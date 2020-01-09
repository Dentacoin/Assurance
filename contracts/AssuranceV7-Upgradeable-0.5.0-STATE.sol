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
        require(b > 0);
        // Solidity only automatically asserts when dividing by 0
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
    uint256 public period_to_withdraw = 2592000; //one month
    uint256 public min_allowed_amount = 8000000000000; //minimum allowed amount to sign a contract
    uint256 public api_result_dcn_usd_price = 1700000; //usd for one dcn
    uint256 public api_decimals = 10; //decimals, because solidity doesn't support float at this time
    bool public usd_over_dcn = true;
    address public proxy_contract;


    // ==================================== MODIFIERS ====================================
    modifier onlyApprovedProxy() {
        require(msg.sender == proxy_contract);
        _;
    }

    modifier onlyApprovedProxyOrAdmin() {
        require(msg.sender == proxy_contract || msg.sender == admin);
        _;
    }

    modifier checkIfPaused() {
        require(!contract_paused, "Contract is paused. Please try again later.");
        _;
    }
    // ==================================== /MODIFIERS ====================================

    function circuitBreaker() public onlyOwner {
        if (!contract_paused) {
            contract_paused = true;
        } else {
            contract_paused = false;
        }
    }

    function changePeriodToWithdraw(uint256 _period_to_withdraw) public onlyOwner {
        period_to_withdraw = _period_to_withdraw;
    }

    function changeProxyAddress(address _proxy_contract) public onlyOwner {
        proxy_contract = _proxy_contract;
    }

    function changeMinimumAllowedAmount(uint256 _min_allowed_amount) public onlyOwner {
        min_allowed_amount = _min_allowed_amount;
    }

    function changeUsdOverDcn(bool _usd_over_dcn) public onlyOwner {
        usd_over_dcn = _usd_over_dcn;
    }

    // ====== SETTERS ======
    function changeApiResultDcnUsdPrice(uint256 _api_result_dcn_usd_price) public onlyOwnerOrAdmin {
        api_result_dcn_usd_price = _api_result_dcn_usd_price;
    }

    function changeApiDecimals(uint256 _api_decimals) public onlyOwnerOrAdmin {
        api_decimals = _api_decimals;
    }
    // ====== /SETTERS ======

    // ====== GETTERS ======
    function getPeriodToWithdraw() public view returns (uint256) {
        return period_to_withdraw;
    }

    function getMinAllowedAmount() public view returns (uint256) {
        return min_allowed_amount;
    }

    function getApiResultDcnUsdPrice() public view returns (uint256) {
        return api_result_dcn_usd_price;
    }

    function getApiDecimals() public view returns (uint256) {
        return api_decimals;
    }

    function getUsdOverDcn() public view returns (bool) {
        return usd_over_dcn;
    }

    function getContractPaused() public view returns (bool) {
        return contract_paused;
    }
    // ====== /GETTERS ======
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
        mapping(address => contractStruct) contracts; //list of contracts for THIS dentist
    }

    struct patientContractHistory {
        mapping(address => dentistSentProposal) dentists;
        address[] dentists_addresses;
    }

    struct dentistSentProposal {
        uint256 index_in_dentists_addresses;
        bool exists;
    }

    mapping(address => patientContractHistory) patient_contract_history; //incoming contracts for patient, waiting his approval
    mapping(address => dentistStruct) dentists; //list of dentists
    address[] dentists_addresses;

    mapping(address => uint256) discounts;
    // ==================================== /STATE ====================================

    // ==================================== LOGIC ====================================
    function registerDiscount(address _patient_addr, uint256 _amount) onlyOwnerOrAdmin public {
        discounts[_patient_addr] = add(discounts[_patient_addr], _amount);
    }

    function getDiscount(address _patient_addr) public view returns (uint256) {
        return discounts[_patient_addr];
    }

    function decreaseDiscount(address _patient_addr, uint256 _amount) external onlyApprovedProxy checkIfPaused {
        discounts[_patient_addr] = sub(discounts[_patient_addr], _amount);
    }

    function registerContract(address _patient_addr, address _dentist_addr, uint256 _date_start_contract, bool _approved_by_dentist, bool _approved_by_patient, bool _validation_checked, uint256 _value_usd, uint256 _value_dcn, string calldata _contract_ipfs_hash) external onlyApprovedProxy checkIfPaused {
        //if dentist not registered in the mapping add him
        if (!dentists[_dentist_addr].exists) {
            dentists[_dentist_addr] = dentistStruct(true, new address[](0));
            dentists_addresses.push(_dentist_addr);
        }

        dentists[_dentist_addr].contracts[_patient_addr] = contractStruct(_date_start_contract, _approved_by_dentist, _approved_by_patient, _validation_checked, _value_usd, _value_dcn, _contract_ipfs_hash, dentists[_dentist_addr].patients_addresses.push(_patient_addr) - 1);
    }

    function updateNextTransferTime(address _patient_addr, address _dentist_addr, uint256 _next_transfer) external onlyApprovedProxy checkIfPaused {
        dentists[_dentist_addr].contracts[_patient_addr].next_transfer = _next_transfer;
    }

    function dentistApproveContract(address _patient_addr, address _dentist_addr) external onlyApprovedProxy checkIfPaused {
        dentists[_dentist_addr].contracts[_patient_addr].approved_by_dentist = true;
    }

    function patientApproveContract(address _patient_addr, address _dentist_addr) external onlyApprovedProxy checkIfPaused {
        dentists[_dentist_addr].contracts[_patient_addr].approved_by_patient = true;
    }

    function insertPatientContractHistory(address _patient_addr, address _dentist_addr) external onlyApprovedProxy checkIfPaused {
        patient_contract_history[_patient_addr].dentists[_dentist_addr] = dentistSentProposal(patient_contract_history[_patient_addr].dentists_addresses.push(_dentist_addr) - 1, true);
    }

    function updateValidationCheck(address _patient_addr, address _dentist_addr) external onlyApprovedProxy checkIfPaused {
        dentists[_dentist_addr].contracts[_patient_addr].validation_checked = true;
    }

    //can be called from patient and dentist
    function breakContract(address _patient_addr, address _dentist_addr) public onlyApprovedProxyOrAdmin checkIfPaused {
        //if there is proposal recorded from this dentist for this patient ----> delete it
        if (patient_contract_history[_patient_addr].dentists[_dentist_addr].exists) {
            //deleting the dentist address from the dentists_addresses array for the current patient
            uint256 proposal_row_to_delete = patient_contract_history[_patient_addr].dentists[_dentist_addr].index_in_dentists_addresses;
            address proposal_key_to_move = patient_contract_history[_patient_addr].dentists_addresses[patient_contract_history[_patient_addr].dentists_addresses.length - 1];
            patient_contract_history[_patient_addr].dentists_addresses[proposal_row_to_delete] = proposal_key_to_move;
            patient_contract_history[_patient_addr].dentists[proposal_key_to_move].index_in_dentists_addresses = proposal_row_to_delete;
            patient_contract_history[_patient_addr].dentists_addresses.length--;

            //deleting the struct
            delete patient_contract_history[_patient_addr].dentists[_dentist_addr];
        }

        //deleting the patient address from the patients_addresses array for the current dentist
        uint256 row_to_delete = dentists[_dentist_addr].contracts[_patient_addr].index_in_patients_addresses;
        address key_to_move = dentists[_dentist_addr].patients_addresses[dentists[_dentist_addr].patients_addresses.length - 1];
        dentists[_dentist_addr].patients_addresses[row_to_delete] = key_to_move;
        dentists[key_to_move].contracts[_patient_addr].index_in_patients_addresses = row_to_delete;
        dentists[_dentist_addr].patients_addresses.length--;

        //deleting the patient struct from the dentist patients mapping
        delete dentists[_dentist_addr].contracts[_patient_addr];
    }

    // ====== GETTERS ======
    function getDentist(address _dentist_addr) public view returns (bool, address[] memory) {
        return (dentists[_dentist_addr].exists, dentists[_dentist_addr].patients_addresses);
    }

    function getDentistsArr() public view returns (address[] memory) {
        return dentists_addresses;
    }

    function getPatient(address _patient_addr, address _dentist_addr) public view returns (uint256, bool, bool, bool, uint256, uint256, string memory) {
        contractStruct memory patient = dentists[_dentist_addr].contracts[_patient_addr];
        return (patient.next_transfer, patient.approved_by_dentist, patient.approved_by_patient, patient.validation_checked, patient.value_usd, patient.value_dcn, patient.contract_ipfs_hash);
    }

    function getContractNextTransfer(address _patient_addr, address _dentist_addr) public view returns (uint256) {
        return dentists[_dentist_addr].contracts[_patient_addr].next_transfer;
    }

    function getContractApprovedByDentist(address _patient_addr, address _dentist_addr) public view returns (bool) {
        return dentists[_dentist_addr].contracts[_patient_addr].approved_by_dentist;
    }

    function getContractApprovedByPatient(address _patient_addr, address _dentist_addr) public view returns (bool) {
        return dentists[_dentist_addr].contracts[_patient_addr].approved_by_patient;
    }

    function getContractValidationChecked(address _patient_addr, address _dentist_addr) public view returns (bool) {
        return dentists[_dentist_addr].contracts[_patient_addr].validation_checked;
    }

    function getContractUsdValue(address _patient_addr, address _dentist_addr) public view returns (uint256) {
        return dentists[_dentist_addr].contracts[_patient_addr].value_usd;
    }

    function getContractDcnValue(address _patient_addr, address _dentist_addr) public view returns (uint256) {
        return dentists[_dentist_addr].contracts[_patient_addr].value_dcn;
    }

    function getWaitingContractsForPatient(address _patient_addr) public view returns (address[] memory) {
        return patient_contract_history[_patient_addr].dentists_addresses;
    }
    // ====== /GETTERS ======
    // ==================================== /LOGIC ====================================
}