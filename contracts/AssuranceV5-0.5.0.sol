pragma solidity ^0.5.0;

contract Ownable {
    address public owner;
    address public admin;

    constructor() public {
        owner = msg.sender;
        admin = msg.sender;
    }

    modifier onlyOwner() {
        require(msg.sender == owner);
        _;
    }

    modifier onlyOwnerOrAdmin() {
        require(msg.sender == owner || msg.sender == admin);
        _;
    }

    function transferOwnership(address _new_owner) public onlyOwner {
        require(_new_owner != address(0));
        owner = _new_owner;
    }

    function transferAdmin(address _new_admin) public onlyOwner {
        require(_new_admin != address(0));
        admin = _new_admin;
    }
}

contract SafeMath {
    /**
    * @dev Multiplies two numbers, reverts on overflow.
    */
    function mul(uint256 a, uint256 b) internal pure returns (uint256) {
        // Gas optimization: this is cheaper than requiring 'a' not being zero, but the
        // benefit is lost if 'b' is also tested.
        // See: https://github.com/OpenZeppelin/openzeppelin-solidity/pull/522
        if (a == 0) {
            return 0;
        }

        uint256 c = a * b;
        require(c / a == b);

        return c;
    }

    /**
    * @dev Integer division of two numbers truncating the quotient, reverts on division by zero.
    */
    function div(uint256 a, uint256 b) internal pure returns (uint256) {
        require(b > 0); // Solidity only automatically asserts when dividing by 0
        uint256 c = a / b;
        // assert(a == b * c + a % b); // There is no case in which this doesn't hold

        return c;
    }

    /**
    * @dev Subtracts two numbers, reverts on overflow (i.e. if subtrahend is greater than minuend).
    */
    function sub(uint256 a, uint256 b) internal pure returns (uint256) {
        require(b <= a);
        uint256 c = a - b;

        return c;
    }

    /**
    * @dev Adds two numbers, reverts on overflow.
    */
    function add(uint256 a, uint256 b) internal pure returns (uint256) {
        uint256 c = a + b;
        require(c >= a);

        return c;
    }

    /**
    * @dev Divides two numbers and returns the remainder (unsigned integer modulo),
    * reverts when dividing by zero.
    */
    function mod(uint256 a, uint256 b) internal pure returns (uint256) {
        require(b != 0);
        return a % b;
    }
}

contract ownerSettings is Ownable {
    bool public contract_paused = false;
    uint256 public period_to_withdraw = 180; //3 minutes for testing purpose
    uint256 public min_allowed_amount = 8000000000000; //minimum allowed amount to sign a contract
    uint256 public api_result_dcn_usd_price = 1700000; //usd for one dcn
    uint256 public api_decimals = 10; //decimals, because solidity doesn't support float at this time
    bool public usd_over_dcn = true;
    //DentacoinToken address
    address public dentacoin_token_address = 0x19f49a24c7CB0ca1cbf38436A86656C2F30ab362;
    //DentacoinToken instance
    DentacoinToken dcn = DentacoinToken(dentacoin_token_address);

    function circuitBreaker() public onlyOwner {
        if(!contract_paused) {
            contract_paused = true;
        }else {
            contract_paused = false;
        }
    }

    function changePeriodToWithdraw(uint256 _period_to_withdraw) public onlyOwner {
        period_to_withdraw = _period_to_withdraw;
    }

    function changeDentacoinTokenAddress(address _dentacoin_token_address) public onlyOwner {
        dentacoin_token_address = _dentacoin_token_address;
    }

    function changeMinimumAllowedAmount(uint256 _min_allowed_amount) public onlyOwner {
        min_allowed_amount = _min_allowed_amount;
    }

    function changeUsdOverDcn(bool _usd_over_dcn) public onlyOwner {
        usd_over_dcn = _usd_over_dcn;
    }

    // ====== PRICE SETTERS ======
    function changeApiResultDcnUsdPrice(uint256 _api_result_dcn_usd_price) public onlyOwnerOrAdmin {
        api_result_dcn_usd_price = _api_result_dcn_usd_price;
    }

    function changeApiDecimals(uint256 _api_decimals) public onlyOwner {
        api_decimals = _api_decimals;
    }
    // ====== /PRICE SETTERS ======
}

