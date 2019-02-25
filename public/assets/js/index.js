var {getWeb3, getContractInstance} = require('./helper');

basic.init();

$(document).ready(function() {
    App.init();

    fixButtonsFocus();

    onDocumentReadyPageData();
});

$(window).on('load', function() {
    onWindowLoadPageData();
});

$(window).on('resize', function(){

});

$(window).on('scroll', function()  {

});

//on button click next time when you hover the button the color is bugged until you click some other element (until you move out the focus from this button)
function fixButtonsFocus() {
    if($('.white-blue-green-btn').length > 0) {
        $('.white-blue-green-btn').click(function() {
            $(this).blur();
        });
    }
    if($('.blue-green-white-btn').length > 0) {
        $('.blue-green-white-btn').click(function() {
            $(this).blur();
        });
    }
    if($('.white-transparent-btn').length > 0) {
        $('.white-transparent-btn').click(function() {
            $(this).blur();
        });
    }
}

function generateUrl(str)  {
    var str_arr = str.split('');
    var cyr = [
        'Ð°','Ð±','Ð²','Ð³','Ð´','Ðµ','Ñ‘','Ð¶','Ð·','Ð¸','Ð¹','Ðº','Ð»','Ð¼','Ð½','Ð¾','Ð¿',
        'Ñ€','Ñ','Ñ‚','Ñƒ','Ñ„','Ñ…','Ñ†','Ñ‡','Ñˆ','Ñ‰','ÑŠ','Ñ‹','ÑŒ','Ñ','ÑŽ','Ñ',
        'Ð','Ð‘','Ð’','Ð“','Ð”','Ð•','Ð','Ð–','Ð—','Ð˜','Ð™','Ðš','Ð›','Ðœ','Ð','Ðž','ÐŸ',
        'Ð ','Ð¡','Ð¢','Ð£','Ð¤','Ð¥','Ð¦','Ð§','Ð¨','Ð©','Ðª','Ð«','Ð¬','Ð­','Ð®','Ð¯',' '
    ];
    var lat = [
        'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
        'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
        'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
        'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya','-'
    ];
    for(var i = 0; i < str_arr.length; i+=1)  {
        for(var y = 0; y < cyr.length; y+=1)    {
            if(str_arr[i] == cyr[y])    {
                str_arr[i] = lat[y];
            }
        }
    }
    return str_arr.join('').toLowerCase();
}

function checkIfCookie()    {
    if($('.privacy-policy-cookie').length > 0)  {
        $('.privacy-policy-cookie .accept').click(function()    {
            basic.cookies.set('privacy_policy', 1);
            $('.privacy-policy-cookie').hide();
        });
    }
}

//binding the refresh captcha event to existing button
function initCaptchaRefreshEvent()  {
    if($('.refresh-captcha').length > 0)    {
        $('.refresh-captcha').click(function()  {
            $.ajax({
                type: 'GET',
                url: '/refresh-captcha',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('.captcha-container span').html(response.captcha);
                }
            });
        });
    }
} 

