pragma solidity ^0.5.0;

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

contract ProxyAssurance is SafeMath {
    address public assurance_address;
    Assurance assurance;

    //DentacoinToken address
    address public dentacoin_token_address = 0x19f49a24c7CB0ca1cbf38436A86656C2F30ab362;
    //DentacoinToken instance
    DentacoinToken dcn = DentacoinToken(dentacoin_token_address);

    constructor(address _assurance_address) public {
        assurance_address = _assurance_address;
        assurance = Assurance(assurance_address);
    }

    // ==================================== MODIFIERS ====================================
    modifier validPatientDentistAddresses(address _patient_addr, address _dentist_addr)  {
        //check if both patient and dentist address are valid
        require(_patient_addr != address(0) && _dentist_addr != address(0), "Patient and Dentist addresses must be valid.");
        //check if valid dentist and patient address are different
        require(_patient_addr != _dentist_addr, "Patient and Dentist addresses cannot match.");
        _;
    }

    modifier checkIfPaused() {
        require(!assurance.getContractPaused(), "Contract is paused. Please try again later.");
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

    //can be called by patient and by dentist
    function registerContract(address _patient_addr, address _dentist_addr, uint256 _value_usd, uint256 _value_dcn, uint256 _date_start_contract, string memory _contract_ipfs_hash) public validPatientDentistAddresses(_patient_addr, _dentist_addr) checkIfPaused {
        //check if one of the patient or dentist is the one who call the method
        require(msg.sender == _patient_addr || msg.sender == _dentist_addr, "Contract cannot be created for other parties. A Patient and Dentist can only create a contract between themselves.");
        //check if value for USD and DCN are valid
        require(_value_usd > 0 && _value_dcn > 0, "USD and DCN values must be valid.");
        //check if this patient is not already registered for this dentist, so there cannot be overwrite
        require(assurance.getContractNextTransfer(_patient_addr, _dentist_addr) == 0, "Only one contract is allowed per Patient - Dentist pair.");
        //check if this patient already allowed Assurance to use his DCN in DentacoinToken
        require(dcn.allowance(_patient_addr, assurance_address) >= assurance.getMinAllowedAmount(), "This Patient has not allowed the Assurance contract to automatically manage their Dentacoin tokens.");


        //if dentist is the one who calls the method the patient should approve the new contract
        if (msg.sender == _dentist_addr) {
            assurance.registerContract(_patient_addr, msg.sender, _date_start_contract, true, false, false, _value_usd, _value_dcn, _contract_ipfs_hash);
        } else {
            assurance.registerContract(msg.sender, _dentist_addr, _date_start_contract, false, true, false, _value_usd, _value_dcn, _contract_ipfs_hash);
        }

        //inserting dentistSentProposal so patient can track his incoming contracts from dentists
        assurance.insertPatientContractHistory(_patient_addr, _dentist_addr);

        emit logSuccessfulContractRegistration(_dentist_addr, _patient_addr, now, _value_usd, _value_dcn);
    }

    //called by dentist
    function dentistApproveContract(address _patient_addr) public validPatientDentistAddresses(_patient_addr, msg.sender) checkIfPaused {
        assurance.dentistApproveContract(_patient_addr, msg.sender);
        emit logSuccessfulContractApproval(_patient_addr, msg.sender);
    }

    //called by patient
    function patientApproveContract(address _dentist_addr) public validPatientDentistAddresses(msg.sender, _dentist_addr) checkIfPaused {
        assurance.patientApproveContract(msg.sender, _dentist_addr);
        emit logSuccessfulContractApproval(msg.sender, _dentist_addr);
    }

    function singleWithdraw(address _patient_addr) public checkIfPaused {
        uint256 discount = 0;

        //'caching' the check for next withdraw for same patient
        if (!assurance.getContractValidationChecked(_patient_addr, msg.sender)) {
            //check if both patient and dentist address are valid
            require(_patient_addr != address(0) && msg.sender != address(0), "Patient and Dentist addresses must be valid.");
            //check if valid dentist and patient address are different
            require(_patient_addr != msg.sender, "Patient and Dentist addresses cannot match.");
            //check if contract is approved by the dentist and by the patient
            require(assurance.getContractApprovedByDentist(_patient_addr, msg.sender) && assurance.getContractApprovedByPatient(_patient_addr, msg.sender), "Both Patient and Dentist must agree to the contract terms, before the contract becomes active.");

            assurance.updateValidationCheck(_patient_addr, msg.sender);
        }

        //check if time passed for dentist to withdraw his dentacoins from patient
        require(now > assurance.getContractNextTransfer(_patient_addr, msg.sender), "Please wait, you cannot withdraw your Dentacoin tokens yet.");

        uint256 months_num = 1;
        //time range from the last withdraw from this patient till now
        uint256 time_range = sub(now, assurance.getContractNextTransfer(_patient_addr, msg.sender));
        //adding the number of months in a row dentists didn't pull his DCN from the patient
        months_num += div(time_range, assurance.getPeriodToWithdraw());
        //time_passed_for_next_withdraw is the time that passed until the next_withdraw , but not finished the full period_to_withdraw for next_withdraw
        uint256 time_passed_for_next_withdraw = sub(time_range, (months_num - 1) * assurance.getPeriodToWithdraw());

        //getting the amount that should be transfered to dentist for all the months since the contract init
        uint256 current_withdraw_amount;
        //transfer DCN to dentist
        uint256 dcnForOneMonthWhenUsdOverDcn;
        uint256 nextTransferTime;

        if (assurance.getUsdOverDcn()) {
            //IF USD MATTERS
            //here we multiply the value_usd with 10**api_decimals, because the api_result_dcn_usd_price is multiplied earlier with 10**api_decimals. Doing this, because solidity doesn't support floats yet
            dcnForOneMonthWhenUsdOverDcn = div(mul(assurance.getContractUsdValue(_patient_addr, msg.sender), 10 ** assurance.getApiDecimals()), assurance.getApiResultDcnUsdPrice());
            //here we multiply the amount each month with the number of months for which dentist didn't make withdraw
            current_withdraw_amount = mul(dcnForOneMonthWhenUsdOverDcn, months_num);
            nextTransferTime = add(now, sub(assurance.getPeriodToWithdraw(), time_passed_for_next_withdraw));

            if (current_withdraw_amount > dcn.balanceOf(_patient_addr)) {
                // if patient has not enough DCN to cover all the months he has to pay for, then withdraw only the amount he has
                uint256 availableMonthsToPay = div(dcn.balanceOf(_patient_addr), dcnForOneMonthWhenUsdOverDcn);

                current_withdraw_amount = mul(dcnForOneMonthWhenUsdOverDcn, availableMonthsToPay);
                nextTransferTime = add(mul(availableMonthsToPay, assurance.getPeriodToWithdraw()), assurance.getContractNextTransfer(_patient_addr, msg.sender));
            }
        } else {
            //IF DCN MATTERS
            current_withdraw_amount = mul(assurance.getContractDcnValue(_patient_addr, msg.sender), months_num);
            nextTransferTime = add(now, sub(assurance.getPeriodToWithdraw(), time_passed_for_next_withdraw));

            if (current_withdraw_amount > dcn.balanceOf(_patient_addr)) {
                // if patient has not enough DCN to cover all the months he has to pay for, then withdraw only the amount he has
                uint256 availableMonthsToPay = div(dcn.balanceOf(_patient_addr), assurance.getContractDcnValue(_patient_addr, msg.sender));

                current_withdraw_amount = mul(assurance.getContractDcnValue(_patient_addr, msg.sender), availableMonthsToPay);
                nextTransferTime = add(mul(availableMonthsToPay, assurance.getPeriodToWithdraw()), assurance.getContractNextTransfer(_patient_addr, msg.sender));
            }
        }

        //check in state contract if patient has registered discount and discount him
        if (assurance.getDiscount(_patient_addr) > 0) {
            discount = assurance.getDiscount(_patient_addr);
            current_withdraw_amount = sub(current_withdraw_amount, discount);

            //decrease discount in state contract
            assurance.decreaseDiscount(_patient_addr, discount);
        }

        require(dcn.balanceOf(_patient_addr) >= current_withdraw_amount, "This Patient has not enough Dentacoin balance.");

        //check if patient had registered discount and send that discount to the dentist
        if (discount > 0) {
            require(dcn.transfer(msg.sender, discount));
        }

        //updating next_transfer timestamp
        assurance.updateNextTransferTime(_patient_addr, msg.sender, nextTransferTime);

        //transferring the DCN from patient to dentist
        assurance.dcnTransferFrom(_patient_addr, msg.sender, current_withdraw_amount);
        emit logSuccessfulWithdraw(msg.sender, _patient_addr, current_withdraw_amount, now);
    }

    function multipleWithdraw(address[] memory _array) public checkIfPaused {
        uint256 len = _array.length;
        for (uint256 i = 0; i < len; i += 1) {
            singleWithdraw(_array[i]);
        }
    }

    function breakContract(address _patient_addr, address _dentist_addr) public validPatientDentistAddresses(_patient_addr, _dentist_addr) checkIfPaused {
        //check if patient or dentist calling
        require(msg.sender == _patient_addr || msg.sender == _dentist_addr, "Only the Patient, or the Dentist can void the contract.");
        //check if this patient is registered for this dentist
        require(assurance.getContractNextTransfer(_patient_addr, _dentist_addr) > 0, "This Patient does not have a contract with this Dentist.");

        assurance.breakContract(_patient_addr, _dentist_addr);

        emit logSuccessfulContractBreak(_dentist_addr, _patient_addr, now);
    }
}

interface DentacoinToken {
    function balanceOf(address _owner) view external returns (uint256);

    function transferFrom(address _from, address _to, uint256 _value) external returns (bool success);

    function transfer(address _to, uint256 _value) external returns (bool success);

    function allowance(address _owner, address _spender) external view returns (uint256);

    function approve(address _spender, uint256 _value) external returns (bool success);
}

interface Assurance {
    function getDiscount(address _patient_addr) external view returns (uint256);

    function decreaseDiscount(address _patient_addr, uint256 _amount) external;

    function getDentist(address _dentist_addr) external view returns (bool, address[] memory);

    function dcnTransferFrom(address _patient_addr, address _dentist_addr, uint256 _amount) external;

    function registerContract(address _patient_addr, address _dentist_addr, uint256 _date_start_contract, bool _approved_by_dentist, bool _approved_by_patient, bool _validation_checked, uint256 _value_usd, uint256 _value_dcn, string calldata _contract_ipfs_hash) external;

function insertPatientContractHistory(address _patient_addr, address _dentist_addr) external;

function dentistApproveContract(address _patient_addr, address _dentist_addr) external;

function patientApproveContract(address _patient_addr, address _dentist_addr) external;

function updateValidationCheck(address _patient_addr, address _dentist_addr) external;

function updateNextTransferTime(address _patient_addr, address _dentist_addr, uint256 _next_transfer) external;

function getUsdOverDcn() external view returns (bool);

function getApiDecimals() external view returns (uint256);

function getApiResultDcnUsdPrice() external view returns (uint256);

function getMinAllowedAmount() external view returns (uint256);

function getPeriodToWithdraw() external view returns (uint256);

function breakContract(address _patient_addr, address _dentist_addr) external;

function getContractNextTransfer(address _patient_addr, address _dentist_addr) external view returns (uint256);

function getContractApprovedByDentist(address _patient_addr, address _dentist_addr) external view returns (bool);

function getContractApprovedByPatient(address _patient_addr, address _dentist_addr) external view returns (bool);

function getContractValidationChecked(address _patient_addr, address _dentist_addr) external view returns (bool);

function getContractUsdValue(address _patient_addr, address _dentist_addr) external view returns (uint256);

function getContractDcnValue(address _patient_addr, address _dentist_addr) external view returns (uint256);

function getContractPaused() external view returns (bool);
}