contract Assurance is ownerSettings, SafeMath {
    // ==================================== STATE ====================================
    address public AssuranceContract = address(this);

    struct contractStruct {
        uint256 next_transfer;
        bool approved_by_dentist;
        bool approved_by_patient;
        bool validation_checked;
        uint256 value_usd;
        uint256 value_dcn;
        string contract_ipfs_hash;
        uint256 index_in_patients_addresses;
    }

    struct dentistStruct {
        bool exists;
        address[] patients_addresses; //list of patients addresses for THIS dentist
        mapping (address => contractStruct) contracts; //list of contracts for THIS dentist
    }

    struct patientContractHistory {
        mapping (address => dentistSentProposal) dentists;
        address[] dentists_addresses;
    }

    struct dentistSentProposal {
        uint256 index_in_dentists_addresses;
        bool exists;
    }

    mapping (address => patientContractHistory) patient_contract_history; //incoming contracts for patient, waiting his approval
    mapping (address => dentistStruct) dentists; //list of dentists
    address[] dentists_addresses;
    // ==================================== /STATE ====================================

    // ==================================== MODIFIERS ====================================
    modifier validPatientDentistAddresses(address _patient_addr, address _dentist_addr)  {
        //check if both patient and dentist address are valid
        require(_patient_addr != address(0) && _dentist_addr != address(0), "Patient and Dentist addresses must be valid.");
        //check if valid dentist and patient address are different
        require(_patient_addr != _dentist_addr, "Patient and Dentist addresses cannot match.");
        _;
    }

    modifier checkIfPaused() {
        require(!contract_paused, "Contract is paused. Please try again later.");
        _;
    }
    // ==================================== /MODIFIERS ====================================

    // ==================================== EVENTS ====================================
    event logSuccessfulWithdraw(address indexed _dentist_addr, address indexed _patient_addr, uint256 _value, uint256 _date);

    event logSuccessfulDentistRegistration(address indexed _dentist_addr, uint256 _date);

    event logSuccessfulContractRegistration(address indexed _dentist_addr, address indexed _patient_addr, uint256 _date, uint256 _value_usd, uint256 _value_dcn);

    event logSuccessfulContractBreak(address indexed _dentist_addr, address indexed _patient_addr, uint256 _date);

    event logSuccessfulContractApproval(address indexed _patient_addr, address indexed _dentist_addr);
    // ==================================== /EVENTS ====================================

    // ==================================== LOGIC ====================================
    //can be called by patient and by dentist
    function registerContract(address _patient_addr, address _dentist_addr, uint256 _value_usd, uint256 _value_dcn, uint256 _date_start_contract, string memory _contract_ipfs_hash) public validPatientDentistAddresses(_patient_addr, _dentist_addr) checkIfPaused {
        //check if one of the patient or dentist is the one who call the method
        require(msg.sender == _patient_addr || msg.sender == _dentist_addr, "Contract cannot be created for other parties. A Patient and Dentist can only create a contract between themselves.");
        //check if this dentist is already registered one in the contract (used registerDentist method to register him self)
        require(dentists[_dentist_addr].exists, "Please select a Dentist who is already registered in the Assurance contract database.");
        //check if this patient already allowed Assurance to use his DCN in DentacoinToken
        require(dcn.allowance(_patient_addr, AssuranceContract) >= min_allowed_amount, "This Patient has not allowed the Assurance contract to automatically manage their Dentacoin tokens.");
        //check if value for USD and DCN are valid
        require(_value_usd > 0 && _value_dcn > 0, "USD and DCN values must be valid.");
        //check if this patient is not already registered for this dentist, so there cannot be overwrite
        require(dentists[_dentist_addr].contracts[_patient_addr].next_transfer == 0, "Only one contract is allowed per Patient - Dentist pair.");


        //if dentist is the one who calls the method the patient should approve the new contract
        if(msg.sender == _dentist_addr) {
            dentists[_dentist_addr].contracts[_patient_addr] = contractStruct(_date_start_contract, true, false, false, _value_usd, _value_dcn, _contract_ipfs_hash, dentists[_dentist_addr].patients_addresses.push(_patient_addr) - 1);
        } else {
            dentists[_dentist_addr].contracts[_patient_addr] = contractStruct(_date_start_contract, false, true, false, _value_usd, _value_dcn, _contract_ipfs_hash, dentists[_dentist_addr].patients_addresses.push(_patient_addr) - 1);
        }

        //inserting dentistSentProposal so patient can track his incoming contracts from dentists
        patient_contract_history[_patient_addr].dentists[_dentist_addr] = dentistSentProposal(patient_contract_history[_patient_addr].dentists_addresses.push(_dentist_addr) - 1, true);

        emit logSuccessfulContractRegistration(_dentist_addr, _patient_addr, now, _value_usd, _value_dcn);
    }

    //called by dentist
    function dentistApproveContract(address _patient_addr) public validPatientDentistAddresses(_patient_addr, msg.sender) checkIfPaused {
        dentists[msg.sender].contracts[_patient_addr].approved_by_dentist = true;
        emit logSuccessfulContractApproval(_patient_addr, msg.sender);
    }

    //called by patient
    function patientApproveContract(address _dentist_addr) public validPatientDentistAddresses(msg.sender, _dentist_addr) checkIfPaused {
        dentists[_dentist_addr].contracts[msg.sender].approved_by_patient = true;
        emit logSuccessfulContractApproval(msg.sender, _dentist_addr);
    }

    //called by dentist
    function registerDentist() public checkIfPaused {
        //check if dentist is registered in the dentists mapping, so there cannot be overwrite
        require(!dentists[msg.sender].exists, "A Dentist can only be registered once in the Assurance contract.");

        dentists[msg.sender] = dentistStruct(true, new address[](0));
        dentists_addresses.push(msg.sender);
        emit logSuccessfulDentistRegistration(msg.sender, now);
    }

    function singleWithdraw(address _patient_addr) public {
        //'caching' the check for next withdraw for same patient
        if(!dentists[msg.sender].contracts[_patient_addr].validation_checked) {
            //check if both patient and dentist address are valid
            require(_patient_addr != address(0) && msg.sender != address(0), "Patient and Dentist addresses must be valid.");
            //check if valid dentist and patient address are different
            require(_patient_addr != msg.sender, "Patient and Dentist addresses cannot match.");
            //check if contract is approved by the dentist and by the patient
            require(dentists[msg.sender].contracts[_patient_addr].approved_by_dentist && dentists[msg.sender].contracts[_patient_addr].approved_by_patient, "Both Patient and Dentist must agree to the contract terms, before the contract becomes active.");

            dentists[msg.sender].contracts[_patient_addr].validation_checked = true;
        }

        //check if time passed for dentist to withdraw his dentacoins from patient
        require(now > dentists[msg.sender].contracts[_patient_addr].next_transfer, "Please wait, you cannot withdraw your Dentacoin tokens yet.");

        uint256 months_num = 1;
        //time range from the last withdraw from this patient till now
        uint256 time_range = sub(now, dentists[msg.sender].contracts[_patient_addr].next_transfer);
        //adding the number of months in a row dentists didn't pull his DCN from the patient
        months_num+=div(time_range, period_to_withdraw);
        //time_passed_for_next_withdraw is the time that passed until the next_withdraw , but not finished the full period_to_withdraw for next_withdraw
        uint256 time_passed_for_next_withdraw = sub(time_range, ((months_num - 1) * period_to_withdraw));

        //updating next_transfer timestamp
        dentists[msg.sender].contracts[_patient_addr].next_transfer = add(now, sub(period_to_withdraw, time_passed_for_next_withdraw));

        //getting the amount that should be transfered to dentist for all the months since the contract init
        uint256 current_withdraw_amount;
        //transfer DCN to dentist

        if(usd_over_dcn) {
            //IF USD MATTERS
            //here we multiply the value_usd with 10**api_decimals, because the api_result_dcn_usd_price is multiplied earlier with 10**api_decimals. Doing this, because solidity doesn't support floats yet
            current_withdraw_amount = div(mul(dentists[msg.sender].contracts[_patient_addr].value_usd, 10**api_decimals), api_result_dcn_usd_price);
            //here we multiply the amount each month with the number of months for which dentist didn't make withdraw
            current_withdraw_amount = mul(current_withdraw_amount, months_num);
        } else {
            //IF DCN MATTERS
            current_withdraw_amount = mul(dentists[msg.sender].contracts[_patient_addr].value_dcn, months_num);
        }

        //transferring the DCN from patient to dentist
        require(dcn.transferFrom(_patient_addr, msg.sender, current_withdraw_amount));
        emit logSuccessfulWithdraw(msg.sender, _patient_addr, current_withdraw_amount, now);
    }

    function multipleWithdraw(address[] memory _array) public {
        uint256 len = _array.length;
        for(uint256 i = 0; i < len; i+=1) {
            singleWithdraw(_array[i]);
        }
    }

    //can be called from patient and dentist
    function breakContract(address _patient_addr, address _dentist_addr) public validPatientDentistAddresses(_patient_addr, _dentist_addr) checkIfPaused {
        //check if patient or dentist calling
        require(msg.sender == _patient_addr || msg.sender == _dentist_addr, "Only the Patient, or the Dentist can void the contract.");
        //check if this patient is registered for this dentist
        require(dentists[_dentist_addr].contracts[_patient_addr].next_transfer > 0, "This Patient does not have a contract with this Dentist.");

        //if there is proposal recorded from this dentist for this patient ----> delete it
        if(patient_contract_history[_patient_addr].dentists[_dentist_addr].exists) {
            //deleting the dentist address from the dentists_addresses array for the current patient
            uint256 proposal_row_to_delete = patient_contract_history[_patient_addr].dentists[_dentist_addr].index_in_dentists_addresses;
            address proposal_key_to_move = patient_contract_history[_patient_addr].dentists_addresses[patient_contract_history[_patient_addr].dentists_addresses.length-1];
            patient_contract_history[_patient_addr].dentists_addresses[proposal_row_to_delete] = proposal_key_to_move;
            patient_contract_history[_patient_addr].dentists[proposal_key_to_move].index_in_dentists_addresses = proposal_row_to_delete;
            patient_contract_history[_patient_addr].dentists_addresses.length--;

            //deleting the struct
            delete patient_contract_history[_patient_addr].dentists[_dentist_addr];
        }

        //deleting the patient address from the patients_addresses array for the current dentist
        uint256 row_to_delete = dentists[_dentist_addr].contracts[_patient_addr].index_in_patients_addresses;
        address key_to_move = dentists[_dentist_addr].patients_addresses[dentists[_dentist_addr].patients_addresses.length-1];
        dentists[_dentist_addr].patients_addresses[row_to_delete] = key_to_move;
        dentists[key_to_move].contracts[_patient_addr].index_in_patients_addresses = row_to_delete;
        dentists[_dentist_addr].patients_addresses.length--;

        //deleting the patient struct from the dentist patients mapping
        delete dentists[_dentist_addr].contracts[_patient_addr];
        emit logSuccessfulContractBreak(_dentist_addr, _patient_addr, now);
    }

    /// @dev add a fallback method including require(msg.data.length == 0) to prevent invalid calls.

    // ====== GETTERS ======
    function getDentist(address _dentist_addr) public view returns(bool, address[] memory) {
        return (dentists[_dentist_addr].exists, dentists[_dentist_addr].patients_addresses);
    }

    function getDentistsArr() public view returns(address[] memory) {
        return dentists_addresses;
    }

    function getPatient(address _patient_addr, address _dentist_addr) public view validPatientDentistAddresses(_patient_addr, _dentist_addr) returns(uint256, bool, bool, bool, uint256, uint256, string memory) {
        contractStruct memory patient = dentists[_dentist_addr].contracts[_patient_addr];
        return (patient.next_transfer, patient.approved_by_dentist, patient.approved_by_patient, patient.validation_checked, patient.value_usd, patient.value_dcn, patient.contract_ipfs_hash);
    }

    function getWaitingContractsForPatient(address _patient_addr) public view returns(address[] memory) {
        return patient_contract_history[_patient_addr].dentists_addresses;
    }
    // ====== /GETTERS ======

    // ==================================== /LOGIC ====================================
}

interface DentacoinToken {
    function balanceOf(address _owner) view external returns (uint256);
    function transferFrom(address _from, address _to, uint256 _value) external returns (bool success);
    function transfer(address _to, uint256 _value) external returns (bool success);
    function allowance(address _owner, address _spender) external view returns (uint256);
    function approve(address _spender, uint256 _value) external returns (bool success);
}