var global_state = {};
var temporally_timestamp = 0;
var App = {
    assurance_state_address: '0xb5f910da40782a5261b9809eefb3a8e183abd025',
    assurance_state_abi: [{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"},{"name":"_next_transfer","type":"uint256"}],"name":"updateNextTransferTime","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getPeriodToWithdraw","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"getContractUsdValue","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"circuitBreaker","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_period_to_withdraw","type":"uint256"}],"name":"changePeriodToWithdraw","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_api_decimals","type":"uint256"}],"name":"changeApiDecimals","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_dentist_addr","type":"address"}],"name":"registerDentist","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_api_result_dcn_usd_price","type":"uint256"}],"name":"changeApiResultDcnUsdPrice","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"dentistApproveContract","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"breakContract","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_patient_addr","type":"address"}],"name":"getWaitingContractsForPatient","outputs":[{"name":"","type":"address[]"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"getContractNextTransfer","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"getApiResultDcnUsdPrice","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_dentacoin_token_address","type":"address"}],"name":"changeDentacoinTokenAddress","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getApiDecimals","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"api_result_dcn_usd_price","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"getContractApprovedByDentist","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"dentacoin_token_address","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_new_admin","type":"address"}],"name":"transferAdmin","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"getContractDcnValue","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"},{"name":"_amount","type":"uint256"}],"name":"dcnTransferFrom","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"AssuranceContract","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"usd_over_dcn","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"getUsdOverDcn","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"updateValidationCheck","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_min_allowed_amount","type":"uint256"}],"name":"changeMinimumAllowedAmount","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"},{"name":"_date_start_contract","type":"uint256"},{"name":"_approved_by_dentist","type":"bool"},{"name":"_approved_by_patient","type":"bool"},{"name":"_validation_checked","type":"bool"},{"name":"_value_usd","type":"uint256"},{"name":"_value_dcn","type":"uint256"},{"name":"_contract_ipfs_hash","type":"string"}],"name":"registerContract","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"insertPatientContractHistory","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"getContractApprovedByPatient","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"api_decimals","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"min_allowed_amount","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_proxy_contract","type":"address"}],"name":"changeProxyAddress","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getDentistsArr","outputs":[{"name":"","type":"address[]"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"contract_paused","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"patientApproveContract","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"getPatient","outputs":[{"name":"","type":"uint256"},{"name":"","type":"bool"},{"name":"","type":"bool"},{"name":"","type":"bool"},{"name":"","type":"uint256"},{"name":"","type":"uint256"},{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"period_to_withdraw","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"proxy_contract","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_dentist_addr","type":"address"}],"name":"getDentist","outputs":[{"name":"","type":"bool"},{"name":"","type":"address[]"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"getContractPaused","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_usd_over_dcn","type":"bool"}],"name":"changeUsdOverDcn","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"getContractValidationChecked","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"getMinAllowedAmount","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_new_owner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"admin","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"}],
    assurance_state_instance: null,
    assurance_proxy_address: '0x0379f43679c3b0520a1165dccc9e5cd9649d8893',
    assurance_proxy_abi: [{"constant":false,"inputs":[],"name":"registerDentist","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"}],"name":"breakContract","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_dentist_addr","type":"address"}],"name":"patientApproveContract","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"dentacoin_token_address","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"}],"name":"dentistApproveContract","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"assurance_address","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"},{"name":"_dentist_addr","type":"address"},{"name":"_value_usd","type":"uint256"},{"name":"_value_dcn","type":"uint256"},{"name":"_date_start_contract","type":"uint256"},{"name":"_contract_ipfs_hash","type":"string"}],"name":"registerContract","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_array","type":"address[]"}],"name":"multipleWithdraw","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_patient_addr","type":"address"}],"name":"singleWithdraw","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"inputs":[{"name":"_assurance_address","type":"address"}],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_dentist_addr","type":"address"},{"indexed":true,"name":"_patient_addr","type":"address"},{"indexed":false,"name":"_value","type":"uint256"},{"indexed":false,"name":"_date","type":"uint256"}],"name":"logSuccessfulWithdraw","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_dentist_addr","type":"address"},{"indexed":false,"name":"_date","type":"uint256"}],"name":"logSuccessfulDentistRegistration","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_dentist_addr","type":"address"},{"indexed":true,"name":"_patient_addr","type":"address"},{"indexed":false,"name":"_date","type":"uint256"},{"indexed":false,"name":"_value_usd","type":"uint256"},{"indexed":false,"name":"_value_dcn","type":"uint256"}],"name":"logSuccessfulContractRegistration","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_dentist_addr","type":"address"},{"indexed":true,"name":"_patient_addr","type":"address"},{"indexed":false,"name":"_date","type":"uint256"}],"name":"logSuccessfulContractBreak","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_patient_addr","type":"address"},{"indexed":true,"name":"_dentist_addr","type":"address"}],"name":"logSuccessfulContractApproval","type":"event"}],
    assurance_proxy_instance: null,
    dentacoin_token_address: "0x19f49a24c7cb0ca1cbf38436a86656c2f30ab362",
    dentacoin_token_abi: [{"constant":false,"inputs":[{"name":"_spender","type":"address"},{"name":"_value","type":"uint256"}],"name":"approve","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[],"name":"buyDentacoinsAgainstEther","outputs":[{"name":"amount","type":"uint256"}],"payable":true,"stateMutability":"payable","type":"function"},{"constant":false,"inputs":[],"name":"haltDirectTrade","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"amountOfEth","type":"uint256"},{"name":"dcn","type":"uint256"}],"name":"refundToOwner","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"amount","type":"uint256"}],"name":"sellDentacoinsAgainstEther","outputs":[{"name":"revenue","type":"uint256"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"newDCNAmount","type":"uint256"}],"name":"setDCNForGas","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"newBuyPriceEth","type":"uint256"},{"name":"newSellPriceEth","type":"uint256"}],"name":"setEtherPrices","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"newGasAmountInWei","type":"uint256"}],"name":"setGasForDCN","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"newGasReserveInWei","type":"uint256"}],"name":"setGasReserve","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"minimumBalanceInWei","type":"uint256"}],"name":"setMinBalance","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_to","type":"address"},{"name":"_value","type":"uint256"}],"name":"transfer","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_from","type":"address"},{"name":"_to","type":"address"},{"name":"_value","type":"uint256"}],"name":"transferFrom","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[],"name":"unhaltDirectTrade","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"payable":true,"stateMutability":"payable","type":"fallback"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_from","type":"address"},{"indexed":true,"name":"_to","type":"address"},{"indexed":false,"name":"_value","type":"uint256"}],"name":"Transfer","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_owner","type":"address"},{"indexed":true,"name":"_spender","type":"address"},{"indexed":false,"name":"_value","type":"uint256"}],"name":"Approval","type":"event"},{"constant":true,"inputs":[{"name":"_owner","type":"address"},{"name":"_spender","type":"address"}],"name":"allowance","outputs":[{"name":"remaining","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_owner","type":"address"}],"name":"balanceOf","outputs":[{"name":"balance","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"buyPriceEth","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"DCNForGas","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"DentacoinAddress","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"directTradeAllowed","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"gasForDCN","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"gasReserve","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"minBalanceForAccounts","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"sellPriceEth","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"}],
    dentacoin_instance: null,
    web3Provider: null,
    web3_0_2: null,
    web3_1_0: null,
    clinics_holder: null,
    contracts: {},
    loading: false,
    init: function() {
        return App.initWeb3();
    },
    initWeb3: async function()    {
        if(typeof(web3) !== 'undefined' && web3.currentProvider.isMetaMask === true) {
            //METAMASK
            App.web3_0_2 = web3;
            global_state.account = App.web3_0_2.eth.defaultAccount;
            //overwrite web3 0.2 with web 1.0
            web3 = getWeb3(App.web3_0_2.currentProvider);
            App.web3_1_0 = web3;
        }else if(typeof(web3) === 'undefined')    {
            //CUSTOM
            if(localStorage.getItem('current-account') != null) {
                global_state.account = JSON.parse(localStorage.getItem('current-account')).address;
            }
            App.web3_1_0 = getWeb3(new Web3.providers.HttpProvider('https://rinkeby.infura.io/v3/c3a8017424324e47be615fb4028275bb'));
        }else {
            //NO CUSTOM, NO METAMASK. Doing this final third check so we can use web3_1_0 functions and utils even if there is no metamask or custom imported/created account
            App.web3_1_0 = getWeb3();
        }

        return App.initContract();
    },
    initContract: async function() {
        //Assurance
        App.assurance_state_instance = new App.web3_1_0.eth.Contract(App.assurance_state_abi, App.assurance_state_address);
        //DentacoinToken
        App.dentacoin_token_instance = new App.web3_1_0.eth.Contract(App.dentacoin_token_abi, App.dentacoin_token_address);

        //init pages logic
        pagesDataOnContractInit();
    },
    dentacoin_token_methods: {
        allowance: function(owner, spender)  {
            return App.dentacoin_token_instance.methods.allowance(owner, spender).call({ from: global_state.account }, function(error, result)   {
                if(!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        approve: function()  {
            return App.dentacoin_token_instance.methods.approve(App.assurance_state_address, 9000000000000).send({
                from: global_state.account,
                gas: 65000
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        }
    },
    assurance_state_methods: {
        getPeriodToWithdraw: function()  {
            return App.assurance_state_instance.methods.getPeriodToWithdraw().call({}, function(error, result)   {
                if(!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        }
        /*getDentist: function(dentist_addr)  {
            return App.assurance_state_instance.methods.getDentist(dentist_addr).call({ from: global_state.account }, function(error, result)   {
                if(!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        getPatient: function(patient_addr, dentist_addr)  {
            return App.assurance_state_instance.methods.getPatient(patient_addr, dentist_addr).call({ from: global_state.account }, function(error, result)   {
                if(!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        getDentistsArr: function()  {
            return App.assurance_state_instance.methods.getDentistsArr().call({ from: global_state.account }, function(error, result)   {
                if(!error)  {
                    console.log(result);
                }else {
                    console.error(error);
                }
            });
        },
        getPatientsArrForDentist: function(dentist_addr)  {
            return App.assurance_state_instance.methods.getPatientsArrForDentist(dentist_addr).call({ from: global_state.account }, function(error, result)   {
                if(!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        getWaitingContractsForPatient: function(patient_addr)  {
            return App.assurance_state_instance.methods.getWaitingContractsForPatient(patient_addr).call({ from: global_state.account }, function(error, result)   {
                if(!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        breakContract: function(patient_addr, dentist_addr)  {
            //check if patient and dentist addresses are valid
            if(!innerAddressCheck(patient_addr) || !innerAddressCheck(dentist_addr)) {
                basic.showAlert('Patient and dentist addresses must be valid.');
                return false;
            }
            //CHECK IF THERE IS CONTRACT BETWEEN THEM?????
            return App.assurance_state_instance.methods.breakContract(patient_addr, dentist_addr).send({
                from: global_state.account,
                gas: 130000
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        },
        dentistApproveContract: function(patient_addr)  {
            //check if patient address is valid
            if(!innerAddressCheck(patient_addr)) {
                basic.showAlert('Patient address must be valid.');
                return false;
            }
            return App.assurance_state_instance.methods.dentistApproveContract(patient_addr).send({
                from: global_state.account,
                gas: 65000
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        },
        patientApproveContract: function(dentist_addr)  {
            return App.assurance_state_instance.methods.patientApproveContract(dentist_addr).send({
                from: global_state.account,
                gas: 65000
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        },
        registerContract: async function(patient_addr, dentist_addr, value_usd, value_dcn, date_start_contract, contract_ipfs_hash)  {
            var check_if_dentist_registered = await App.assurance_methods.getDentist(dentist_addr);
            //check if patient and dentist addresses are valid
            if(!innerAddressCheck(patient_addr) || !innerAddressCheck(dentist_addr)) {
                basic.showAlert('Patient and dentist addresses must be valid.');
                return false;
            }
            //check if dentist is registered on Assurance contract
            if(check_if_dentist_registered.toLowerCase() != dentist_addr.toLowerCase()) {
                basic.showAlert('You are not registered dentist on the Assurance contract. In order to init contracts you must first register your self.');
                return false;
            }
            //(talk with Jeremias about this check) check if patient gave allowance to Assurance contract to manage his Dentacoins
            if(parseInt(await App.dentacoin_token_methods.allowance(patient_addr, App.assurance_address)) <= 0) {
                basic.showAlert('This patient didn\'t give allowance to Assurance contract to manage his Dentacoins.');
                return false;
            }
            //check if USD and DCN values are valid
            if(parseInt(value_usd) <= 0 || parseInt(value_dcn) <= 0) {
                basic.showAlert('Both USD and DCN values must be greater than 0.');
                return false;
            }
            //check if valid timestamp
            if(date_start_contract < 0) {
                basic.showAlert('Please enter valid date.');
                return false;
            }
            return App.assurance_state_instance.methods.registerContract(patient_addr, dentist_addr, value_usd, value_dcn, date_start_contract, contract_ipfs_hash).send({
                from: global_state.account,
                gas: 330000
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        },
        registerDentist: function()  {
            return App.assurance_state_instance.methods.registerDentist().send({
                from: global_state.account,
                gas: 100000
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        },
        withdrawToDentist: async function()  {
            var ready_to_withdraw_arr = [];
            var current_patients_for_dentist = await App.assurance_methods.getPatientsArrForDentist(global_state.account);
            if(current_patients_for_dentist.length > 0) {
                for (var i = 0, len = current_patients_for_dentist.length; i < len; i += 1) {
                    var patient = await App.assurance_methods.getPatient(current_patients_for_dentist[i], global_state.account);
                    //if time passed for next_transfer of contract and if the contract is approved by both patient and dentist and then dentist can withdraw from patient legit
                    console.log(patient);
                    if(Math.round(new Date().getTime() / 1000) > parseInt(patient[2]) && patient[3] && patient[4]) {
                        ready_to_withdraw_arr.push(patient[1]);
                    }
                }
            }

            if(ready_to_withdraw_arr.length > 0) {
                return App.assurance_state_instance.methods.withdrawToDentist(ready_to_withdraw_arr).send({
                    from: global_state.account,
                    gas: ready_to_withdraw_arr.length * 60000
                }).on('transactionHash', function(hash){
                    basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
                }).catch(function(err) {
                    console.error(err);
                });
            }else {
                basic.showAlert('At this moment you don\'t have any possible withdraws (no running contracts or not ready to withdraw contracts).');
                return false;
            }
        }*/
    },
    events: {

    },
    helper: {
        addBlockTimestampToTransaction: function(transaction)    {
            return new Promise(function(resolve, reject) {
                App.web3_1_0.eth.getBlock(transaction.blockNumber, function(error, result) {
                    if (error !== null) {
                        reject(error);
                    }
                    temporally_timestamp = result.timestamp;
                    resolve(temporally_timestamp);
                });
            });
        },
        getLoopingTransactionFromBlockTimestamp: function(block_num)    {
            return new Promise(function(resolve, reject) {
                App.web3_1_0.eth.getBlock(block_num, function(error, result) {
                    if (error !== null) {
                        reject(error);
                    }
                    resolve(result.timestamp);
                });
            });
        },
        getBlockNum: function()  {
            return new Promise(function(resolve, reject) {
                App.web3_1_0.eth.getBlockNumber(function(error, result) {
                    if(!error){
                        global_state.curr_block = result;
                        resolve(global_state.curr_block);
                    }
                });
            });
        },
        getAccounts: function()  {
            return new Promise(function(resolve, reject) {
                App.web3_1_0.eth.getAccounts(function(error, result) {
                    if(!error){
                        resolve(result);
                    }
                });
            });
        },
        estimateGas: function(address, function_abi)  {
            return new Promise(function(resolve, reject) {
                App.web3_1_0.eth.estimateGas({
                    to: address,
                    data: function_abi
                }, function(error, result) {
                    if(!error){
                        resolve(result);
                    }
                });
            });
        },
        getGasPrice: function() {
            return new Promise(function(resolve, reject) {
                App.web3_1_0.eth.getGasPrice(function(error, result) {
                    if(!error){
                        resolve(result);
                    }
                });
            });
        },
        getAddressETHBalance: function(address)    {
            return new Promise(function(resolve, reject) {
                resolve(App.web3_1_0.eth.getBalance(address));
            });
        }
    }
};

async function pagesDataOnContractInit() {
    if($('body').hasClass('dentist')) {
        $('.additional-info .current-account a').html(global_state.account).attr('href', 'https://rinkeby.etherscan.io/address/' + global_state.account);
        $('.additional-info .assurance-account a').html(App.assurance_address).attr('href', 'https://rinkeby.etherscan.io/address/' + App.assurance_address);
        $('.additional-info .dentacointoken-account a').html(App.dentacoin_token_address).attr('href', 'https://rinkeby.etherscan.io/address/' + App.dentacoin_token_address);
        var check_dentist_account = await App.assurance_methods.getDentist(global_state.account);
        if(check_dentist_account.toLowerCase() == global_state.account.toLowerCase()) {
            $('.additional-info .is-dentist span').addClass('yes').html('YES');
        }else {
            $('.additional-info .is-dentist span').addClass('no').html('NO'); 
        }

        //show current pending and running contracts
        buildCurrentDentistContractHistory();

        $('.register-dentist').click(function() {
            App.assurance_methods.registerDentist();
        });

        $('.register-contract').click(function()    {
            App.assurance_methods.registerContract($('.registerContract .patient-address').val().trim(), global_state.account, $('.registerContract .value-usd').val().trim(), $('.registerContract .value-dcn').val().trim(), new Date($('.registerContract .date-start-contract').val().trim()).getTime() / 1000, $('.registerContract .ipfs-hash').val().trim());
        });

        $('.dentist-approve-contract').click(function() {
            App.assurance_methods.dentistApproveContract($('.dentistApproveContract .patient-address').val().trim());
        });

        $('.withdraw-to-dentist').click(function() {
            App.assurance_methods.withdrawToDentist();
        });

        $('.break-contract').click(function() {
            App.assurance_methods.breakContract($('.breakContract .patient-address').val().trim(), global_state.account);
        });
    }else if($('body').hasClass('patient')) {
        $('.additional-info .current-account a').html(global_state.account).attr('href', 'https://rinkeby.etherscan.io/address/' + global_state.account);
        $('.additional-info .assurance-account a').html(App.assurance_address).attr('href', 'https://rinkeby.etherscan.io/address/' + App.assurance_address);
        $('.additional-info .dentacointoken-account a').html(App.dentacoin_token_address).attr('href', 'https://rinkeby.etherscan.io/address/' + App.dentacoin_token_address);

        //we check greater than 0 or more?????? ASK JEREMIAS
        if(parseInt(await App.dentacoin_token_methods.allowance(global_state.account, App.assurance_address)) > 0) {
            $('.is-allowance-given span').addClass('yes').html('YES');
        }else {
            $('.is-allowance-given span').addClass('no').html('NO');
        }

        //show current pending and running contracts
        buildCurrentPatientContractHistory();

        $('.approve .approve-dcntoken-contract').click(function() {
            App.dentacoin_token_methods.approve();
        });

        $('.register-contract').click(function()    {
            App.assurance_methods.registerContract(global_state.account, $('.registerContract .dentist-address').val().trim(), $('.registerContract .value-usd').val().trim(), $('.registerContract .value-dcn').val().trim(), new Date($('.registerContract .date-start-contract').val().trim()).getTime() / 1000, $('.registerContract .ipfs-hash').val().trim());
        });

        $('.patient-approve-contract').click(function() {
            App.assurance_methods.patientApproveContract($('.patientApproveContract .dentist-address').val().trim());
        });

        $('.break-contract').click(function() {
            App.assurance_methods.breakContract(global_state.account, $('.breakContract .dentist-address').val().trim());
        });
    }
}

function initDateTimePicker() {
    if($(".form_datetime").length > 0) {
        $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
    }
}
initDateTimePicker();

//checking if passed address is valid
function innerAddressCheck(address)    {
    return App.web3_1_0.utils.isAddress(address);
}

async function buildCurrentDentistContractHistory() {
    var current_patients_for_dentist = await App.assurance_methods.getPatientsArrForDentist(global_state.account);
    if(current_patients_for_dentist.length > 0) {
        var pending_approval_from_this_dentist_bool = false;
        var pending_approval_from_patient = false;
        var running_contacts_bool = false;
        for(var i = 0, len = current_patients_for_dentist.length; i < len; i+=1) {
            var patient = await App.assurance_methods.getPatient(current_patients_for_dentist[i], global_state.account);
            var single_patient_body = '<div class="single"><div><label>Patient address:</label> <a href="https://rinkeby.etherscan.io/address/'+patient[1]+'" target="_blank" class="etherscan-hash">'+patient[1]+'</a></div><div><label>USD value:</label> '+patient[6]+'</div><div><label>DCN value:</label> '+patient[7]+'</div><div><label>IPFS link: (this is where patient and dentist can see the real contract (pdf) signed between them) <a href="https://gateway.ipfs.io/ipfs/'+patient[8]+'" target="_blank">https://gateway.ipfs.io/ipfs/'+patient[8]+'</a></label></div>';
            if(patient[3] == true && patient[4] == true) {
                if(!running_contacts_bool) {
                    $('.running-contacts .fieldset-body').html('');
                    running_contacts_bool = true;
                }
                single_patient_body+='<div><label>Date and time for next available withdraw:</label> '+new Date(parseInt(patient[2])*1000)+'</div></div>';
                $('.running-contacts .fieldset-body').append(single_patient_body);
            }else if(patient[3] == true) {
                if(!pending_approval_from_patient) {
                    $('.pending-approval-from-patient .fieldset-body').html('');
                    pending_approval_from_patient = true;
                }
                single_patient_body+='<div><label>Date and time contract start:</label> '+new Date(parseInt(patient[2])*1000)+'</div></div>';
                $('.pending-approval-from-patient .fieldset-body').append(single_patient_body);
            }else if(patient[4] == true) {
                if(!pending_approval_from_this_dentist_bool) {
                    $('.pending-approval-from-this-dentist .fieldset-body').html('');
                    pending_approval_from_this_dentist_bool = true;
                }
                single_patient_body+='<div><label>Date and time contract start:</label> '+new Date(parseInt(patient[2])*1000)+'</div></div>';
                $('.pending-approval-from-this-dentist .fieldset-body').append(single_patient_body);
            }
        }
    }
}

async function buildCurrentPatientContractHistory() {
    var current_dentists_for_patient = await App.assurance_methods.getWaitingContractsForPatient(global_state.account);
    if(current_dentists_for_patient.length > 0) {
        var pending_approval_from_this_dentist_bool = false;
        var pending_approval_from_patient = false;
        var running_contacts_bool = false;
        for(var i = 0, len = current_dentists_for_patient.length; i < len; i+=1) {
            var patient = await App.assurance_methods.getPatient(global_state.account, current_dentists_for_patient[i]);
            var single_patient_body = '<div class="single"><div><label>Dentist address:</label> <a href="https://rinkeby.etherscan.io/address/'+patient[0]+'" target="_blank" class="etherscan-hash">'+patient[0]+'</a></div><div><label>USD value:</label> '+patient[6]+'</div><div><label>DCN value:</label> '+patient[7]+'</div><div><label>IPFS link:  (this is where patient and dentist can see the real contract (pdf) signed between them) <a href="https://gateway.ipfs.io/ipfs/'+patient[8]+'" target="_blank">https://gateway.ipfs.io/ipfs/'+patient[8]+'</a></label></div>';
            if(patient[3] == true && patient[4] == true) {
                if(!running_contacts_bool) {
                    $('.running-contacts .fieldset-body').html('');
                    running_contacts_bool = true;
                }
                single_patient_body+='<div><label>Date and time for next available withdraw:</label> '+new Date(parseInt(patient[2])*1000)+'</div></div>';
                $('.running-contacts .fieldset-body').append(single_patient_body);
            }else if(patient[3] == true) {
                if(!pending_approval_from_patient) {
                    $('.pending-approval-from-this-patient .fieldset-body').html('');
                    pending_approval_from_patient = true;
                }
                single_patient_body+='<div><label>Date and time contract start:</label> '+new Date(parseInt(patient[2])*1000)+'</div></div>';
                $('.pending-approval-from-this-patient .fieldset-body').append(single_patient_body);
            }else if(patient[4] == true) {
                if(!pending_approval_from_this_dentist_bool) {
                    $('.pending-approval-from-dentist .fieldset-body').html('');
                    pending_approval_from_this_dentist_bool = true;
                }
                single_patient_body+='<div><label>Date and time contract start:</label> '+new Date(parseInt(patient[2])*1000)+'</div></div>';
                $('.pending-approval-from-dentist .fieldset-body').append(single_patient_body);
            }
        }
    }
}

// ================== PAGES ==================
if($('body').hasClass('home')) {
    if($('.testimonials-slider').length > 0) {
        $('.testimonials-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 8000,
            adaptiveHeight: true
        });
    }

    if($('.open-calculator').length > 0) {
        $('.open-calculator').click(function() {
            $.ajax({
                type: 'POST',
                url: '/get-calculator-html',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if(response.success) {
                        basic.closeDialog();
                        basic.showDialog(response.success, 'calculator-popup', null, true);
                        $('#number-of-patients').focus();

                        $('.selectpicker').selectpicker('refresh');
                        fixButtonsFocus();

                        calculateLogic();
                    }
                }
            });
        });
    }
}else if($('body').hasClass('patient-access')) {
    if($('.ask-your-dentist-for-assurance').length) {
        $('.ask-your-dentist-for-assurance').click(function() {
            $('html, body').animate({scrollTop: $('#find-your-dentist').offset().top}, 500);
            $('#find-your-dentist .search-dentist-input').focus();
            return false;
        });
    }

    //init select combobox with clinics
    initComboboxes();

    if($('section#find-your-dentist select.combobox').length) {
        $('section#find-your-dentist select.combobox').on('keydown', function (e) {
            if(e.which == 13) {
                basic.showAlert('Please login to continue. If you don\'t have registration please click <a href="javascript:void(0)" class="show-login-signin">here</a>.', '', true);
                bindLoginSigninPopupShow();
            }
        });

        //on change show login popup
        $('section#find-your-dentist input[type="text"].combobox').attr('placeholder', 'Search for a clinic...');

        //on enter press show login popup
        $('section#find-your-dentist select.combobox').on('change', function() {
            basic.closeDialog();
            basic.showAlert('Please login to continue. If you don\'t have registration please click <a href="javascript:void(0)" class="show-login-signin">here</a>.', '', true);
            bindLoginSigninPopupShow();
        });
    }

    if($('section.section-logged-patient-form select.combobox').length) {
        //on change show login popup
        $('section.section-logged-patient-form input[type="text"].combobox').attr('placeholder', 'Find your preferred dentist/s in a snap...');

        //on enter press show login popup
        $('section.section-logged-patient-form select.combobox').on('change', function() {
            console.log($(this).val());
        });
    }

    if($('.contracts-list.slider').length) {
        $('.contracts-list.slider').slick({
            slidesToShow: 3,
            slidesToScroll: 3,
            autoplaySpeed: 8000
        });
    }
}else if($('body').hasClass('support-guide')) {
    if($('.support-guide-slider').length) {
        $('.support-guide-slider').slick({
            slidesToShow: 3,
            slidesToScroll: 3
        });
    }

    if($('.section-support-guide-list .question').length > 0) {
        $('.section-support-guide-list .question').click(function()   {
            $(this).closest('li').find('.question-content').toggle(300);
        });
    }
}else if($('body').hasClass('wallet-instructions')) {
    if($('.section-wallet-instructions-questions .question').length > 0) {
        $('.section-wallet-instructions-questions .question').click(function()   {
            $(this).toggleClass('active');
            $(this).closest('li').find('.question-content').toggle(300);
        });
    }
}else if($('body').hasClass('forgotten-password')) {
    $('form#forgotten-password').on('submit', function(event) {
        var this_form = $(this);
        if(this_form.find('input[type="email"]').val().trim() == '' || !basic.validateEmail(this_form.find('input[type="email"]').val().trim())) {
            basic.showAlert('Please try again with valid email.', '', true);
            event.preventDefault();
        }
    });
}

//LOGGED USER LOGIC
if($('body').hasClass('logged-in')) {
    if($('body').hasClass('edit-account')) {
        styleAvatarUploadButton('form#patient-update-profile .avatar button label');

        $('form#patient-update-profile').on('submit', function(event) {
            var this_form = $(this);
            var errors = false;
            //clear prev errors
            if(this_form.find('.error-handle').length) {
                this_form.find('.error-handle').remove();
            }

            var form_fields = this_form.find('.custom-input');
            for(var i = 0, len = form_fields.length; i < len; i+=1) {
                if(form_fields.eq(i).attr('type') == 'email' && !basic.validateEmail(form_fields.eq(i).val().trim())) {
                    customErrorHandle(form_fields.eq(i).parent(), 'Please use valid email address.');
                    errors = true;
                }

                if(form_fields.eq(i).val().trim() == '') {
                    customErrorHandle(form_fields.eq(i).parent(), 'This field is required.');
                    errors = true;
                }
            }

            if(errors) {
                event.preventDefault();
            }
        });
    } else if($('body').hasClass('manage-privacy')) {
        if(localStorage.getItem('current-account') != null)   {
            var title;
            var text;
            var btn_label;
            var alert_response;

            if(JSON.parse(localStorage.getItem('current-account')).type == 'key') {
                title = 'Delete my private key';
                text = 'Your private key is locally stored in your browser for easier and faster transactions. If you are sure about that, just click the button below.';
                btn_label = 'DELETE MY PRIVATE KEY';
                alert_response = 'Your private key has been deleted from your browser successfully.';
            } else if(JSON.parse(localStorage.getItem('current-account')).type == 'keystore') {
                title = 'Delete my keystore file';
                text = 'Your private key is locally stored in your browser for easier and faster transactions. If you are sure about that, just click the button below.';
                btn_label = 'DELETE MY KEYSTORE FILE';
                alert_response = 'Your keystore file has been deleted from your browser successfully.';
            }

            $('.delete-local-storage').html('<div class="padding-bottom-50 padding-top-60 delete-local-storage-border-bottom"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block-top"><img alt="Cancel icon" src="/assets/uploads/cancel.svg"/></figure><div class="text inline-block-top"><h3 class="fs-20 padding-bottom-20 lato-bold dark-color">'+title+'</h3><div class="fs-16 dark-color">'+text+'</div></div><div class="btn-container text-right padding-top-30"><a href="javascript:void(0);" class="white-blue-green-btn clear-current-account-local-storage" onclick="return confirm(\'Are you sure you want to continue?\')">'+btn_label+'</a></div></div>');

            $('.clear-current-account-local-storage').click(function() {
                localStorage.removeItem('current-account');
                $('.delete-local-storage').html('');
                basic.showAlert(alert_response, '', true);
            });


        }
    } else if($('body').hasClass('my-profile')) {
        $('.my-profile-page-content .dropdown-hidden-menu button').click(function() {
            var this_btn = $(this);
            $('.my-profile-page-content .current-converted-price .amount').html((parseFloat($('.current-dcn-amount').html()) * parseFloat(this_btn.attr('data-multiple-with'))).toFixed(6));
            $('.my-profile-page-content .current-converted-price .symbol span').html(this_btn.html());
        });

        if($('form#add-dcn-address').length) {
            $('form#add-dcn-address').on('submit', function(event) {
                var this_form = $(this);
                this_form.find('.error-handle').remove();
                if(this_form.find('.address').val().trim() == '') {
                    customErrorHandle(this_form.find('.address').parent(), 'Please enter your wallet address.');
                    event.preventDefault();
                } else if(!innerAddressCheck(this_form.find('.address').val().trim())) {
                    customErrorHandle(this_form.find('.address').parent(), 'Please enter valid wallet address.');
                    event.preventDefault();
                }
            });
        }

        //if no key or keystore file is cached show the option for it
        if(localStorage.getItem('current-account') == null) {
            $.ajax({
                type: 'POST',
                url: '/get-address-validation-or-remember-me',
                dataType: 'json',
                data: {
                    cache: true
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        $('.remember-my-wallet-camp').html('<h3 class="line-crossed-title margin-bottom-50 fs-20 lato-bold black-color"><span>Remember my wallet</span></h3>' + response.success + '<div class="padding-bottom-50"></div>');

                        styleUploadFileButton();

                        $('.enter-private-key').unbind().click(function() {
                            $('.proof-of-address .on-change-result').html('<div class="col-xs-12 col-sm-5 padding-left-30 padding-top-20"><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-private-key">Your Private Key:</label><input type="text" id="your-private-key" maxlength="64" class="full-rounded"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn cache-key-btn">REMEMBER</a></div></div>');
                            $('.proof-of-address #upload-keystore-file').val('');
                            bindGoogleAlikeButtonsEvents();
                            bindCacheKeyEvent();
                        });

                        $('.upload-file-container button').unbind().click(function() {
                            $('.proof-of-address .on-change-result').html('');
                        });
                    }
                }
            });
        }
    } else if($('body').hasClass('create-contract')) {
        var signature_pad_inited = false;
        styleAvatarUploadButton('.steps-body .avatar button label');

        if($('.single-row.proof-of-address').length) {
            bindVerifyAddressLogic();
        }

        //showing the list for each service category
        $('.show-category-list a').click(function() {
            $(this).slideUp(300);
            $(this).closest('.show-category-list').find('ul').slideDown(300);
        });

        var form_props_arr = ['professional-company-number', 'postal-address', 'country', 'phone', 'website', 'address', 'fname', 'lname', 'email', 'monthly-premium', 'check-ups-per-year', 'teeth-cleaning-per-year'];
        var create_contract_form = $('form#dentist-create-contract');
        create_contract_form.find('.terms-and-conditions-long-list').mCustomScrollbar();

        //validation for all fields for each step
        function validateStepFields(step_fields, step) {
            step_fields.removeClass('with-error');
            $('.step.'+step+' .single-row').removeClass('row-with-error');
            $('.step.'+step+' .single-row > label span').remove();

            if(step == 'three' && $('.step.three [name="general-dentistry[]"]:checked').val() != undefined) {
                $('.step.three .checkboxes-right-container').removeClass('with-error');
            }

            var inner_error = false;
            for(var i = 0, len = step_fields.length; i < len; i+=1) {
                if(step_fields.eq(i).val().trim() == '') {
                    customCreateContractErrorHandle(step_fields.eq(i), 'Required field cannot be left blank.');
                    inner_error = true;
                } else if(step_fields.eq(i).attr('data-type') == 'email' && !basic.validateEmail(step_fields.eq(i).val().trim())) {
                    customCreateContractErrorHandle(step_fields.eq(i), 'Please enter valid email.');
                    inner_error = true;
                } else if(step_fields.eq(i).attr('data-type') == 'address' && !innerAddressCheck(step_fields.eq(i).val().trim())) {
                    customCreateContractErrorHandle(step_fields.eq(i), 'Please enter valid wallet address.');
                    inner_error = true;
                } else if(step_fields.eq(i).attr('data-type') == 'website' && !basic.validateUrl(step_fields.eq(i).val().trim())) {
                    customCreateContractErrorHandle(step_fields.eq(i), 'Please enter valid website.');
                    inner_error = true;
                }else if(step_fields.eq(i).attr('data-type') == 'phone' && !basic.validatePhone(step_fields.eq(i).val().trim())) {
                    customCreateContractErrorHandle(step_fields.eq(i), 'Please enter valid phone.');
                    inner_error = true;
                }
            }

            if(inner_error) {
                $('html, body').animate({scrollTop: create_contract_form.offset().top}, 500);
            }

            return inner_error;
        }

        //bind event for step buttons above the contract, adding true/false attribute
        $('.contract-creation-steps-container button').bind('click.validateStepsNav', async function() {
            var current_step_error = false;
            var this_btn = $(this);
            var this_btn_step = this_btn.attr('data-step');
            //if steping into one of the next buttons, NOT PREVIOUS
            if(this_btn.index() > $('.contract-creation-steps-container button[data-step="'+create_contract_form.find('.next').attr('data-current-step')+'"]').index()) {
                var validate_steps_arr;
                if(this_btn_step == 'two') {
                    var validate_dentist_address = false;
                    var dentist_address;
                    if($('.step.one #dcn_address').is('input')) {
                        dentist_address = $('.step.one #dcn_address').val().trim();
                    } else {
                        dentist_address = $('.step.one #dcn_address').html().trim();
                    }

                    if(innerAddressCheck(dentist_address)) {
                        //method for first step validating the dentist address
                        validate_dentist_address = await validateUserAddress(dentist_address, $('.step.one #dcn_address'));
                    }

                    if(validate_dentist_address) {
                        current_step_error = true;
                    }

                    validate_steps_arr = ['one'];
                } else if(this_btn_step == 'three') {
                    validate_steps_arr = ['one', 'two'];
                } else if(this_btn_step == 'four') {
                    if($('.step.three [name="general-dentistry[]"]:checked').val() == undefined) {
                        $('.step.three .checkboxes-right-container').prev().find('span').remove();
                        customCreateContractErrorHandle($('.step.three .checkboxes-right-container'), 'Please select at least one service.');
                        current_step_error = true;
                    }
                    validate_steps_arr = ['one', 'two', 'three'];
                }

                //if validate_steps_arr is defined and if no errors until now
                if(validate_steps_arr.length && !current_step_error) {
                    for(var y = 0, len = validate_steps_arr.length; y < len; y+=1) {
                        current_step_error = validateStepFields($('.step.'+validate_steps_arr[y]+' input.right-field'), validate_steps_arr[y]);
                    }
                } else if(current_step_error) {
                    $('html, body').animate({scrollTop: create_contract_form.offset().top}, 500);
                }

                if(!current_step_error) {
                    //update the html of the NEXT button
                    if(this_btn_step == 'one' || this_btn_step == 'two') {
                        create_contract_form.find('.next').html('NEXT');
                    } else if(this_btn_step == 'three') {
                        create_contract_form.find('.next').html('GENERATE SAMPLE CONTRACT');
                    } else if(this_btn_step == 'four') {
                        create_contract_form.find('.next').html('SIGN CONTRACT');
                    }

                    switch(create_contract_form.find('.next').attr('data-current-step')) {
                        case 'one':
                            firstStepPassedSuccessfully(create_contract_form.find('.next'), this_btn_step);
                            break;
                        case 'two':
                            secondStepPassedSuccessfully(create_contract_form.find('.next'), this_btn_step);
                            break;
                        case 'three':
                            thirdStepPassedSuccessfully(create_contract_form.find('.next'), this_btn_step);
                            break;
                    }
                }
            } else {
                //going backwards, no validation is needed here
                showResponseLayer(500);

                //update the active class
                $('.contract-creation-steps-container button').removeClass('active');
                this_btn.addClass('active');

                //hide current step and show the current one
                $('.step.'+create_contract_form.find('.next').attr('data-current-step')).hide();
                $('.step.'+this_btn_step).show();

                //updaing the NEXT button attr
                create_contract_form.find('.next').attr('data-current-step', this_btn_step);
                $('html, body').animate({scrollTop: $('.contract-creation-steps-container').offset().top}, 500);

                //update the html of the NEXT button
                if(this_btn_step == 'one' || this_btn_step == 'two') {
                    create_contract_form.find('.next').html('NEXT');
                } else if(this_btn_step == 'three') {
                    create_contract_form.find('.next').html('GENERATE SAMPLE CONTRACT');
                } else if(this_btn_step == 'four') {
                    create_contract_form.find('.next').html('SIGN CONTRACT');
                }
            }
        });

        //method
        function onStepValidationSuccess(current_step, next_step, button) {
            if(next_step == 'four') {
                showContractResponseLayer(3000);
            }else {
                showResponseLayer(500);
            }

            $('.steps-body .step').hide();
            $('.step.'+next_step).show();
            button.attr('data-current-step', next_step);
            window.scrollTo(0, $('.contract-creation-steps-container').offset().top);

            if(next_step == 'four') {
                fourthStepValidation(button);
            }

            $('.contract-creation-steps-container button[data-step="'+next_step+'"]').removeClass('not-allowed-cursor').addClass('active');
            $('.contract-creation-steps-container button[data-step="'+current_step+'"]').removeClass('active not-passed').addClass('passed');
        }

        function firstStepPassedSuccessfully(button, next_step) {
            onStepValidationSuccess('one', next_step, button);
        }

        function secondStepPassedSuccessfully(button, next_step) {
            onStepValidationSuccess('two', next_step, button);
        }

        function thirdStepPassedSuccessfully(button, next_step) {
            onStepValidationSuccess('three', next_step, button);

            //update the fields on the sample contract
            for(var i = 0, len = form_props_arr.length; i < len; i+=1) {
                if(create_contract_form.find('[name="'+form_props_arr[i]+'"]').is('input')) {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('input[name="'+form_props_arr[i]+'"]').val().trim());
                } else if(create_contract_form.find('[name="'+form_props_arr[i]+'"]').is('select')) {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('select[name="'+form_props_arr[i]+'"]').val().trim());
                } else {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('[name="'+form_props_arr[i]+'"]').html().trim());
                }
            }

            //clear step three services error
            $('.step.three .checkboxes-right-container').removeClass('with-error');

            $('.step.four .checkboxes-right-container input[type="checkbox"]').prop('checked', false);
            //update the disabled checkboxes on the sample contract
            for(var i = 0, len = $('.step.three [name="general-dentistry[]"]:checked').length; i < len; i+=1) {
                $('.step.four input[type="checkbox"]#'+$('[name="general-dentistry[]"]:checked').eq(i).val()).prop('checked', true);
            }

            //init the signature logic
            if(!signature_pad_inited) {
                initSignaturePad();
                signature_pad_inited = true;
            }
        }

        //method for final step validation
        function fourthStepValidation(button) {
            for(var i = 0, len = form_props_arr.length; i < len; i+=1) {
                if(create_contract_form.find('[name="'+form_props_arr[i]+'"]').is('input')) {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('input[name="'+form_props_arr[i]+'"]').val().trim());
                } else if(create_contract_form.find('[name="'+form_props_arr[i]+'"]').is('select')) {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('select[name="'+form_props_arr[i]+'"]').val().trim());
                } else {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('[name="'+form_props_arr[i]+'"]').html().trim());
                }
            }

            $('.step.four .checkboxes-right-container input[type="checkbox"]').prop('checked', false);
            //update the disabled checkboxes on the sample contract
            for(var i = 0, len = $('.step.three [name="general-dentistry[]"]:checked').length; i < len; i+=1) {
                $('.step.four input[type="checkbox"]#'+$('[name="general-dentistry[]"]:checked').eq(i).val()).prop('checked', true);
            }

            create_contract_form.unbind().on('submit', function(event) {
                var this_form = this;
                var form_errors = false;
                if(signature_pad.isEmpty()) {
                    basic.showAlert('Please sign the contract sample. Use your mouse or touch screen to sign.', '', true);
                    event.preventDefault();
                    form_errors = true;
                }else if(!$('.step.four input#terms').is(':checked')) {
                    basic.showAlert('Please accept the Terms and Conditions', '', true);
                    event.preventDefault();
                    form_errors = true;
                }else if(!$('.step.four input#privacy-policy').is(':checked')) {
                    basic.showAlert('Please accept the Privacy Policy', '', true);
                    event.preventDefault();
                    form_errors = true;
                }

                if(!form_errors) {
                    //save the base64 signature image in hidden value
                    $(this_form).find('input[name="dentist_signature"]').val(signature_pad.toDataURL('image/png'));

                    //delay the form submission so we can init loader animation
                    event.preventDefault();
                    $('.contract-response-success-layer').show();
                    setTimeout(function() {
                        this_form.submit();
                    }, 2000);
                }
            });
        }

        //logic for showing the suggested price based on country and calculator parameters
        $('.step.three [name="general-dentistry[]"]').on('change', function() {
            var suggested_price;
            var checked_services = $('.step.three [name="general-dentistry[]"]:checked');
            if(checked_services.length) {
                $('.show-on-services-pick').fadeIn(1000);

                var checked_services_arr = [];
                for(var i = 0, len = checked_services.length; i < len; i+=1) {
                    checked_services_arr.push(checked_services.eq(i).val());
                }

                if($.inArray('param_gd', checked_services_arr) != -1 && $.inArray('param_cd', checked_services_arr) != -1 && $.inArray('param_id', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-gd-cd-id');
                } else if($.inArray('param_gd', checked_services_arr) != -1 && $.inArray('param_cd', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-gd-cd');
                } else if($.inArray('param_gd', checked_services_arr) != -1 && $.inArray('param_id', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-gd-id');
                } else if($.inArray('param_cd', checked_services_arr) != -1 && $.inArray('param_id', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-cd-id');
                } else if($.inArray('param_gd', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-gd');
                } else if($.inArray('param_cd', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-cd');
                } else if($.inArray('param_id', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-id');
                }

                create_contract_form.find('.suggested-price').html(suggested_price);
            } else {
                $('.show-on-services-pick').fadeOut(500);
            }
        });

        //on button NEXT click which is below the contract, it's playing with the steps navigation above the contract
        create_contract_form.find('.next').click(async function() {
            var this_btn = $(this);
            switch($('.contract-creation-steps-container button.active').attr('data-step')) {
                case 'one':
                    $('.contract-creation-steps-container button[data-step="two"]').click();
                    break;
                case 'two':
                    $('.contract-creation-steps-container button[data-step="three"]').click();
                    break;
                case 'three':
                    $('.contract-creation-steps-container button[data-step="four"]').click();
                    break;
                case 'four':
                    create_contract_form.submit();
                    break;
            }
        });
    }else if($('body').hasClass('contract-proposal')) {
        if($('.terms-and-conditions-long-list').length) {
            $('.terms-and-conditions-long-list').mCustomScrollbar();
        }

        if($('.single-row.proof-of-address').length) {
            bindVerifyAddressLogic();
        }

        initSignaturePad();

        if($('.contract-proposal.section .contact-your-dentist').length) {
            $('.contact-your-dentist').click(function() {
                var this_btn = $(this);
                $.ajax({
                    type: 'POST',
                    url: '/patient/get-reconsider-monthly-premium',
                    dataType: 'json',
                    data: {
                        contract: this_btn.closest('form').find('input[type="hidden"][name="contract"]').val().trim()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            basic.showDialog(response.success, 'reconsider-monthly-premium', true);
                            fixButtonsFocus();

                            $('.bootbox.reconsider-monthly-premium #new-usd-proposal-to-dentist').focus();

                            $('.bootbox.reconsider-monthly-premium form#submit-reconsider-monthly-premium').on('submit', function(event) {
                                var this_form = $(this);
                                if(this_form.find('#new-usd-proposal-to-dentist').val().trim() == '' || parseFloat(this_form.find('#new-usd-proposal-to-dentist').val().trim()) <= 0) {
                                    basic.showAlert('Please enter valid monthly premium proposal', '', true);
                                    event.preventDefault();
                                } else {
                                    $('.response-layer').show();
                                }
                            });
                        } else if (response.error) {
                            basic.showAlert(response.success, '', true);
                        }
                    }
                });
            });
        }

        if($('form#dentist-update-and-sign-contract').length) {
            cancelContractEventInit();

            $('form#dentist-update-and-sign-contract').on('submit', async function(event) {
                event.preventDefault();
                var this_form_plain = this;
                var this_form = $(this);
                var fields = this_form.find('.right-field.required-field');
                var form_errors = false;

                //clear previous submits errors
                this_form.find('.error-in-label').remove();
                this_form.find('.single-row').removeClass('row-with-error');
                fields.removeClass('with-error');

                //checking the validation for the patient fields
                for(var i = 0, len = fields.length; i < len; i+=1) {
                    if(fields.eq(i).is('select')) {
                        if(fields.eq(i).val() == null) {
                            customCreateContractErrorHandle(fields.eq(i), 'Required field cannot be left blank.');
                            form_errors = true;
                        }
                    } else if(fields.eq(i).is('input')) {
                        if (fields.eq(i).val().trim() == '') {
                            customCreateContractErrorHandle(fields.eq(i), 'Required field cannot be left blank.');
                            form_errors = true;
                        } else if(fields.eq(i).is('[name="dcn_address"]') && !innerAddressCheck(fields.eq(i).val().trim())) {
                            customCreateContractErrorHandle(fields.eq(i), 'Please enter valid Wallet Address.');
                            if($('.proof-of-address').length) {
                                $('.proof-of-address').remove();
                            }
                            form_errors = true;
                        }
                    }
                }

                if(!form_errors) {
                    var validate_patient_address = false;
                    var patient_address;
                    if($('.dcn-address-row #dcn_address').is('input')) {
                        patient_address = $('.dcn-address-row #dcn_address').val().trim();
                    } else {
                        patient_address = $('.dcn-address-row #dcn_address').html().trim();
                    }

                    if(innerAddressCheck(patient_address)) {
                        //method for first step validating the patient address
                        validate_patient_address = await validateUserAddress(patient_address, $('.dcn-address-row #dcn_address'));
                    }

                    if(validate_patient_address) {
                        form_errors = true;
                    }
                }

                if(form_errors) {
                    if($('.proof-of-address').length) {
                        $('html, body').animate({scrollTop: $('.proof-of-address').offset().top - 50}, 500);
                    } else {
                        $('html, body').animate({scrollTop: $('.right-field.required-field.with-error').offset().top - 50}, 500);
                    }
                } else {
                    //check if patient signed if privacy policy and terms checkboxes are checked
                    //save the base64 signature image in hidden value
                    this_form.find('input[name="patient_signature"]').val(signature_pad.toDataURL('image/png'));
                    if(signature_pad.isEmpty()) {
                        basic.showAlert('Please sign the contract sample. Use your mouse or touch screen to sign.', '', true);
                    }else if(!this_form.find('input#terms').is(':checked')) {
                        basic.showAlert('Please accept the Terms and Conditions', '', true);
                    }else if(!this_form.find('input#privacy-policy').is(':checked')) {
                        basic.showAlert('Please accept the Privacy Policy', '', true);
                    }else {
                        this_form_plain.submit();
                    }
                }
            });
        }
    }
}

//THIS IS FUNCTIONALITY ONLY FOR LOGGED IN USERS
if($('body').hasClass('logged-in')) {
    $('.logged-user > a, .logged-user .hidden-box').hover(function(){
        $('.logged-user .hidden-box').show();
    }, function(){
        $('.logged-user .hidden-box').hide();
    });

    if($('.contract-decrypt').length) {
        $('.contract-decrypt').click(async function() {
            var this_btn = $(this);
            var encrypted_pdf_content = await getEncryptedContractPdfContent(this_btn.attr('data-hash'), this_btn.attr('data-type'));
            var render_form = $('form#render-pdf');
            if(encrypted_pdf_content.success) {
                if(localStorage.getItem('current-account') != null) {
                    var cached_key = JSON.parse(localStorage.getItem('current-account'));
                    if(cached_key.type == 'key') {
                        var decrypted_pdf_response = await getDecryptedPdfContent(encrypted_pdf_content.success, cached_key.key);
                        if(decrypted_pdf_response.success) {
                            render_form.find('input[name="pdf_data"]').val(encodeEntities(decrypted_pdf_response.success.decrypted));
                            render_form.submit();
                        } else if(decrypted_pdf_response.error) {
                            basic.showAlert(decrypted_pdf_response.error, '', true);
                        }
                    } else if(cached_key.type == 'keystore') {
                        $.ajax({
                            type: 'POST',
                            url: '/get-keystore-file-password-validation',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                basic.closeDialog();
                                basic.showDialog(response.success, 'keystore-file-password-validation', null, true);
                                fixButtonsFocus();
                                $('.keystore-file-password-validation .keystore-password').focus();

                                $('.keystore-file-password-validation .btn-container a').click(function() {
                                    if($('.keystore-file-password-validation .keystore-password').val().trim() == '') {
                                        basic.showAlert('Please enter your password.', '', true);
                                    }else {
                                        $.ajax({
                                            type: 'POST',
                                            url: '/decrypt-pk',
                                            data: {
                                                password: $('.keystore-file-password-validation .keystore-password').val().trim(),
                                                keystore: JSON.stringify(JSON.parse(localStorage.getItem('current-account')).keystore)
                                            },
                                            dataType: 'json',
                                            success: async function (inner_response) {
                                                if(inner_response.success && inner_response.to_string)    {
                                                    var decrypted_pdf_response = await getDecryptedPdfContentByPlainKey(encrypted_pdf_content.success, inner_response.to_string);
                                                    if(decrypted_pdf_response.success) {
                                                        basic.closeDialog();
                                                        render_form.find('input[name="pdf_data"]').val(encodeEntities(decrypted_pdf_response.success.decrypted));
                                                        render_form.submit();
                                                    } else if(decrypted_pdf_response.error) {
                                                        basic.showAlert(decrypted_pdf_response.error, '', true);
                                                    }
                                                }else if(inner_response.error)    {
                                                    basic.showAlert(inner_response.error, '', true);
                                                }
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }
                } else {
                    basic.closeDialog();
                    openCacheKeyPopup();
                }
            } else if(encrypted_pdf_content.error) {
                basic.showAlert(encrypted_pdf_content.error, '', true);
            }
        });
    }

    if($('section.open-new-assurance-contact-section input[type="text"].combobox').length) {
        $('section.open-new-assurance-contact-section input[type="text"].combobox').attr('placeholder', 'Search for a clinic...');
    }

    if($('section.ready-to-purchase-with-external-api').length) {
        //currency conversion logic
        var current_active_currency = 'dcn';
        var dcn_for_one_usd = parseFloat($('section.ready-to-purchase-with-external-api').attr('data-dcn-for-one-usd'));
        var eth_for_one_usd = parseFloat($('section.ready-to-purchase-with-external-api').attr('data-eth-for-one-usd'));
        $('section.ready-to-purchase-with-external-api #crypto-amount').val(dcn_for_one_usd * parseFloat($('section.ready-to-purchase-with-external-api #usd-value').val().trim()));

        $('section.ready-to-purchase-with-external-api #usd-value').on('input', function() {
            if($(this).val().trim() < 30)   {
                $(this).parent().addClass('error-field');
            }else {
                $(this).parent().removeClass('error-field');
            }

            if(parseFloat($(this).val().trim()) < 0)    {
                $(this).val(30);
            }else if(parseFloat($(this).val().trim()) > 6000)    {
                $(this).val(6000);
            }

            if($('section.ready-to-purchase-with-external-api nav ul li a.active').attr('data-currency') == 'dcn') {
                $('section.ready-to-purchase-with-external-api #crypto-amount').val(dcn_for_one_usd * parseFloat($(this).val().trim()));
            } else if($('section.ready-to-purchase-with-external-api nav ul li a.active').attr('data-currency') == 'eth') {
                $('section.ready-to-purchase-with-external-api #crypto-amount').val(eth_for_one_usd * parseFloat($(this).val().trim()));
            }
        });

        $('section.ready-to-purchase-with-external-api nav ul li a').on('click', function() {
            $('section.ready-to-purchase-with-external-api nav ul li a').removeClass('active');
            $(this).addClass('active');
            if(current_active_currency != $(this).attr('data-currency')) {
                current_active_currency = $(this).attr('data-currency');

                $('section.ready-to-purchase-with-external-api #usd-value').val(30);
                $('section.ready-to-purchase-with-external-api #usd-value').parent().removeClass('error-field');

                $('section.ready-to-purchase-with-external-api .crypto-label').html(current_active_currency.toUpperCase());

                if(current_active_currency == 'dcn') {
                    $('section.ready-to-purchase-with-external-api #crypto-amount').val(dcn_for_one_usd * 30);
                } else if(current_active_currency == 'eth') {
                    $('section.ready-to-purchase-with-external-api #crypto-amount').val(eth_for_one_usd * 30);
                }
            }
        });

        $('section.ready-to-purchase-with-external-api #crypto-amount').on('input', function() {
            var divisor;
            if($('section.ready-to-purchase-with-external-api nav ul li a.active').attr('data-currency') == 'dcn') {
                divisor = dcn_for_one_usd;
            } else if($('section.ready-to-purchase-with-external-api nav ul li a.active').attr('data-currency') == 'eth') {
                divisor = eth_for_one_usd;
            }

            if(parseFloat($(this).val().trim()) / divisor > 6000)   {
                $(this).val(divisor * 6000);
            }
            $('section.ready-to-purchase-with-external-api #usd-value').val(parseFloat($(this).val().trim()) / divisor);
        });

        $('section.ready-to-purchase-with-external-api .buy-crypto-btn').click(function() {
            var currency = $('section.ready-to-purchase-with-external-api nav ul li a.active').attr('data-currency');
            var currency_amount_for_one_usd;
            if(currency == 'dcn') {
                currency_amount_for_one_usd = dcn_for_one_usd;
            } else if(currency == 'eth') {
                currency_amount_for_one_usd = eth_for_one_usd;
            }

            if(parseFloat($('section.ready-to-purchase-with-external-api #usd-value').val().trim()) < 30)  {
                basic.showAlert('The minimum transaction limit is 30 USD.', '', true);
            }else if(parseFloat($('section.ready-to-purchase-with-external-api #usd-value').val().trim()) > 6000)  {
                basic.showAlert('The maximum transaction limit is 6000 USD.', '', true);
            }else if(parseFloat($('section.ready-to-purchase-with-external-api #crypto-amount').val().trim()) < currency_amount_for_one_usd * 30)  {
                basic.showAlert('The minimum transaction limit is 30 USD in '+currency.toUpperCase()+'.', '', true);
            }else if(parseFloat($('section.ready-to-purchase-with-external-api #crypto-amount').val().trim()) > currency_amount_for_one_usd * 6000)  {
                basic.showAlert('The maximum transaction limit is 6000 USD in '+currency.toUpperCase()+'.', '', true);
            }else if(!innerAddressCheck($('section.ready-to-purchase-with-external-api input#dcn_address').val().trim())) {
                basic.showAlert('Please enter a valid wallet address. It should start with "0x" and be followed by 40 characters (numbers and letters).', '', true);
            }else if(!basic.validateEmail($('section.ready-to-purchase-with-external-api input#email').val().trim()))  {
                basic.showAlert('Please enter a valid email.', '', true);
            }else if(!$('section.ready-to-purchase-with-external-api #privacy-policy-agree').is(':checked')) {
                basic.showAlert('Please agree with our Privacy Policy.', '', true);
            }else {
                window.location = 'https://indacoin.com/gw/payment_form?partner=dentacoin&cur_from=USD&cur_to='+currency.toUpperCase()+'&amount='+$('section.ready-to-purchase-with-external-api #usd-value').val().trim()+'&address='+$('section.ready-to-purchase-with-external-api input#dcn_address').val().trim()+'&user_id='+$('section.ready-to-purchase-with-external-api input#email').val().trim();
            }
        });

        bindGoogleAlikeButtonsEvents();
    }
}

function calculateLogic() {
    $('.calculate').click(function() {
        var patients_number = $('#number-of-patients').val();
        var params_type;
        if($('#general-dentistry').is(':checked') && $('#cosmetic-dentistry').is(':checked') && $('#implant-dentistry').is(':checked')) {
            params_type = 'param_gd_cd_id';
        } else if($('#general-dentistry').is(':checked') && $('#cosmetic-dentistry').is(':checked')) {
            params_type = 'param_gd_cd';
        } else if($('#general-dentistry').is(':checked') && $('#implant-dentistry').is(':checked')) {
            params_type = 'param_gd_id';
        } else if($('#cosmetic-dentistry').is(':checked') && $('#implant-dentistry').is(':checked')) {
            params_type = 'param_cd_id';
        } else if($('#general-dentistry').is(':checked')) {
            params_type = 'param_gd';
        } else if($('#cosmetic-dentistry').is(':checked')) {
            params_type = 'param_cd';
        } else if($('#implant-dentistry').is(':checked')) {
            params_type = 'param_id';
        }

        var country = $('#country').val();
        var currency = $('#currency').val();

        if(patients_number == '' || parseInt(patients_number) <= 0) {
            basic.showAlert('Please enter valid number of patients per day.', '', true);
            return false;
        } else if(params_type == undefined) {
            basic.showAlert('Please select specialties.', '', true);
            return false;
        } else if(country == undefined) {
            basic.showAlert('Please select country.', '', true);
            return false;
        } else if(currency == undefined) {
            basic.showAlert('Please select currency.', '', true);
            return false;
        }
        var calculator_data = {
            'patients_number' : patients_number.trim(),
            'params_type' : params_type,
            'country' : country.trim(),
            'currency' : currency.trim()
        };

        $('.response-layer').show();
        setTimeout(function() {
            $.ajax({
                type: 'POST',
                url: '/get-calculator-result',
                dataType: 'json',
                data: calculator_data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    basic.closeDialog();
                    basic.showDialog(response.success, 'calculator-result-popup', null, true);
                    $('.response-layer').hide();

                    var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',');
                    $('.calculator-result-popup .price-container .result .amount').animateNumber({
                        number: parseFloat($('.calculator-result-popup .price-container .result .amount').attr('data-result')),
                        numberStep: comma_separator_number_step
                    }, 1000);

                    fixButtonsFocus();

                    $('.calculate-again').click(function () {
                        $.ajax({
                            type: 'POST',
                            url: '/get-calculator-html',
                            dataType: 'json',
                            data: calculator_data,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.success) {
                                    basic.closeDialog();
                                    basic.showDialog(response.success, 'calculator-popup', null, true);

                                    $('.selectpicker').selectpicker('refresh');
                                    fixButtonsFocus();

                                    calculateLogic();
                                }
                            }
                        });
                    });
                }
            });
        }, 1000);
    });
}

function customJavascriptForm(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

function encodeEntities(string) {
    var p = document.createElement('p');
    p.textContent = string;
    var inner_html = p.innerHTML;
    p.remove();
    return inner_html;
}

//call the popup for login/sign for patient and dentist
function bindLoginSigninPopupShow() {
    if($('.show-login-signin').length) {
        $('.show-login-signin').unbind();
        $('.show-login-signin').on('click', function() {
            $.ajax({
                type: 'POST',
                url: '/get-login-signin',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: async function (response) {
                    if(response.success) {
                        basic.closeDialog();
                        basic.showDialog(response.success, 'login-signin-popup', null, true);

                        fixButtonsFocus();

                        $('.popup-header-action a').click(function() {
                            $('.login-signin-popup .popup-body > .inline-block').addClass('custom-hide');
                            $('.login-signin-popup .popup-body .'+$(this).attr('data-type')).removeClass('custom-hide');
                        });

                        $('.login-signin-popup .call-sign-up').click(function() {
                            $('.login-signin-popup .form-login').hide();
                            $('.login-signin-popup .form-register').show();
                        });

                        $('.login-signin-popup .call-log-in').click(function() {
                            $('.login-signin-popup .form-login').show();
                            $('.login-signin-popup .form-register').hide();
                        });

                        // ====================== PATIENT LOGIN/SIGNUP LOGIC ======================

                        //login
                        $('.patient .form-register #privacy-policy-registration-patient').on('change', function() {
                            if($(this).is(':checked')) {
                                $('.patient .form-register .facebook-custom-btn').removeAttr('custom-stopper');
                                $('.patient .form-register .civic-custom-btn').removeAttr('custom-stopper');
                            } else {
                                $('.patient .form-register .facebook-custom-btn').attr('custom-stopper', 'true');
                                $('.patient .form-register .civic-custom-btn').attr('custom-stopper', 'true');
                            }
                        });

                        $(document).on('civicCustomBtnClicked', async function (event) {
                            $('.patient .form-register .step-errors-holder').html('');
                        });

                        $(document).on('civicRead', async function (event) {
                            $('.response-layer').show();
                        });

                        $(document).on('facebookCustomBtnClicked', async function (event) {
                            $('.patient .form-register .step-errors-holder').html('');
                        });

                        $(document).on('customCivicFbStopperTriggered', async function (event) {
                            customErrorHandle($('.patient .form-register .step-errors-holder'), 'Please agree with our privacy policy.');
                        });
                        // ====================== /PATIENT LOGIN/SIGNUP LOGIC ======================

                        // ====================== DENTIST LOGIN/SIGNUP LOGIC ======================

                        //login
                        $('form#dentist-login').on('submit', function(event) {
                            //clear prev errors
                            if($('form#dentist-login .error-handle').length) {
                                $('form#dentist-login .error-handle').remove();
                            }

                            var form_fields = $(this).find('.custom-input');
                            var dentist_login_errors = false;
                            for(var i = 0, len = form_fields.length; i < len; i+=1) {
                                if(form_fields.eq(i).attr('type') == 'email' && !basic.validateEmail(form_fields.eq(i).val().trim())) {
                                    customErrorHandle(form_fields.eq(i).parent(), 'Please use valid email address.');
                                    dentist_login_errors = true;
                                } else if(form_fields.eq(i).attr('type') == 'password' && form_fields.eq(i).val().length < 6) {
                                    customErrorHandle(form_fields.eq(i).parent(), 'Passwords must be min length 6.');
                                    dentist_login_errors = true;
                                }

                                if(form_fields.eq(i).val().trim() == '') {
                                    customErrorHandle(form_fields.eq(i).parent(), 'This field is required.');
                                    dentist_login_errors = true;
                                }
                            }

                            if(dentist_login_errors) {
                                event.preventDefault();
                            }
                        });

                        //register
                        $('.dentist .form-register .prev-step').click(function() {
                            var current_step = $('.dentist .form-register .step.visible');
                            var current_prev_step = current_step.prev();
                            current_step.removeClass('visible');
                            if(current_prev_step.hasClass('first')) {
                                $(this).hide();
                            }
                            current_prev_step.addClass('visible');

                            $('.dentist .form-register .next-step').val('Next');
                            $('.dentist .form-register .next-step').attr('data-current-step', current_prev_step.attr('data-step'));
                        });

                        //SECOND STEP INIT LOGIC
                        //load address script
                        await $.getScript('/assets/js/address.js', function() {});

                        $('#dentist-country').on('change', function() {
                            $('.step.second .phone .country-code').html('+'+$(this).find('option:selected').attr('data-code'));
                        });

                        //THIRD STEP INIT LOGIC
                        styleAvatarUploadButton('.bootbox.login-signin-popup .dentist .form-register .step.third .avatar button label');
                        initCaptchaRefreshEvent();

                        $('.dentist .form-register .next-step').click(function() {
                            var this_btn = $(this);
                            var previous_step = this_btn.attr('data-current-step');

                            switch(this_btn.attr('data-current-step')) {
                                case 'first':
                                    var first_step_inputs = $('.dentist .form-register .step.first .custom-input');
                                    var errors = false;
                                    $('.dentist .form-register .step.first').parent().find('.error-handle').remove();
                                    for(var i = 0, len = first_step_inputs.length; i < len; i+=1) {
                                        if(first_step_inputs.eq(i).attr('type') == 'email' && !basic.validateEmail(first_step_inputs.eq(i).val().trim())) {
                                            customErrorHandle(first_step_inputs.eq(i).parent(), 'Please use valid email address.');
                                            errors = true;
                                        } else if(first_step_inputs.eq(i).attr('type') == 'password' && first_step_inputs.eq(i).val().length < 6) {
                                            customErrorHandle(first_step_inputs.eq(i).parent(), 'Passwords must be min length 6.');
                                            errors = true;
                                        }

                                        if(first_step_inputs.eq(i).val().trim() == '') {
                                            customErrorHandle(first_step_inputs.eq(i).parent(), 'This field is required.');
                                            errors = true;
                                        }
                                    }

                                    if($('.dentist .form-register .step.first .custom-input.password').val().trim() != $('.step.first .custom-input.repeat-password').val().trim()) {
                                        customErrorHandle($('.step.first .custom-input.repeat-password').parent(), 'Both passwords don\'t match.');
                                        errors = true;
                                    }

                                    if(!errors) {
                                        $('.dentist .form-register .step').removeClass('visible');
                                        $('.dentist .form-register .step.second').addClass('visible');
                                        $('.prev-step').show();

                                        this_btn.attr('data-current-step', 'second');
                                        this_btn.val('Next');
                                    }
                                    break;
                                case 'second':
                                    var second_step_inputs = $('.dentist .form-register .step.second .custom-input');
                                    var errors = false;
                                    $('.dentist .form-register .step.second').find('.error-handle').remove();

                                    //check custom-input fields
                                    for(var i = 0, len = second_step_inputs.length; i < len; i+=1) {
                                        if(second_step_inputs.eq(i).is('select')) {
                                            //IF SELECT TAG
                                            if(second_step_inputs.eq(i).val().trim() == '') {
                                                customErrorHandle(second_step_inputs.eq(i).parent(), 'This field is required.');
                                                errors = true;
                                            }
                                        } else if(second_step_inputs.eq(i).is('input')) {
                                            //IF INPUT TAG
                                            if(second_step_inputs.eq(i).val().trim() == '') {
                                                customErrorHandle(second_step_inputs.eq(i).parent(), 'This field is required.');
                                                errors = true;
                                            }

                                            if(second_step_inputs.eq(i).attr('type') == 'url' && !basic.validateUrl(second_step_inputs.eq(i).val().trim())) {
                                                customErrorHandle(second_step_inputs.eq(i).parent(), 'Please use valid website.');
                                                errors = true;
                                            }else if(second_step_inputs.eq(i).attr('type') == 'number' && !basic.validatePhone(second_step_inputs.eq(i).val().trim())) {
                                                customErrorHandle(second_step_inputs.eq(i).parent(), 'Please use valid numbers.');
                                                errors = true;
                                            }
                                        }
                                    }

                                    //check custom radio buttons
                                    if($('.dentist .form-register .step.second [name="work-type"]:checked').val() == undefined) {
                                        customErrorHandle($('.dentist .form-register .step.second .radio-buttons-holder'), 'Please select one of the options.');
                                        errors = true;
                                    } else {
                                        if($('.dentist .form-register .step.second [name="work-type"]:checked').val() == 'an-associate-dentist') {
                                            $('.dentist .form-register .step.third .search-for-clinic').html('<div class="padding-bottom-10"><select class="combobox custom-input"></select><input type="hidden" name="clinic-id"/></div>');

                                            $.ajax({
                                                type: 'POST',
                                                url: '/get-all-clinics/',
                                                dataType: 'json',
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                                success: function (response) {
                                                    console.log(response, 'response');
                                                    if(response.success && response.success.length > 0) {
                                                        var select_html = '<option></option>';
                                                        for(var i = 0, len = response.success.length; i < len; i+=1) {
                                                            select_html+='<option value="'+response.success[i].id+'">'+response.success[i].name+'</option>';
                                                        }

                                                        $('.dentist .form-register .step.third .search-for-clinic select.combobox').html(select_html);

                                                        initComboboxes();
                                                        $('.dentist .form-register .step.third .search-for-clinic input[type="text"].combobox').attr('placeholder', 'Search for a clinic...');

                                                        //update the hidden input value on the select change
                                                        $('.dentist .form-register .step.third .search-for-clinic select.combobox').on('change', function() {
                                                            $('.dentist .form-register .step.third .search-for-clinic input[name="clinic-id"]').val($(this).find('option:selected').val());
                                                        });
                                                    } else if(response.error) {
                                                        basic.showAlert(response.error);
                                                    }
                                                }
                                            });
                                        } else {
                                            $('.dentist .form-register .step.third .search-for-clinic').html('');
                                        }
                                    }

                                    //check if error from google place suggester
                                    if($('.dentist .form-register .step.second .suggester-parent .alert.alert-warning').is(':visible')) {
                                        customErrorHandle($('.dentist .form-register .step.second .radio-buttons-holder'), 'Please select one of the options.');
                                        errors = true;
                                    }

                                    if(!errors) {
                                        $('.dentist .form-register .step').removeClass('visible');
                                        $('.dentist .form-register .step.third').addClass('visible');

                                        this_btn.attr('data-current-step', 'third');
                                        this_btn.val('Create profile');
                                    }
                                    break;
                                case 'third':
                                    $('.dentist .form-register .step.third').find('.error-handle').remove();
                                    var errors = false;
                                    //checking if empty avatar
                                    if($('.dentist .form-register .step.third #custom-upload-avatar').val().trim() == '') {
                                        customErrorHandle($('.step.third .step-errors-holder'), 'Please select avatar.');
                                        errors = true;
                                    }

                                    //checking if no specialization checkbox selected
                                    if($('.dentist .form-register .step.third [name="specialization[]"]:checked').val() == undefined) {
                                        customErrorHandle($('.step.third .step-errors-holder'), 'Please select specialization/s.');
                                        errors = true;
                                    }

                                    //check captcha length
                                    if($('.dentist .form-register .step.third #register-captcha').val().trim() == '' || $('.dentist .form-register .step.third #register-captcha').val().trim().length < 5) {
                                        customErrorHandle($('.step.third .step-errors-holder'), 'Please enter correct captcha.');
                                        errors = true;
                                    }

                                    //check if privacy policy checkbox is checked
                                    if(!$('.dentist .form-register .step.third #privacy-policy-registration').is(':checked')) {
                                        customErrorHandle($('.step.third .step-errors-holder'), 'Please agree with our privacy policy.');
                                        errors = true;
                                    }

                                    if(!errors) {
                                        //submit the form
                                        $('form#dentist-register').submit();
                                    }
                                    break;
                            }
                        });
                        // ====================== /DENTIST LOGIN/SIGNUP LOGIC ======================
                    }
                }
            });
        });
    }
}
bindLoginSigninPopupShow();

function readURL(input, label_el) {
    if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            //SHOW THE IMAGE ON LOAD
            $(label_el).css({'background-image' : 'url("'+e.target.result+'")'});
            $(label_el).find('.inner i').addClass('fs-0');
            $(label_el).find('.inner .inner-label').addClass('fs-0');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function styleAvatarUploadButton(label_el)    {
    if(jQuery(".upload-file.avatar").length) {
        jQuery(".upload-file.avatar").each(function(key, form){
            var this_file_btn_parent = jQuery(this);
            if(this_file_btn_parent.attr('data-current-user-avatar')) {
                this_file_btn_parent.find('button').append('<label for="custom-upload-avatar" style="background-image:url('+this_file_btn_parent.attr('data-current-user-avatar')+');"><div class="inner"><i class="fa fa-plus fs-0" aria-hidden="true"></i><div class="inner-label fs-0">Add profile photo</div></div></label>');
            } else {
                this_file_btn_parent.find('button').append('<label for="custom-upload-avatar"><div class="inner"><i class="fa fa-plus" aria-hidden="true"></i><div class="inner-label">Add profile photo</div></div></label>');
            }

            var inputs = document.querySelectorAll('.inputfile');
            Array.prototype.forEach.call(inputs, function(input) {
                var label    = input.nextElementSibling,
                    labelVal = label.innerHTML;

                input.addEventListener('change', function(e) {
                    readURL(this, label_el);

                    var fileName = '';
                    if(this.files && this.files.length > 1)
                        fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length);
                    else
                        fileName = e.target.value.split('\\').pop();

                    /*if(fileName) {
                        if(load_filename_to_other_el)    {
                            $(this).closest('.form-row').find('.file-name').html('<i class="fa fa-file-text-o" aria-hidden="true"></i>' + fileName);
                        }else {
                            label.querySelector('span').innerHTML = fileName;
                        }
                    }else{
                        label.innerHTML = labelVal;
                    }*/
                });
                // Firefox bug fix
                input.addEventListener('focus', function(){ input.classList.add('has-focus'); });
                input.addEventListener('blur', function(){ input.classList.remove('has-focus'); });
            });
        });
    }
}

//hide bootbox popup when its clicked around him (outside of him)
function hidePopupOnBackdropClick() {
    $(document).on('click', '.bootbox', function(){
        var classname = event.target.className;
        classname = classname.replace(/ /g, '.');

        if(classname && !$('.' + classname).parents('.modal-dialog').length) {
            bootbox.hideAll();
        }
    });
}
hidePopupOnBackdropClick();

//transfer all selects to bootstrap combobox
function initComboboxes() {
    jQuery("select.combobox").each(function () {
        jQuery(this).combobox();
    });
}

function apiEventsListeners() {
    //login
    $(document).on('successResponseCoreDBApi', async function (event) {
        if(event.response_data.token) {
            var custom_form_obj = {
                token: event.response_data.token,
                id: event.response_data.data.id,
                email: event.response_data.data.email,
                have_contracts : false,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            //check if CoreDB returned address for this user and if its valid one
            if(basic.objHasKey(custom_form_obj, 'address') != null && innerAddressCheck(custom_form_obj.address)) {
                //var current_dentists_for_logging_user = await App.assurance_methods.getWaitingContractsForPatient(custom_form_obj.address);
                //if(current_dentists_for_logging_user.length > 0) {
                //custom_form_obj.have_contracts = true;
                //}
            }

            customJavascriptForm('/patient/authenticate', custom_form_obj, 'post');
        }
    });

    $(document).on('errorResponseCoreDBApi', function (event) {
        console.log(event, 'errorResponseCoreDBApi');
    });
}
apiEventsListeners();

//INIT LOGIC FOR ALL STEPS
function customErrorHandle(el, string) {
    el.append('<div class="error-handle">'+string+'</div>');
}

if($('form#invite-dentists').length) {
    $('form#invite-dentists').on('submit', function(event) {
        event.preventDefault();
        var this_form = $(this);

        var form_fields = this_form.find('.custom-input.required');
        var errors = false;
        this_form.find('.error-handle').remove();

        //check custom-input fields
        for(var i = 0, len = form_fields.length; i < len; i+=1) {
            if(form_fields.eq(i).is('select')) {
                //IF SELECT TAG
                if(form_fields.eq(i).val().trim() == '') {
                    customErrorHandle(form_fields.eq(i).parent(), 'This field is required.');
                    errors = true;
                }
            } else if(form_fields.eq(i).is('input')) {
                //IF INPUT TAG
                if(form_fields.eq(i).val().trim() == '') {
                    customErrorHandle(form_fields.eq(i).parent(), 'This field is required.');
                    errors = true;
                }
            }
        }

        if(this_form.find('input[name="dcn_address"]').length) {
            if(this_form.find('input[name="dcn_address"]').val().trim() == '' || !innerAddressCheck(this_form.find('input[name="dcn_address"]').val().trim())) {
                customErrorHandle(this_form.find('input[name="dcn_address"]').parent(), 'This field is required. Please enter valid Wallet Address.');
                errors = true;
            }
        }

        if(!errors) {
            $.ajax({
                type: 'POST',
                url: '/patient/get-invite-dentists-popup',
                dataType: 'json',
                data: {
                    serialized: this_form.serialize()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    basic.showDialog(response.success, 'invite-dentists-popup', null, true);
                    fixButtonsFocus();

                    var serialized_values = this_form.serializeArray();
                    var custom_form_obj = {};
                    $('.send-mail-invite-dentists').click(function() {
                        $('.response-layer').show();

                        //clear spamming
                        $(this).unbind();

                        for(var i = 0, len = serialized_values.length; i < len; i+=1) {
                            custom_form_obj[serialized_values[i].name] = serialized_values[i].value;
                        }

                        setTimeout(function() {
                            customJavascriptForm('/patient/submit-invite-dentists', custom_form_obj, 'post');
                        }, 1500);
                    });
                }
            });

            //AJAX
        }
    });
}

function showResponseLayer(time) {
    $('.response-layer').show();
    setTimeout(function() {
        $('.response-layer').hide();
    }, time);
}

function showContractResponseLayer(time) {
    $('.contract-response-layer').show();
    setTimeout(function() {
        $('.contract-response-layer').hide();
    }, time);
}

var signature_pad;
function initSignaturePad() {
    if($('#signature-pad').length) {
        var canvas = document.getElementById('signature-pad');

        // Adjust canvas coordinate space taking into account pixel ratio,
        // to make it look crisp on mobile devices.
        // This also causes canvas to be cleared.
        function resizeCanvas() {
            // When zoomed out to less than 100%, for some very strange reason,
            // some browsers report devicePixelRatio as less than 1
            // and only part of the canvas is cleared then.
            var ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }

        //window.onresize = resizeCanvas;
        resizeCanvas();

        signature_pad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
        });

        if($('.clear-signature').length) {
            $('.clear-signature').click(function() {
                signature_pad.clear();
            });
        }
    }
}

function customCreateContractErrorHandle(el, text) {
    el.addClass('with-error');
    el.closest('.single-row').addClass('row-with-error');
    el.parent().find('> label').append('<span class="error-in-label">'+text+'</span>');
}

function onWindowLoadPageData() {
    if($('body').hasClass('logged-in')) {

    }
}

async function onDocumentReadyPageData() {
    if($('body').hasClass('logged-in')) {
        if($('body').hasClass('congratulations')) {
            var next_transfer_timestamp = parseInt($('section.congratulation-and-time-section').attr('data-time-left-next-transfer')) + parseInt(await App.assurance_state_methods.getPeriodToWithdraw());
            if($('.converted-date').length > 0) {
                var date_obj = new Date(next_transfer_timestamp * 1000);
                $('.converted-date').html(dateObjToFormattedDate(date_obj));
            }
            initFlipClockTimer(next_transfer_timestamp - new Date().getTime() / 1000);
        } else if ($('body').hasClass('my-contracts')) {
            initDataTable();

            var table_trs_with_timestamp = $('.table-container table tr[data-timestamp-signed]');
            var smart_contract_withdraw_period = parseInt(await App.assurance_state_methods.getPeriodToWithdraw());
            var now_timestamp = Math.round((new Date()).getTime() / 1000);

            for(var i = 0, len = table_trs_with_timestamp.length; i < len; i+=1) {
                var time_passed_since_creation = now_timestamp - parseInt(table_trs_with_timestamp.eq(i).attr('data-timestamp-signed'));
                if(time_passed_since_creation > smart_contract_withdraw_period) {
                    var remainder = time_passed_since_creation % smart_contract_withdraw_period;
                    var next_payment_timestamp_date_obj = new Date((now_timestamp + smart_contract_withdraw_period - remainder) * 1000);
                } else {
                    var next_payment_timestamp_date_obj = new Date((now_timestamp + smart_contract_withdraw_period - time_passed_since_creation) * 1000);
                }

                table_trs_with_timestamp.eq(i).find('.next-payment').html(dateObjToFormattedDate(next_payment_timestamp_date_obj));
            }
        } else if ($('body').hasClass('patient-access')) {
            //make all contracts in the slider with same height
            if ($('.contract-tile').length) {
                var max_height = 0;
                for (var i = 0, len = $('.contract-tile .tile-wrapper').length; i < len; i += 1) {
                    if ($('.contract-tile .tile-wrapper').eq(i).outerHeight() > max_height) {
                        max_height = $('.contract-tile .tile-wrapper').eq(i).outerHeight();
                    }
                }
                $('.contract-tile .tile-wrapper').outerHeight(max_height);
            }
        }else if($('body').hasClass('patient-contract-view')) {
            var next_transfer_timestamp = parseInt($('.contract-body').attr('data-time-left-next-transfer')) + parseInt(await App.assurance_state_methods.getPeriodToWithdraw());
            if($('.converted-date').length > 0) {
                var date_obj = new Date(next_transfer_timestamp * 1000);
                $('.converted-date').html(dateObjToFormattedDate(date_obj));
            }
            initFlipClockTimer(next_transfer_timestamp - new Date().getTime() / 1000);

            cancelContractEventInit();
        }else if($('body').hasClass('contract-proposal')) {
            if ($('.contract-proposal.section').length && $('.contract-proposal.section').attr('data-created-at-timestamp') != undefined) {
                var date_obj = new Date((parseInt($('.contract-proposal.section').attr('data-created-at-timestamp')) + parseInt(await App.assurance_state_methods.getPeriodToWithdraw())) * 1000);
                $('.active-until').html(dateObjToFormattedDate(date_obj));
            }
        }
    }
}

function initFlipClockTimer(time_left) {
    var clock;
    if(time_left > 0) {
        clock = jQuery('.clock').FlipClock(time_left, {
            clockFace: 'DailyCounter',
            autoStart: false,
            showSeconds: false,
            callbacks: {
                stop: function() {
                    jQuery('.flip-clock-message').html('You are late with payment to your dentist.')
                }
            }
        });
        clock.setCountdown(true);
        clock.start();
    }else {
        jQuery('.countdown-section').hide();
    }
}

//if cancel contract button exist add the event for it
function cancelContractEventInit() {
    if($('.cancel-contract-btn').length) {
        $('.cancel-contract-btn').click(function() {
            var this_btn = $(this);
            $.ajax({
                type: 'POST',
                url: '/update-contract-status',
                dataType: 'json',
                data: {
                    contract: this_btn.attr('data-contract'),
                    status: 'cancelled'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        window.location = '/contract/' + this_btn.attr('data-contract');
                    } else if (response.error) {
                        basic.showAlert(response.error, '', true);
                    }
                }
            });
        })
    }
}

function styleUploadFileButton()    {
    $('.custom-upload-file').each(function(key, form){
        var this_btn = $(this);
        var caching = false;
        if(this_btn.hasClass('caching')) {
            caching = true;
        }

        var this_btn_parent = this_btn.closest('.upload-file-container');
        this_btn_parent.find('button').append("<label for='"+this_btn_parent.attr('data-id')+"' class='white-blue-green-btn display-block-important'><span class='display-block-important fs-18'>"+this_btn_parent.attr('data-label')+"</span></label>");

        var inputs = document.querySelectorAll('.custom-upload-file');
        Array.prototype.forEach.call( inputs, function( input ) {

            input.addEventListener('change', function(e) {
                var fileName = '';
                if(this.files && this.files.length > 1) {
                    fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length);
                } else {
                    fileName = e.target.value.split('\\').pop();
                }

                if(this_btn.attr('id') == 'upload-keystore-file') {
                    var uploaded_file = this.files[0];
                    var reader = new FileReader();
                    reader.addEventListener('load', function (e) {
                        if (isJsonString(e.target.result) && has(JSON.parse(e.target.result), 'address') && ('0x' + JSON.parse(e.target.result).address) == $('.proof-of-address').attr('data-address')) {
                            if(caching) {
                                var keystore_string = e.target.result;
                                $('.proof-of-address .on-change-result').html('<div class="col-xs-12 col-sm-5 col-sm-offset-7 padding-right-30 padding-top-5"><div class="fs-14 light-gray-color text-center padding-bottom-10 file-name">'+fileName+'</div><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-secret-key-password">Enter your secret key password:</label><input type="password" id="your-secret-key-password" maxlength="100" class="full-rounded"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn cache-key-btn">REMEMBER</a></div></div>');
                                bindGoogleAlikeButtonsEvents();
                                bindCacheKeyEvent(keystore_string);
                            } else {
                                var keystore_string = e.target.result;
                                $('.proof-of-address .on-change-result').html('<div class="col-xs-12 col-sm-5 col-sm-offset-7 padding-right-30 padding-top-5"><div class="fs-14 light-gray-color text-center padding-bottom-10 file-name">'+fileName+'</div><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-secret-key-password">Enter your secret key password:</label><input type="password" id="your-secret-key-password" maxlength="100" class="full-rounded"/></div><div class="checkbox-container"><div class="pretty p-svg p-curve on-white-background margin-bottom-0"><input type="checkbox" id="remember-my-keystore-file"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label class="fs-14 calibri-bold" for="remember-my-keystore-file">Remember my keystore file <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Remembering your keystore file allows for easier and faster transactions. It is stored only in your browser and nobody else has access to it."></i></label></div></div></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn verify-address-btn">VERIFY</a></div></div>');
                                initTooltips();
                                bindGoogleAlikeButtonsEvents();
                                bindVerifyAddressEvent(keystore_string);
                            }
                        } else {
                            $('#upload-keystore-file').val('');
                            basic.showAlert('Please upload valid keystore file.', '', true);
                        }
                    });
                    reader.readAsBinaryString(uploaded_file);
                }
            });
            // Firefox bug fix
            input.addEventListener('focus', function(){ input.classList.add('has-focus'); });
            input.addEventListener('blur', function(){ input.classList.remove('has-focus'); });
        });
    });
}

function bindGoogleAlikeButtonsEvents() {
    //google alike style for label/placeholders
    if($('.custom-google-label-style').length) {
        $('.custom-google-label-style label').unbind('click').on('click', function () {
            $(this).addClass('active-label');
            if($('.custom-google-label-style').attr('data-input-blue-green-border') == 'true') {
                $(this).parent().find('input').addClass('blue-green-border');
            }
        });

        $('.custom-google-label-style input').unbind('keyup change').on('keyup change', function () {
            var value = $(this).val().trim();
            if (value.length) {
                $(this).closest('.custom-google-label-style').find('label').addClass('active-label');
                if($('.custom-google-label-style').attr('data-input-blue-green-border') == 'true') {
                    $(this).addClass('blue-green-border');
                }
            } else {
                $(this).closest('.custom-google-label-style').find('label').removeClass('active-label');
                if($('.custom-google-label-style').attr('data-input-blue-green-border') == 'true') {
                    $(this).removeClass('blue-green-border');
                }
            }
        });
    }
}

//check if object has property
function has(object, key) {
    return object ? hasOwnProperty.call(object, key) : false;
}

//checking if string is valid json
function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

//bind the logic when address is not verified
function bindVerifyAddressLogic() {
    styleUploadFileButton();

    $('.enter-private-key').unbind().click(function() {
        $('.proof-of-address .on-change-result').html('<div class="col-xs-12 col-sm-5 padding-left-30 padding-top-20"><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-private-key">Your Private Key:</label><input type="text" id="your-private-key" maxlength="64" class="full-rounded"/></div><div class="checkbox-container"><div class="pretty p-svg p-curve on-white-background margin-bottom-0"><input type="checkbox" id="remember-my-private-key"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label class="fs-14 calibri-bold" for="remember-my-private-key">Remember my private key <i class="fa fa-info-circle" aria-hidden="true"  data-toggle="tooltip" data-placement="top" title="Remembering your key allows for easier and faster transactions. It is stored only in your browser and nobody else has access to it."></i></label></div></div></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn verify-address-btn">VERIFY</a></div></div>');
        initTooltips();
        $('.proof-of-address #upload-keystore-file').val('');
        bindGoogleAlikeButtonsEvents();
        bindVerifyAddressEvent();
    });

    $('.upload-file-container button').unbind().click(function() {
        $('.proof-of-address .on-change-result').html('');
    });
}

function bindVerifyAddressEvent(keystore_file) {
    if(keystore_file === undefined) {
        keystore_file = null;
    }
    $('.proof-of-address .verify-address-btn').click(function() {
        if(keystore_file != null) {
            //import with keystore
            if($('.proof-of-address #your-secret-key-password').val().trim() == '' || $('.proof-of-address #your-secret-key-password').val().trim().length > 100 || $('.proof-of-address #your-secret-key-password').val().trim().length < 6) {
                basic.showAlert('Please enter valid secret key password with length between 6 and 100 symbols.', '', true);
            } else {
                $('.response-layer').show();
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '/app-import',
                        dataType: 'json',
                        data: {
                            address: $('.proof-of-address').attr('data-address'),
                            keystore: keystore_file,
                            password: $('.proof-of-address #your-secret-key-password').val().trim()
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            //now with the address and the public key received from the nodejs api update the db
                            if(response.success) {
                                //if remember me option is checked
                                if($('#remember-my-keystore-file').is(':checked')) {
                                    localStorage.setItem('current-account', JSON.stringify({
                                        address: $('.proof-of-address').attr('data-address'),
                                        type: 'keystore',
                                        keystore: response.success
                                    }));
                                }

                                $.ajax({
                                    type: 'POST',
                                    url: '/update-public-keys',
                                    dataType: 'json',
                                    data: {
                                        address: $('.proof-of-address').attr('data-address'),
                                        public_key: response.public_key
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (inner_response) {
                                        $('.response-layer').hide();
                                        if(inner_response.success) {
                                            $('.proof-of-address').remove();
                                            $('.proof-success').fadeIn(1500);
                                        } else {
                                            basic.showAlert(inner_response.error, '', true);
                                        }
                                    }
                                });
                            } else if(response.error) {
                                $('.response-layer').hide();
                                basic.showAlert(response.error, '', true);
                            }
                        }
                    });
                }, 1000);
            }
        } else {
            //import with private key
            if($('.proof-of-address #your-private-key').val().trim() == '' || $('.proof-of-address #your-private-key').val().trim().length > 64) {
                basic.showAlert('Please enter valid private key.', '', true);
            } else {
                $('.response-layer').show();
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '/assurance-import-private-key',
                        dataType: 'json',
                        data: {
                            private_key: $('.proof-of-address #your-private-key').val().trim()
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            //now with the address and the public key received from the nodejs api update the db
                            if(response.success) {
                                //if remember me option is checked
                                if($('.proof-of-address #remember-my-private-key').is(':checked')) {
                                    localStorage.setItem('current-account', JSON.stringify({
                                        address: response.address,
                                        type: 'key',
                                        key: response.private_key
                                    }));
                                }

                                $.ajax({
                                    type: 'POST',
                                    url: '/update-public-keys',
                                    dataType: 'json',
                                    data: {
                                        address: response.address,
                                        public_key: response.public_key
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (inner_response) {
                                        $('.response-layer').hide();
                                        if(inner_response.success) {
                                            $('.proof-of-address').remove();
                                            $('.proof-success').fadeIn(1500);
                                        } else {
                                            basic.showAlert(inner_response.error, '', true);
                                        }
                                    }
                                });
                            } else if(response.error) {
                                $('.response-layer').hide();
                                basic.showAlert(response.error, '', true);
                            }
                        }
                    });
                }, 1000);
            }
        }
    });
}

function bindCacheKeyEvent(keystore_file) {
    if(keystore_file === undefined) {
        keystore_file = null;
    }
    $('.proof-of-address .cache-key-btn').click(function() {
        if(keystore_file != null) {
            //import with keystore
            if($('.proof-of-address #your-secret-key-password').val().trim() == '' || $('.proof-of-address #your-secret-key-password').val().trim().length > 100 || $('.proof-of-address #your-secret-key-password').val().trim().length < 6) {
                basic.showAlert('Please enter valid secret key password with length between 6 and 100 symbols.', '', true);
            } else {
                $('.response-layer').show();
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '/app-import',
                        dataType: 'json',
                        data: {
                            address: $('.proof-of-address').attr('data-address'),
                            keystore: keystore_file,
                            password: $('.proof-of-address #your-secret-key-password').val().trim()
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            //now with the address and the public key received from the nodejs api update the db
                            if(response.success) {
                                //if remember me option is checked
                                localStorage.setItem('current-account', JSON.stringify({
                                    address: $('.proof-of-address').attr('data-address'),
                                    type: 'keystore',
                                    keystore: response.success
                                }));

                                $.ajax({
                                    type: 'POST',
                                    url: '/update-public-keys',
                                    dataType: 'json',
                                    data: {
                                        address: $('.proof-of-address').attr('data-address'),
                                        public_key: response.public_key
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (inner_response) {
                                        $('.response-layer').hide();
                                        $('.remember-my-wallet-camp').remove();
                                        basic.showAlert('Your wallet has been remembered successfully. If you want to delete your private key or keystore file you can do this from Manage Privacy section in your profile.', '', true);
                                    }
                                });
                            } else if(response.error) {
                                $('.response-layer').hide();
                                basic.showAlert(response.error, '', true);
                            }
                        }
                    });
                }, 1000);
            }
        } else {
            //import with private key
            if($('.proof-of-address #your-private-key').val().trim() == '' || $('.proof-of-address #your-private-key').val().trim().length > 64) {
                basic.showAlert('Please enter valid private key.', '', true);
            } else {
                $('.response-layer').show();
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '/assurance-import-private-key',
                        dataType: 'json',
                        data: {
                            private_key: $('.proof-of-address #your-private-key').val().trim()
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            //now with the address and the public key received from the nodejs api update the db
                            if(response.success) {
                                localStorage.setItem('current-account', JSON.stringify({
                                    address: response.address,
                                    type: 'key',
                                    key: response.private_key
                                }));

                                $.ajax({
                                    type: 'POST',
                                    url: '/update-public-keys',
                                    dataType: 'json',
                                    data: {
                                        address: response.address,
                                        public_key: response.public_key
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (inner_response) {
                                        $('.response-layer').hide();
                                        $('.remember-my-wallet-camp').remove();
                                        basic.showAlert('Your wallet has been remembered successfully. If you want to delete your private key or keystore file you can do this from Manage Privacy section in your profile.', '', true);
                                    }
                                });
                            } else if(response.error) {
                                $('.response-layer').hide();
                                basic.showAlert(response.error, '', true);
                            }
                        }
                    });
                }, 1000);
            }
        }
    });
}

function openCacheKeyPopup() {
    $.ajax({
        type: 'POST',
        url: '/get-address-validation-or-remember-me',
        dataType: 'json',
        data: {
            cache: false
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                basic.showDialog('<div class="lato-regular fs-30 text-center padding-bottom-40 padding-top-15">Unlock the PDF file with your private key or your keystore file</div>' + response.success, 'address-validation-or-remember-me', null, true);

                styleUploadFileButton();

                $('.enter-private-key').unbind().click(function() {
                    $('.proof-of-address .on-change-result').html('<div class="col-xs-12 col-sm-5 padding-left-30 padding-top-20"><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-private-key">Your Private Key:</label><input type="text" id="your-private-key" maxlength="64" class="full-rounded"/></div><div class="checkbox-container"><div class="pretty p-svg p-curve on-white-background margin-bottom-0"><input type="checkbox" id="remember-my-private-key"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label class="fs-14 calibri-bold" for="remember-my-private-key">Remember my private key <i class="fa fa-info-circle" aria-hidden="true"  data-toggle="tooltip" data-placement="top" title="Remembering your key allows for easier and faster transactions. It is stored only in your browser and nobody else has access to it."></i></label></div></div></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn verify-address-btn">VERIFY</a></div></div>');
                    $('.proof-of-address #upload-keystore-file').val('');
                    bindGoogleAlikeButtonsEvents();
                    bindVerifyAddressEvent();
                });

                $('.upload-file-container button').unbind().click(function() {
                    $('.proof-of-address .on-change-result').html('');
                });
            }
        }
    });
}

async function validateUserAddress(user_address, value_element) {
    var error;
    var check_public_key_ajax_result = await $.ajax({
        type: 'POST',
        url: '/check-public-key',
        dataType: 'json',
        data: {
            address: user_address
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if (check_public_key_ajax_result.success) {
        $('.proof-of-address').remove();
        error = false;
    } else if (check_public_key_ajax_result.error) {
        if(value_element.is('input')) {
            $('.camping-for-validation').html('<div class="single-row proof-of-address padding-bottom-20" data-address="'+user_address+'"><div class="text-center calibri-bold fs-18 padding-top-20 padding-bottom-15">PLEASE VERIFY YOU OWN THIS ADDRESS</div><div class="container-fluid"><div class="row fs-0"><div class="col-xs-12 col-sm-5 inline-block padding-left-30"><a href="javascript:void(0)" class="blue-green-white-btn text-center enter-private-key display-block-important fs-18 line-height-18"><span>Enter your Private Key<div class="fs-16">(not recommended)</div></span></a></div><div class="col-xs-12 col-sm-2 text-center calibri-bold fs-20 inline-block">or</div><div class="col-xs-12 col-sm-5 inline-block padding-right-30"><div class="upload-file-container" data-id="upload-keystore-file" data-label="Upload your Keystore file"><input type="file" id="upload-keystore-file" class="custom-upload-file hide-input"/><button type="button" class="display-block"></button></div></div></div><div class="row on-change-result"></div></div></div><div class="single-row proof-success no-transition padding-top-20 padding-bottom-20 fs-20 calibri-bold text-center">Successful address verification.</div>');
            $('.proof-of-address').addClass('proof-failed');

            fixButtonsFocus();
            bindVerifyAddressLogic();
            error = true;
        } else {
            $('.proof-of-address').addClass('proof-failed');
            error = true;
        }
    }
    return error;
}

function initTooltips() {
    if($('[data-toggle="tooltip"]')) {
        $('[data-toggle="tooltip"]').tooltip();
    }
}

function initDataTable()    {
    if($('table.table.table-without-reorder').length > 0) {
        if($('table.table.table-without-reorder').hasClass('media-table'))  {
            $('table.table.table-without-reorder.media-table').DataTable().on('draw.dt', function (){
                var pagination_id = null;
                if($(this).attr('data-id-in-action') != undefined) {
                    pagination_id = $(this).attr('data-id-in-action');
                }
                var close_button;
                if($(this).attr('data-close-btn') == 'false')   {
                    close_button = false;
                }else if($(this).attr('data-close-btn') == 'true')   {
                    close_button = true;
                }
                useMediaEvent(pagination_id, close_button);
            });
        }else {
            $('table.table.table-without-reorder').DataTable({
                sort: false
            });

            if($('table.table.table-without-reorder').hasClass('my-contracts')) {
                $('.dataTables_filter input').addClass('custom-input green-arrow-background').attr('placeholder', 'Search for contract');
            }
        }
    }
    if($('table.table.table-with-reorder').length > 0) {
        var table = $('table.table.table-with-reorder').DataTable({
            rowReorder: true
        });
        $('table.table.table-with-reorder').addClass('sortable');
        table.on('row-reorder', function(e, diff, edit) {
            var order_object = {};
            for(let i = 0, len = diff.length; i < len; i+=1) {
                order_object[$(diff[i].node).data('id')] = diff[i].newPosition;
            }
            $.ajax({
                type: 'POST',
                url: SITE_URL + '/'+$('table.table.table-with-reorder').attr('data-action')+'/update-order',
                data: {
                    'order_object' : order_object
                },
                dataType: 'json',
                success: function (response) {
                    if(response.success)    {
                        basic.showAlert(response.success, '', true);
                    }
                }
            });
        });
    }
}

function dateObjToFormattedDate(object) {
    if(object.getDate() < 10) {
        var date = '0' + object.getDate();
    } else {
        var date = object.getDate();
    }

    if(object.getMonth() + 1 < 10) {
        var month = '0' + (object.getMonth() + 1);
    } else {
        var month = object.getMonth() + 1;
    }
    return date + '/' + month + '/' + object.getFullYear();
}

async function getEncryptedContractPdfContent(hash, type) {
    return await $.ajax({
        type: 'POST',
        url: '/decrypt-contract',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            ipfs_hash: hash,
            type: type
        }
    });
}

async function getDecryptedPdfContentByPlainKey(encrypted_html, key) {
    return await $.ajax({
        type: 'POST',
        url: '/decrypt-data-plain-key',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            encrypted_html: encrypted_html,
            private_key: key
        }
    });
}

async function getDecryptedPdfContent(encrypted_html, key) {
    return await $.ajax({
        type: 'POST',
        url: '/decrypt-data',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            encrypted_html: encrypted_html,
            private_key: key
        }
    });
}

/*
async function renderPdfFromDecryptedPdfContent(response_param) {
    if(response_param.success) {
        $.ajax({
            type: 'POST',
            url: '/render-pdf',
            dataType: 'json',
            data: {
                decrypted_data: response_param.success
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log(response);
            }
        });
    } else if(response_param.error) {
        basic.showAlert(response_param.error, '', true);
    }
}*/
