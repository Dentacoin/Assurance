console.log('Don\'t touch the code. Or do ... ¯\\_(ツ)_/¯');

checkIfCookie();
var allowAutomaticScripts = true;
$(window).bind('beforeunload',function(){
    allowAutomaticScripts = false;
});

var {getWeb3, importKeystoreFile, decryptKeystore, decryptDataByPlainKey, importPrivateKey, decryptDataByKeystore} = require('./helper');
var {assurance_config} = require('./assurance_config');

$(document).ready(async function() {
    await dApp.init();

    projectData.pagesData.onDocumentReady();

    fixButtonsFocus();
});

$(window).on('load', function() {

});

$(window).on('resize', function() {
    if ($('.my-contracts-container').length) {
        triggerIframeSizeEventForParent($('.my-contracts-container').width(), $('.my-contracts-container').height());
    }
});

$(window).on('scroll', function()  {

});

//on button click next time when you hover the button the color is bugged until you click some other element (until you move out the focus from this button)
function fixButtonsFocus() {
    $(document).on('click', '.white-blue-green-btn', function() {
        $(this).blur();
    });

    $(document).on('click', '.blue-green-white-btn', function() {
        $(this).blur();
    });

    $(document).on('click', '.white-transparent-btn', function() {
        $(this).blur();
    });
}

//init cookie events only if exists
function checkIfCookie()    {
    if ($('.privacy-policy-cookie').length > 0)  {
        $('.privacy-policy-cookie .accept-all').click(function()    {
            basic.cookies.set('performance_cookies', 1);
            basic.cookies.set('functionality_cookies', 1);
            basic.cookies.set('marketing_cookies', 1);
            basic.cookies.set('strictly_necessary_policy', 1);

            window.location.reload();
        });

        $('.adjust-cookies').click(function() {
            $('.customize-cookies').remove();

            $('.privacy-policy-cookie').append('<div class="customize-cookies"><button class="close-customize-cookies close-customize-cookies-popup">×</button><div class="text-center"><img src="/assets/images/cookie-icon.svg" alt="Cookie icon" class="cookie-icon"/></div><div class="text-center padding-top-10 padding-bottom-20 fs-20">Select cookies to accept:</div><div class="cookies-options-list"><ul><li><div class="pretty p-svg p-curve"><input checked disabled type="checkbox" id="strictly-necessary-cookies"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label for="strictly-necessary-cookies"><span>Strictly necessary</span> <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Cookies essential to navigate around the website and use its features. Without them, you wouldn’t be able to use basic services like signup or login."></i></label></div></div></li><li><div class="pretty p-svg p-curve"><input checked type="checkbox" id="functionality-cookies"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label for="functionality-cookies">Functionality cookies <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="These cookies allow users to customise how a website looks for them; they can remember usernames, preferences, etc."></i></label></div></div></li></ul><ul><li><div class="pretty p-svg p-curve"><input checked type="checkbox" id="performance-cookies"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label for="performance-cookies">Performance cookies <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="These cookies collect data for statistical purposes on how visitors use a website, they don’t contain personal data and are used to improve user experience."></i></label></div></div></li><li><div class="pretty p-svg p-curve"><input checked type="checkbox" id="marketing-cookies"/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label for="marketing-cookies">Marketing cookies <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Marketing cookies are used e.g. to deliver advertisements more relevant to you or limit the number of times you see an advertisement."></i></label></div></div></li></ul></div><div class="text-center actions"><a href="javascript:void(0);" class="white-blue-green-btn white-border margin-right-10 close-customize-cookies-popup">CANCEL</a><a href="javascript:void(0);" class="blue-green-white-btn white-border custom-cookie-save">SAVE</a></div><div class="custom-triangle"></div></div>');

            projectData.initiators.initTooltips();

            $('.close-customize-cookies-popup').click(function() {
                $('.customize-cookies').remove();
            });

            $('.custom-cookie-save').click(function() {
                basic.cookies.set('strictly_necessary_policy', 1);

                if ($('#functionality-cookies').is(':checked')) {
                    basic.cookies.set('functionality_cookies', 1);
                }

                if ($('#marketing-cookies').is(':checked')) {
                    basic.cookies.set('marketing_cookies', 1);
                }

                if ($('#performance-cookies').is(':checked')) {
                    basic.cookies.set('performance_cookies', 1);
                }

                window.location.reload();
            });
        });
    }
}

// camping for event when user didn't accept strictly necessary cookies
$(document).on('cannotLoginBecauseOfMissingCookies', function (event) {
    basic.showAlert('Please accept the strictly necessary cookies in order to continue with logging in.', '', true);
});

var is_mac = navigator.platform.indexOf('Mac') > -1;
var global_state = {};
var temporally_timestamp = 0;
var metamask = typeof(web3) !== 'undefined' && web3.currentProvider.isMetaMask === true;
var dApp = {
    assurance_state_instance: null,
    assurance_proxy_instance: null,
    dentacoin_instance: null,
    grace_period: 1814400,
    web3Provider: null,
    web3_0_2: null,
    web3_1_0: null,
    clinics_holder: null,
    contracts: {},
    loading: false,
    init: function() {
        return dApp.initWeb3();
    },
    initWeb3: async function()    {
        if (metamask) {
            //METAMASK
            dApp.web3_0_2 = web3;
            //global_state.account = dApp.web3_0_2.eth.defaultAccount;
            //overwrite web3 0.2 with web 1.0
            web3 = getWeb3(dApp.web3_0_2.currentProvider);
            //dApp.web3_1_0 = web3;
            dApp.web3_1_0 = getWeb3(new Web3.providers.HttpProvider(assurance_config.infura_node));
        }else if (typeof(web3) === 'undefined')    {
            //CUSTOM
            dApp.web3_1_0 = getWeb3(new Web3.providers.HttpProvider(assurance_config.infura_node));
        }else {
            //NO CUSTOM, NO METAMASK. Doing this final third check so we can use web3_1_0 functions and utils even if there is no metamask or custom imported/created account
            dApp.web3_1_0 = getWeb3();
        }

        if ($('body').hasClass('logged-in')) {
            if ($('body').hasClass('patient-side')) {
                if ($('.patient-contract-single-page-section').length) {
                    global_state.account = projectData.utils.checksumAddress($('.patient-contract-single-page-section').attr('data-patient'));
                }
            } else if ($('body').hasClass('dentist-side')) {
                if ($('.single-contract-view-section').length) {
                    global_state.account = projectData.utils.checksumAddress($('.single-contract-view-section').attr('data-dentist'));
                }
            }

            //if some fake or false current-account localstorage variable is set -> delete it
            /*if (localStorage.getItem('current-account') != null) {
                var current_account_obj = JSON.parse(localStorage.getItem('current-account'));
                if (!basic.property_exists(current_account_obj, 'address') || !projectData.utils.innerAddressCheck(current_account_obj.address) || global_state.account.toLowerCase() != current_account_obj.address.toLowerCase() || !basic.property_exists(current_account_obj, 'type') || (basic.property_exists(current_account_obj, 'type') && current_account_obj.type != 'keystore')) {
                    localStorage.removeItem('current-account');
                }
            }*/
        }

        console.log(global_state, 'Before dApp.initContract()');

        return dApp.initContract();
    },
    initContract: async function() {
        //Assurance STATE
        dApp.assurance_state_instance = await new dApp.web3_1_0.eth.Contract(assurance_config.assurance_state_abi, assurance_config.assurance_state_address);
        //Assurance PROXY
        dApp.assurance_proxy_instance = await new dApp.web3_1_0.eth.Contract(assurance_config.assurance_proxy_abi, assurance_config.assurance_proxy_address);
        //DentacoinToken
        dApp.dentacoin_token_instance = await new dApp.web3_1_0.eth.Contract(assurance_config.dentacoin_token_abi, assurance_config.dentacoin_token_address);

        //init pages logic
        projectData.pagesData.onContractInit();
    },
    dentacoin_token_methods: {
        allowance: function(owner, spender)  {
            return dApp.dentacoin_token_instance.methods.allowance(owner, spender).call({ from: global_state.account }, function(error, result)   {
                if (!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        approve: function()  {
            return dApp.dentacoin_token_instance.methods.approve(assurance_config.assurance_state_address, assurance_config.dentacoins_to_approve).send({
                from: global_state.account,
                gas: 65000
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        },
        balanceOf: function(address)  {
            return dApp.dentacoin_token_instance.methods.balanceOf(address).call({}, function(error, result)   {
                if (!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        }
    },
    assurance_proxy_methods: {
        registerContract: async function(patient_addr, dentist_addr, value_usd, value_dcn, date_start_contract, contract_ipfs_hash)  {
            if (!projectData.utils.innerAddressCheck(patient_addr) || !projectData.utils.innerAddressCheck(dentist_addr)) {
                //check if patient and dentist addresses are valid
                basic.showAlert('Patient and dentist addresses must be valid.');
                return false;
            } else if (parseInt(await dApp.dentacoin_token_methods.allowance(patient_addr, assurance_config.assurance_state_address)) <= 0) {
                basic.showAlert('This patient didn\'t give allowance to Assurance contract to manage his Dentacoins.');
                return false;
            } else if (parseInt(value_usd) <= 0 || parseInt(value_dcn) <= 0) {
                //check if USD and DCN values are valid
                basic.showAlert('Both USD and DCN values must be greater than 0.');
                return false;
            } else if (date_start_contract < 0) {
                //check if valid timestamp
                basic.showAlert('Please enter valid date.');
                return false;
            } else if (contract_ipfs_hash == '') {
                //check if ipfs hash is passed
                basic.showAlert('Please enter valid date.');
                return false;
            }
            return dApp.assurance_proxy_instance.methods.registerContract(patient_addr, dentist_addr, value_usd, value_dcn, date_start_contract, contract_ipfs_hash).send({
                from: global_state.account
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        }
    },
    assurance_state_methods: {
        getPeriodToWithdraw: function()  {
            return dApp.assurance_state_instance.methods.getPeriodToWithdraw().call({}, function(error, result)   {
                if (!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        getPatient: function(_patient_addr, _dentist_addr)  {
            return dApp.assurance_state_instance.methods.getPatient(_patient_addr, _dentist_addr).call({}, function(error, result)   {
                if (!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        }
        /*getDentist: function(dentist_addr)  {
            return dApp.assurance_state_instance.methods.getDentist(dentist_addr).call({ from: global_state.account }, function(error, result)   {
                if (!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        getPatient: function(patient_addr, dentist_addr)  {
            return dApp.assurance_state_instance.methods.getPatient(patient_addr, dentist_addr).call({ from: global_state.account }, function(error, result)   {
                if (!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        getDentistsArr: function()  {
            return dApp.assurance_state_instance.methods.getDentistsArr().call({ from: global_state.account }, function(error, result)   {
                if (!error)  {
                    console.log(result);
                }else {
                    console.error(error);
                }
            });
        },
        getPatientsArrForDentist: function(dentist_addr)  {
            return dApp.assurance_state_instance.methods.getPatientsArrForDentist(dentist_addr).call({ from: global_state.account }, function(error, result)   {
                if (!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        getWaitingContractsForPatient: function(patient_addr)  {
            return dApp.assurance_state_instance.methods.getWaitingContractsForPatient(patient_addr).call({ from: global_state.account }, function(error, result)   {
                if (!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        breakContract: function(patient_addr, dentist_addr)  {
            //check if patient and dentist addresses are valid
            if (!projectData.utils.innerAddressCheck(patient_addr) || !projectData.utils.innerAddressCheck(dentist_addr)) {
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
            if (!projectData.utils.innerAddressCheck(patient_addr)) {
                basic.showAlert('Patient address must be valid.');
                return false;
            }
            return dApp.assurance_state_instance.methods.dentistApproveContract(patient_addr).send({
                from: global_state.account,
                gas: 65000
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        },
        patientApproveContract: function(dentist_addr)  {
            return dApp.assurance_state_instance.methods.patientApproveContract(dentist_addr).send({
                from: global_state.account,
                gas: 65000
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        },
        registerContract: async function(patient_addr, dentist_addr, value_usd, value_dcn, date_start_contract, contract_ipfs_hash)  {
            var check_if_dentist_registered = await dApp.assurance_methods.getDentist(dentist_addr);
            //check if patient and dentist addresses are valid
            if (!projectData.utils.innerAddressCheck(patient_addr) || !projectData.utils.innerAddressCheck(dentist_addr)) {
                basic.showAlert('Patient and dentist addresses must be valid.');
                return false;
            }
            //check if dentist is registered on Assurance contract
            if (check_if_dentist_registered.toLowerCase() != dentist_addr.toLowerCase()) {
                basic.showAlert('You are not registered dentist on the Assurance contract. In order to init contracts you must first register your self.');
                return false;
            }
            //(talk with Jeremias about this check) check if patient gave allowance to Assurance contract to manage his Dentacoins
            if (parseInt(await dApp.dentacoin_token_methods.allowance(patient_addr, dApp.assurance_address)) <= 0) {
                basic.showAlert('This patient didn\'t give allowance to Assurance contract to manage his Dentacoins.');
                return false;
            }
            //check if USD and DCN values are valid
            if (parseInt(value_usd) <= 0 || parseInt(value_dcn) <= 0) {
                basic.showAlert('Both USD and DCN values must be greater than 0.');
                return false;
            }
            //check if valid timestamp
            if (date_start_contract < 0) {
                basic.showAlert('Please enter valid date.');
                return false;
            }
            return dApp.assurance_state_instance.methods.registerContract(patient_addr, dentist_addr, value_usd, value_dcn, date_start_contract, contract_ipfs_hash).send({
                from: global_state.account,
                gas: 330000
            }).on('transactionHash', function(hash){
                basic.showAlert('Your transaction is now pending. Give it a minute and check for confirmation on <a href="https://rinkeby.etherscan.io/tx/'+hash+'" target="_blank" class="etherscan-hash">Etherscan</a>.', '', true);
            }).catch(function(err) {
                console.error(err);
            });
        },
        registerDentist: function()  {
            return dApp.assurance_state_instance.methods.registerDentist().send({
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
            var current_patients_for_dentist = await dApp.assurance_methods.getPatientsArrForDentist(global_state.account);
            if (current_patients_for_dentist.length > 0) {
                for (var i = 0, len = current_patients_for_dentist.length; i < len; i += 1) {
                    var patient = await dApp.assurance_methods.getPatient(current_patients_for_dentist[i], global_state.account);
                    //if time passed for next_transfer of contract and if the contract is approved by both patient and dentist and then dentist can withdraw from patient legit
                    console.log(patient);
                    if (Math.round(new Date().getTime() / 1000) > parseInt(patient[2]) && patient[3] && patient[4]) {
                        ready_to_withdraw_arr.push(patient[1]);
                    }
                }
            }

            if (ready_to_withdraw_arr.length > 0) {
                return dApp.assurance_state_instance.methods.withdrawToDentist(ready_to_withdraw_arr).send({
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
    helper: {
        addBlockTimestampToTransaction: function(transaction)    {
            return new Promise(function(resolve, reject) {
                dApp.web3_1_0.eth.getBlock(transaction.blockNumber, function(error, result) {
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
                dApp.web3_1_0.eth.getBlock(block_num, function(error, result) {
                    if (error !== null) {
                        reject(error);
                    }
                    resolve(result.timestamp);
                });
            });
        },
        getBlockNum: function()  {
            return new Promise(function(resolve, reject) {
                dApp.web3_1_0.eth.getBlockNumber(function(error, result) {
                    if (!error){
                        global_state.curr_block = result;
                        resolve(global_state.curr_block);
                    }
                });
            });
        },
        getAccounts: function()  {
            return new Promise(function(resolve, reject) {
                dApp.web3_1_0.eth.getAccounts(function(error, result) {
                    if (!error){
                        resolve(result);
                    }
                });
            });
        },
        estimateGas: function(address, function_abi)  {
            return new Promise(function(resolve, reject) {
                dApp.web3_1_0.eth.estimateGas({
                    to: address,
                    data: function_abi
                }, function(error, result) {
                    if (!error){
                        resolve(result);
                    }
                });
            });
        },
        getGasPrice: function() {
            return new Promise(function(resolve, reject) {
                dApp.web3_1_0.eth.getGasPrice(function(error, result) {
                    if (!error){
                        resolve(result);
                    }
                });
            });
        },
        getAddressETHBalance: function(address)    {
            return new Promise(function(resolve, reject) {
                resolve(dApp.web3_1_0.eth.getBalance(address));
            });
        }
    }
};

var projectData = {
    variables: {
        bonusPercentagesToGasEstimations: 15
    },
    utils: {
        innerAddressCheck: function(address)    {
            //checking if passed address is valid
            return dApp.web3_1_0.utils.isAddress(address);
        },
        checksumAddress: function(address)    {
            //converting address to checksum
            return dApp.web3_1_0.utils.toChecksumAddress(address);
        },
        bytesToMegabytes: function(bytes) {
            return bytes / Math.pow(1024, 2);
        },
        dateObjToFormattedDate: function(object) {
            if (object.getDate() < 10) {
                var date = '0' + object.getDate();
            } else {
                var date = object.getDate();
            }

            if (object.getMonth() + 1 < 10) {
                var month = '0' + (object.getMonth() + 1);
            } else {
                var month = object.getMonth() + 1;
            }
            return date + '/' + month + '/' + object.getFullYear();
        },
        isJsonString: function(str) {
            //checking if string is valid json
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        },
        convertUsdToDcn: function(usd_val) {
            if ($("[data-dcn-for-one-usd]").length) {
                return parseInt($("[data-dcn-for-one-usd]").attr('data-dcn-for-one-usd')) * usd_val;
            } else {
                return false;
            }
        },
        getGETParameters: function() {
            var prmstr = window.location.search.substr(1);
            return prmstr != null && prmstr != "" ? projectData.utils.transformToAssocArray(prmstr) : {};
        },
        transformToAssocArray: function(prmstr) {
            var params = {};
            var prmarr = prmstr.split("&");
            for (var i = 0, len = prmarr.length; i < len; i+=1) {
                var tmparr = prmarr[i].split("=");
                params[tmparr[0]] = tmparr[1];
            }
            return params;
        }
    },
    initiators: {
        initDatepicker: function() {
            if ($('.datepicker').length > 0) {
                $('.datepicker').datepicker({
                    dateFormat: 'yy-mm-dd',
                    startDate: '-3d'
                });
            }
        },
        fixSelectsOnMac: function() {
            if (is_mac) {
                $('select').addClass('select-mac-fix');
            }
        },
        initPopoverTooltips: function() {
            if ($('.popover-el').length) {
                $('.popover-el').popover({
                    trigger: 'click',
                    html: true
                });
            }
        },
        initMobileMenu: function() {
            $('header .hamburger').click(function()    {
                $('nav.sidenav').addClass('active');
            });

            $('nav.sidenav .close-btn, nav.sidenav ul li a').click(function()    {
                $('nav.sidenav').removeClass('active');
            });

        },
        initDateTimePicker: function() {
            if ($(".form_datetime").length > 0) {
                $(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
            }
        },
        initFlipClockTimer: function(time_left) {
            var clock;
            if (time_left > 0) {
                clock = jQuery('.clock').FlipClock(time_left, {
                    clockFace: 'DailyCounter',
                    autoStart: false,
                    showSeconds: false,
                    callbacks: {
                        stop: function() {
                            window.location.reload();
                        }
                    }
                });
                clock.setCountdown(true);
                clock.start();
            }else {
                jQuery('.countdown-section').hide();
            }
        },
        initTooltips: function() {
            if ($('[data-toggle="tooltip"]').length) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        initComboboxes: function() {
            jQuery("select.combobox").each(function () {
                jQuery(this).combobox();
            });
        }
    },
    pagesData: {
        // ================== PAGES ==================
        onInit: async function() {
            if ($('body').hasClass('home')) {
                if ($('.testimonials-slider').length > 0) {
                    $('.testimonials-slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        autoplay: true,
                        autoplaySpeed: 8000,
                        adaptiveHeight: true
                    });
                }

                if ($('.open-calculator').length > 0) {
                    $('.open-calculator').click(function() {
                        $.ajax({
                            type: 'POST',
                            url: '/get-calculator-html',
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.success) {
                                    basic.closeDialog();
                                    basic.showDialog(response.success, 'calculator-popup', null, true);
                                    $('#number-of-patients').focus();

                                    $('.selectpicker').selectpicker('refresh');

                                    calculateLogic();
                                }
                            }
                        });
                    });
                }
            } else if ($('body').hasClass('patient-access')) {
                if ($('.ask-your-dentist-for-assurance').length) {
                    $('.ask-your-dentist-for-assurance').click(function() {
                        fireGoogleAnalyticsEvent('Assurance Patient', 'Scroll', 'Assurance Request');

                        $('html, body').animate({scrollTop: $('#find-your-dentist').offset().top}, 500);
                        $('#find-your-dentist .search-dentist-input').focus();
                        return false;
                    });
                }

                //init select combobox with clinics
                projectData.initiators.initComboboxes();

                if ($('section#find-your-dentist select.combobox').length) {
                    $('section#find-your-dentist select.combobox').on('keydown', function (e) {
                        if (e.which == 13) {
                            $('.open-dentacoin-gateway').click();
                        }
                    });

                    //on change show login popup
                    $('section#find-your-dentist input[type="text"].combobox').attr('placeholder', 'Search for a clinic...');

                    //on enter press show login popup
                    $('section#find-your-dentist select.combobox').on('change', function() {
                        $('.open-dentacoin-gateway').click();
                    });
                }

                if ($('section.section-logged-patient-form select.combobox').length) {
                    //on change show login popup
                    $('section.section-logged-patient-form input[type="text"].combobox').attr('placeholder', 'Find your preferred dentist/s in a snap...');
                }
            } else if ($('body').hasClass('support-guide')) {
                if ($('.support-guide-slider').length) {
                    $('.support-guide-slider').slick({
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        responsive: [
                            {
                                breakpoint: 992,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });
                }

                if ($('.section-support-guide-list .question').length > 0) {
                    $('.section-support-guide-list .question').click(function()   {
                        $(this).closest('li').find('.question-content').toggle(300);
                    });
                }
            } else if ($('body').hasClass('assurance-demo')) {
                var demo_video_time_watched = 0;
                var demo_video_timer;

                $('video.demo-video').on('play', function() {
                    demo_video_timer = setInterval(function() {
                        demo_video_time_watched+=1;
                    }, 1000);
                });

                $('video.demo-video').on('pause', function() {
                    clearInterval(demo_video_timer);
                    fireGoogleAnalyticsEvent('Video', 'Play', 'Assurance Demo', demo_video_time_watched);
                });
            } else if ($('body').hasClass('forgotten-password')) {
                $('form#forgotten-password').on('submit', function(event) {
                    var this_form = $(this);
                    if (this_form.find('input[type="email"]').val().trim() == '' || !basic.validateEmail(this_form.find('input[type="email"]').val().trim())) {
                        basic.showAlert('Please try again with valid email.', '', true);
                        event.preventDefault();
                    }
                });
            } else if ($('body').hasClass('password-recover')) {
                $('form#recover-password').on('submit', function(event) {
                    var this_form = $(this);
                    if (this_form.find('input[type="password"]').val().trim() == '' || this_form.find('input[type="password"]').val().trim().length < 8 || this_form.find('input[type="email"]').val().trim().length > 100) {
                        basic.showAlert('Please try again with valid password between 8 and 30 symbols.', '', true);
                        event.preventDefault();
                    }
                });
            }
        },
        onDocumentReady: async function() {
            console.log('onDocumentReady');
            if ($('body').hasClass('logged-in')) {
                if ($('body').hasClass('home') || $('body').hasClass('patient-access')) {
                    // applied for both dentist and patient sides homepages to make contacts in list (slider) with same hight
                    makeElementsInContractListWithSameHeight();

                    // if patient homepage set watcher for new incoming pending contracs
                    if ($('body').hasClass('patient-access')) {
                        if ($('.have-contracts').length) {
                            var now_timestamp = Math.round((new Date()).getTime() / 1000);
                            var foundContracts = [];
                            setInterval(function() {
                                console.log(foundContracts, 'foundContracts');
                                $.ajax({
                                    type: 'POST',
                                    url: '/patient/check-for-incoming-pending-contracts',
                                    dataType: 'json',
                                    data: {
                                        now_timestamp: now_timestamp
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: async function (response) {
                                        if(response.success) {
                                            for (var i = 0, len = response.data.length; i < len; i+=1) {
                                                if(foundContracts.indexOf(response.data[i].slug) == -1) {
                                                    foundContracts.push(response.data[i].slug);

                                                    $('.contracts-list.slider').slick('unslick');
                                                    $('.contracts-list.slider').prepend('<a href="'+response.data[i].url+'" class="module contract-tile padding-bottom-10 pending"><div class="tile-wrapper fs-0"> <div class="inline-block-top figure-container"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Dentist avatar" src="'+response.data[i].dentistAvatar+'"><figcaption class="fs-14 calibri-light text-center padding-left-5 padding-right-5">'+response.data[i].status+'</figcaption></figure></div><div class="contract-info inline-block-top"><div class="calibri-bold fs-18 title">Dr. '+response.data[i].dentistName+'</div><time class="display-block fs-14 calibri-light">Sent on: '+response.data[i].createdAt+'</time><div class="lato-semibold fs-24 line-height-24">'+response.data[i].monthlyPremium+'$</div><div class="btn-container"><div class="white-blue-green-btn">'+response.data[i].btnLabel+'</div></div></div></div></a>', 0);
                                                    initSliders();
                                                }
                                            }
                                        }
                                    }
                                });
                            }, 5000);
                        } else if ($('.start-first-contract').length) {
                            var contractFound = false;
                            setInterval(function() {
                                if (!contractFound) {
                                    $.ajax({
                                        type: 'POST',
                                        url: '/patient/check-contracts-count',
                                        dataType: 'json',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function (response) {
                                            console.log(response, 'response');
                                            if (response.success) {
                                                contractFound = true;
                                                window.location.reload();
                                            }
                                        }
                                    });
                                }
                            }, 5000);
                        }
                    }

                    $(window).on('resize', function() {
                        makeElementsInContractListWithSameHeight();
                    });
                } else if ($('body').hasClass('my-contracts') || $('.my-contracts-container').length) {
                    // applied for both dentist and patient
                    initDataTable();

                    var table_trs_with_timestamp = $('.table-container table tr[data-timestamp-signed]');
                    var period_to_withdraw = parseInt(await dApp.assurance_state_methods.getPeriodToWithdraw());
                    var now_timestamp = Math.round((new Date()).getTime() / 1000);

                    for(var i = 0, len = table_trs_with_timestamp.length; i < len; i+=1) {
                        var time_passed_since_signed = now_timestamp - parseInt(table_trs_with_timestamp.eq(i).attr('data-timestamp-signed'));
                        var next_payment_timestamp;
                        var next_payment_timestamp_date_obj;
                        if (time_passed_since_signed > period_to_withdraw) {
                            var remainder = time_passed_since_signed % period_to_withdraw;
                            next_payment_timestamp = (now_timestamp + period_to_withdraw - remainder) * 1000;
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                        } else {
                            next_payment_timestamp = (now_timestamp + period_to_withdraw - time_passed_since_signed) * 1000;
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                        }

                        if (!isNaN(next_payment_timestamp_date_obj)) {
                            table_trs_with_timestamp.eq(i).find('.next-payment').html('<span class="hide-this">'+next_payment_timestamp+'</span>' + projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj));
                        }
                    }
                } else if ($('body').hasClass('dentist-side')) {
                    if ($('body').hasClass('dentist-contract-view')) {
                        console.log('dentist-contract-view');
                        return false;
                        // dentist side
                        cancelContractEventInit();

                        if ($('.terms-and-conditions-long-list').length) {
                            $('.terms-and-conditions-long-list').mCustomScrollbar();
                        }

                        if ($('.open-contract-details').length) {
                            $('.open-contract-details').on('click', function () {
                                if ($(this).attr('data-hidden-details') == 'true') {
                                    $(this).attr('data-hidden-details', 'false');
                                    $(this).html($(this).attr('data-label-opened'));
                                } else {
                                    $(this).attr('data-hidden-details', 'true');
                                    $(this).html($(this).attr('data-label-closed'));
                                }

                                $('.contract-details-container').toggle(300);
                            });
                        }

                        projectData.initiators.initTooltips();

                        if ($('.single-contract-view-section').hasClass('pending')) {
                            initPopupEvents();
                        }
                    }
                } else if ($('body').hasClass('patient-side')) {
                    if ($('body').hasClass('contract-proposal')) {
                        // patient side
                        /*if ($('.contract-proposal.section').length && $('.contract-proposal.section').attr('data-created-at-timestamp') != undefined) {
                            var date_obj = new Date((parseInt($('.contract-proposal.section').attr('data-created-at-timestamp')) + parseInt(await dApp.assurance_state_methods.getPeriodToWithdraw())) * 1000);
                            $('.active-until').html(projectData.utils.dateObjToFormattedDate(date_obj));
                        }*/

                        if ($('.init-address-suggester').length) {
                            console.log('load address-combined-login');
                            await $.getScript('https://dentacoin.com/assets/js/address-combined-login.js?v='+new Date().getTime(), function() {});
                        }

                        if ($('.terms-and-conditions-long-list').length) {
                            $('.terms-and-conditions-long-list').mCustomScrollbar();
                        }

                        multipleUseWalletAddressesLogic();

                        if ($('.single-row.proof-of-address').length) {
                            bindVerifyAddressLogic();
                        }

                        initSignaturePad();

                        if ($('.contract-proposal.section .contact-your-dentist').length) {
                            $('.contact-your-dentist').click(function() {
                                basic.closeDialog();

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

                                            $('.bootbox.reconsider-monthly-premium #new-usd-proposal-to-dentist').focus();

                                            $('.bootbox.reconsider-monthly-premium form#submit-reconsider-monthly-premium').on('submit', function(event) {
                                                event.preventDefault();

                                                var this_form = $(this);
                                                if (this_form.find('#new-usd-proposal-to-dentist').val().trim() == '' || parseFloat(this_form.find('#new-usd-proposal-to-dentist').val().trim()) <= 0) {
                                                    basic.showAlert('Please enter valid monthly premium proposal', '', true);
                                                } else {
                                                    showLoader();

                                                    fireGoogleAnalyticsEvent('Contract Patient', 'Suggest', 'Contact New Premium', this_form.find('#new-usd-proposal-to-dentist').val().trim());

                                                    $.ajax({
                                                        type: 'POST',
                                                        url: '/patient/submit-reconsider-monthly-premium',
                                                        dataType: 'json',
                                                        data: {
                                                            serialized: this_form.serialize()
                                                        },
                                                        headers: {
                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                        },
                                                        success: function (response) {
                                                            basic.closeDialog();
                                                            hideLoader();
                                                            if (response.success) {
                                                                basic.showDialog(response.success, '', '', true);
                                                            } else if (response.error) {
                                                                basic.showAlert(response.error, '', true);
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        } else if (response.error) {
                                            basic.showAlert(response.success, '', true);
                                        }
                                    }
                                });
                            });
                        }

                        if ($('form#patient-update-and-sign-contract').length) {
                            cancelContractEventInit();

                            $('form#patient-update-and-sign-contract').on('submit', async function(event) {
                                event.preventDefault();
                                var this_form_plain = this;
                                var this_form = $(this);
                                var fields = this_form.find('.required-field');
                                var form_errors = false;

                                if ($('.contract-proposal.section.module').attr('data-expired') != undefined) {
                                    basic.showAlert('This contract proposal has expired.', '', true);
                                    return false;
                                }

                                //clear previous submits errors
                                this_form.find('.error-in-label').remove();
                                this_form.find('.single-row').removeClass('row-with-error');
                                fields.removeClass('with-error');

                                //checking the validation for the patient fields
                                for(var i = 0, len = fields.length; i < len; i+=1) {
                                    if (fields.eq(i).is('select')) {
                                        if (fields.eq(i).val() == null) {
                                            customCreateContractErrorHandle(fields.eq(i), 'Required field cannot be left blank.');
                                            form_errors = true;
                                        }
                                    } else if (fields.eq(i).is('input')) {
                                        if (fields.eq(i).val().trim() == '') {
                                            customCreateContractErrorHandle(fields.eq(i), 'Required field cannot be left blank.');
                                            form_errors = true;
                                        } else if (fields.eq(i).is('[name="dcn_address"]') && !projectData.utils.innerAddressCheck(fields.eq(i).val().trim())) {
                                            customCreateContractErrorHandle(fields.eq(i), 'Please enter valid Wallet Address.');
                                            if ($('.proof-of-address').length) {
                                                $('.proof-of-address').remove();
                                            }
                                            form_errors = true;
                                        }
                                    }
                                }

                                if (!form_errors) {
                                    var validate_patient_address = false;
                                    var patient_address;
                                    if ($('.dcn-address-row #dcn_address').is('input')) {
                                        patient_address = $('.dcn-address-row #dcn_address').val().trim();
                                    } else {
                                        patient_address = $('.dcn-address-row #dcn_address').html().trim();
                                    }

                                    if (projectData.utils.innerAddressCheck(patient_address)) {
                                        //method for first step validating the patient address
                                        validate_patient_address = await validateUserAddress(patient_address, $('.dcn-address-row #dcn_address'));
                                    }

                                    if (validate_patient_address) {
                                        form_errors = true;
                                    }
                                }

                                if ($('.proof-of-address').length && form_errors) {
                                    $('html, body').animate({scrollTop: $('.proof-of-address').offset().top - 50}, 500);
                                } else if (form_errors) {
                                    $('html, body').animate({scrollTop: $('.required-field.with-error').offset().top - 50}, 500);
                                } else {
                                    //check if patient signed if privacy policy and terms checkboxes are checked
                                    //save the base64 signature image in hidden value
                                    this_form.find('input[name="patient_signature"]').val(signature_pad.toDataURL('image/png'));
                                    if (signature_pad.isEmpty()) {
                                        basic.showAlert('Please sign the contract sample. Use your mouse or touch screen to sign.', '', true);
                                    }else if (!this_form.find('input#terms').is(':checked')) {
                                        basic.showAlert('Please accept the Terms and Conditions', '', true);
                                    }else if (!this_form.find('input#privacy-policy').is(':checked')) {
                                        basic.showAlert('Please accept the Privacy Policy', '', true);
                                    }else {
                                        fireGoogleAnalyticsEvent('Contract Patient Accepted', 'Accept', 'Contact Accepted');

                                        showLoader('Please wait ...<br>This may take a few minutes.', 'contract-response-success-layer');
                                        this_form_plain.submit();
                                    }
                                }
                            });
                        }
                    } else if ($('body').hasClass('patient-contract-view')) {
                        if ($('.terms-and-conditions-long-list').length) {
                            $('.terms-and-conditions-long-list').mCustomScrollbar();
                        }

                        if ($('.open-contract-details').length) {
                            $('.open-contract-details').on('click', function() {
                                if ($(this).attr('data-hidden-details') == 'true') {
                                    $(this).attr('data-hidden-details', 'false');
                                    $(this).html($(this).attr('data-label-opened'));
                                } else {
                                    $(this).attr('data-hidden-details', 'true');
                                    $(this).html($(this).attr('data-label-closed'));
                                }

                                $('.contract-details-container').toggle(300);
                            });
                        }

                        if ($('.contract-response-message').length) {
                            $('.contract-response-message .close-btn').click(function() {
                                $(this).closest('.contract-response-message').remove();
                            });
                        }

                        projectData.initiators.initTooltips();
                    }
                }
            }
        },
        onContractInit: async function() {
            if ($('body').hasClass('logged-in')) {
                var now_timestamp = Math.round((new Date()).getTime() / 1000);
                if ($('body').hasClass('patient-contract-view')) {
                    var period_to_withdraw = parseInt(await dApp.assurance_state_methods.getPeriodToWithdraw());
                    if ($('.contract-header').hasClass('awaiting-payment') || $('.contract-header').hasClass('awaiting-approval')) {
                        var time_passed_since_signed = now_timestamp - parseInt($('.patient-contract-single-page-section').attr('data-date-start-contract'));
                        var next_payment_timestamp_date_obj;
                        var next_payment_timestamp_unix;
                        var next_payment_timestamp;
                        var current_patient_dcn_balance = parseInt(await dApp.dentacoin_token_methods.balanceOf($('.patient-contract-single-page-section').attr('data-patient')));

                        var nextWithdrawTimestamp = 0;
                        var dcn_needed_to_be_payed_to_dentist = 0;
                        if ($('.contract-header').hasClass('awaiting-payment')) {
                            // reading the monthly premium from DB, because contract is not yet created on the blockchain
                            dcn_needed_to_be_payed_to_dentist = parseInt($('.patient-contract-single-page-section').attr('data-monthly-premium'));

                            nextWithdrawTimestamp = parseInt($('.patient-contract-single-page-section').attr('data-date-start-contract')) + period_to_withdraw;

                            trackForContractStatusChange($('.patient-contract-single-page-section').attr('data-contract'), 'awaiting-payment');
                        } else if ($('.contract-header').hasClass('awaiting-approval')) {
                            var on_load_exiting_contract = await dApp.assurance_state_methods.getPatient($('.patient-contract-single-page-section').attr('data-patient'), $('.patient-contract-single-page-section').attr('data-dentist'));

                            // reading the monthly premium from the smart contract
                            dcn_needed_to_be_payed_to_dentist = parseInt(on_load_exiting_contract[5]);

                            nextWithdrawTimestamp = parseInt(on_load_exiting_contract[0]);

                            trackForContractStatusChange($('.patient-contract-single-page-section').attr('data-contract'), 'awaiting-approval');
                        }

                        var timer_label = '';
                        if (time_passed_since_signed > period_to_withdraw && current_patient_dcn_balance < dcn_needed_to_be_payed_to_dentist && dApp.grace_period > time_passed_since_signed % period_to_withdraw) {
                            next_payment_timestamp = (nextWithdrawTimestamp + dApp.grace_period) * 1000;
                            console.log(now_timestamp, 'now_timestamp');
                            console.log(next_payment_timestamp, 'next_payment_timestamp');
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                            next_payment_timestamp_unix = (nextWithdrawTimestamp + dApp.grace_period - now_timestamp);

                            timer_label = 'Overdue payment. If you doesn\'t fill in '+projectData.utils.convertUsdToDcn(dcn_needed_to_be_payed_to_dentist)+' Dentacoins inside your  Wallet Address the contract will be canceled in:';
                            $('.clock').addClass('red-background');
                        } else if (time_passed_since_signed > period_to_withdraw) {
                            var remainder = time_passed_since_signed % period_to_withdraw;
                            next_payment_timestamp_unix = period_to_withdraw - remainder;
                            next_payment_timestamp = (next_payment_timestamp_unix + now_timestamp) * 1000;
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                            if (current_patient_dcn_balance < dcn_needed_to_be_payed_to_dentist) {
                                timer_label = 'Fund your account until:';
                            } else {
                                timer_label = 'Next payment processed in:';

                                if ($('.contract-header').hasClass('awaiting-approval')) {
                                    $('.show-on-having-dentacoins').removeClass('hide');
                                }
                            }
                        } else {
                            next_payment_timestamp_unix = period_to_withdraw - time_passed_since_signed;
                            next_payment_timestamp = (next_payment_timestamp_unix + now_timestamp) * 1000;
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                            if (current_patient_dcn_balance < dcn_needed_to_be_payed_to_dentist) {
                                timer_label = 'Fund your account until:';
                            } else {
                                timer_label = 'Next payment processed in:';

                                if ($('.contract-header').hasClass('awaiting-approval')) {
                                    $('.show-on-having-dentacoins').removeClass('hide');
                                }
                            }
                        }

                        $('.timer-label').html(timer_label);
                        projectData.initiators.initFlipClockTimer(next_payment_timestamp_unix);

                        cancelContractEventInit();
                    }

                    if ($('.contract-header').hasClass('active')) {
                        trackForContractStatusChange($('.patient-contract-single-page-section').attr('data-contract'), 'active');

                        var next_payment_timestamp_date_obj;
                        var next_payment_timestamp_unix;
                        var next_payment_timestamp;
                        var on_load_exiting_contract = await dApp.assurance_state_methods.getPatient($('.patient-contract-single-page-section').attr('data-patient'), $('.patient-contract-single-page-section').attr('data-dentist'));
                        var current_patient_dcn_balance = parseInt(await dApp.dentacoin_token_methods.balanceOf($('.patient-contract-single-page-section').attr('data-patient')));

                        var months_passed_for_reward = 1;
                        var contract_next_payment = parseInt(on_load_exiting_contract[0]);
                        var time_passed_since_signed = now_timestamp - contract_next_payment;

                        if (Math.floor(time_passed_since_signed / period_to_withdraw) >= 0) {
                            months_passed_for_reward += Math.floor(time_passed_since_signed / period_to_withdraw);
                        }
                        var dcn_needed_to_be_payed_to_dentist = months_passed_for_reward * parseInt(on_load_exiting_contract[5]);

                        var timer_label = '';
                        if (time_passed_since_signed > period_to_withdraw && months_passed_for_reward == 1 && current_patient_dcn_balance < dcn_needed_to_be_payed_to_dentist && dApp.grace_period > time_passed_since_signed % period_to_withdraw) {
                            next_payment_timestamp = (parseInt(on_load_exiting_contract[0]) + dApp.grace_period - now_timestamp) * 1000;
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                            next_payment_timestamp_unix = (parseInt(on_load_exiting_contract[0]) + dApp.grace_period - now_timestamp);

                            timer_label = 'Overdue payment. If you doesn\'t fill in '+projectData.utils.convertUsdToDcn(dcn_needed_to_be_payed_to_dentist)+' Dentacoins inside your  Wallet Address the contract will be canceled in:';
                            $('.clock').addClass('red-background');
                        } else if (time_passed_since_signed > period_to_withdraw) {
                            var remainder = time_passed_since_signed % period_to_withdraw;
                            next_payment_timestamp_unix = period_to_withdraw - remainder;
                            next_payment_timestamp = (next_payment_timestamp_unix + now_timestamp) * 1000;
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                            if (current_patient_dcn_balance < parseInt(on_load_exiting_contract[5])) {
                                timer_label = 'Fund your account until:';
                            } else {
                                timer_label = 'Next payment processed in:';
                            }
                        } else {
                            if (time_passed_since_signed >= 0) {
                                next_payment_timestamp_unix = period_to_withdraw - time_passed_since_signed;
                            } else {
                                next_payment_timestamp_unix = parseInt(on_load_exiting_contract[0]) - now_timestamp;
                            }
                            next_payment_timestamp = (next_payment_timestamp_unix + now_timestamp) * 1000;
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                            if (current_patient_dcn_balance < parseInt(on_load_exiting_contract[5])) {
                                timer_label = 'Fund your account until:';
                            } else {
                                timer_label = 'Next payment processed in:';
                            }
                        }

                        $('.timer-label').html(timer_label);
                        projectData.initiators.initFlipClockTimer(next_payment_timestamp_unix);

                        cancelContractEventInit();

                        patientDcnBalanceLogic(current_patient_dcn_balance);
                        function patientDcnBalanceLogic(current_patient_dcn_balance) {
                            $('.camping-for-popups').html('');
                            if (current_patient_dcn_balance > dcn_needed_to_be_payed_to_dentist) {
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img alt="Check inside shield" src="/assets/uploads/shield-check.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-20 padding-top-15">ALL SET FOR YOUR NEXT PAYMENT</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-10 padding-bottom-25">It seems you have the needed amount of DCN and ETH in your wallet so your dentist will be able to successfully process your next monthly payment on '+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 second-custom-close-btn">SOUNDS GOOD</a></div></div></div>');
                                initPopupEvents();
                            } else {
                                //showing section where ETH and DCN can be bough when doesnt have enough DCN
                                $('.external-api-crypto-provider').removeClass('hide');

                                //not enough DCN
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Fund icon" src="/assets/uploads/fund-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">Time to fund your account now!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">You should fund your account with DCN equivalent to <span class="calibri-bold blue-green-color">'+(months_passed_for_reward * on_load_exiting_contract[4])+' USD</span> (at the moment: <span class="calibri-bold blue-green-color">'+dcn_needed_to_be_payed_to_dentist+' DCN</span>) before <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 scroll-to-buy-section">FUND NOW</a></div></div></div>');

                                initPopupEvents(true);

                                // checking every 3 seconds if user deposited dentacoins
                                setTimeout(async function() {
                                    patientDcnBalanceLogic(parseInt(await dApp.dentacoin_token_methods.balanceOf($('.patient-contract-single-page-section').attr('data-patient'))), dcn_needed_to_be_payed_to_dentist);
                                }, 3000);
                            }
                        }

                        if ($('.record-check-up').length) {
                            $('.record-check-up').click(function() {
                                initRecordCheckUpLogic();
                            });
                        }

                        function initRecordCheckUpLogic() {
                            $('.camping-for-popups').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img alt="Check up" src="/assets/uploads/check-up.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-20 padding-top-15">TIME FOR DENTAL CHECK-UP</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-10 padding-bottom-25">Your dentist recommended you to visit them <span class="blue-green-color calibri-bold">'+$('.patient-contract-single-page-section').attr('data-checkups')+' times per year</span> for a check-up. Did you have your teeth examined already?</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 record-check-up-action">YES, I\'VE BEEN THERE</a></div></div></div>');
                            scrollToPopupsCamper();

                            $('.record-check-up-action').click(function() {
                                $('.camping-for-popups').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img alt="Check up" src="/assets/uploads/check-up.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-20 padding-top-15">WHEN DID YOU HAVE YOUR CHECK-UP?</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-20 padding-bottom-25"><input type="text" class="custom-input max-width-300 margin-0-auto datepicker"/></div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 record-check-up-submit">SUBMIT</a></div></div></div>');
                                projectData.initiators.initDatepicker();

                                $('.camping-for-popups .datepicker').focus();

                                var sentRecord = false;
                                $('.record-check-up-submit').click(function() {
                                    if ($('.camping-for-popups .datepicker').val() != '') {
                                        if (!sentRecord) {
                                            sentRecord = true;
                                            showLoader();

                                            setTimeout(function() {
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '/patient/record-check-up-or-teeth-cleaning',
                                                    dataType: 'json',
                                                    data: {
                                                        type: 'check-up',
                                                        contract: $('.patient-contract-single-page-section').attr('data-contract'),
                                                        date: $('.camping-for-popups .datepicker').val().trim()
                                                    },
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    },
                                                    success: function (response) {
                                                        hideLoader();
                                                        if (response.success) {
                                                            $('.camping-for-popups').html('');
                                                            basic.showAlert('Check-up recorded successfully. Now your dentist has to approve it.', '', true);
                                                        } else if (response.error) {
                                                            sentRecord = false;
                                                            basic.showAlert(response.message, '', true);
                                                        }
                                                    }
                                                });
                                            }, 2000);
                                        }
                                    } else {
                                        basic.showAlert('Please select valid date.', '', true);
                                    }
                                });
                            });
                        }

                        if ($('.record-teeth-cleaning').length) {
                            $('.record-teeth-cleaning').click(function() {
                                initRecordTeethCleaningLogic();
                            });
                        }

                        function initRecordTeethCleaningLogic() {
                            $('.camping-for-popups').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img alt="Teeth cleaning" src="/assets/uploads/teeth-cleaning.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-20 padding-top-15">TIME FOR TEETH CLEANING</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-10 padding-bottom-25">Your dentist recommended you to visit them <span class="blue-green-color calibri-bold">'+$('.patient-contract-single-page-section').attr('data-teeth-cleanings')+' times per year</span> for teeth cleaning. Did you have your teeth cleaned already?</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 record-teeth-cleaning-action">YES, I\'VE BEEN THERE</a></div></div></div>');
                            scrollToPopupsCamper();

                            $('.record-teeth-cleaning-action').click(function() {
                                $('.camping-for-popups').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img alt="Teeth cleaning" src="/assets/uploads/teeth-cleaning.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-20 padding-top-15">WHEN DID YOU HAVE YOUR TEETH CLEANING?</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-20 padding-bottom-25"><input type="text" class="custom-input max-width-300 margin-0-auto datepicker"/></div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 record-check-up-submit">SUBMIT</a></div></div></div>');
                                projectData.initiators.initDatepicker();

                                $('.camping-for-popups .datepicker').focus();

                                var sentRecord = false;
                                $('.record-check-up-submit').click(function() {
                                    if ($('.camping-for-popups .datepicker').val() != '') {
                                        if (!sentRecord) {
                                            sentRecord = true;
                                            showLoader();

                                            setTimeout(function() {
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '/patient/record-check-up-or-teeth-cleaning',
                                                    dataType: 'json',
                                                    data: {
                                                        type: 'teeth-cleaning',
                                                        contract: $('.patient-contract-single-page-section').attr('data-contract'),
                                                        date: $('.camping-for-popups .datepicker').val().trim()
                                                    },
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    },
                                                    success: function (response) {
                                                        hideLoader();
                                                        if (response.success) {
                                                            $('.camping-for-popups').html('');
                                                            basic.showAlert('Teeth cleaning recorded successfully. Now your dentist have to approve it.', '', true);
                                                        } else if (response.error) {
                                                            sentRecord = false;
                                                            basic.showAlert(response.message, '', true);
                                                        }
                                                    }
                                                });
                                            }, 2000);
                                        }
                                    } else {
                                        basic.showAlert('Please select valid date.', '', true);
                                    }
                                });
                            });
                        }

                        function initCheckUpAndRecordTeethCleaningLogic() {
                            $('.camping-for-popups').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20 margin-right-10 inline-block"><img alt="Check up" src="/assets/uploads/check-up.svg" class="max-width-70 width-100"/></figure><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20 inline-block"><img alt="Teeth cleaning" src="/assets/uploads/teeth-cleaning.svg" class="max-width-70 width-100"/></figure><h2 class="lato-bold fs-20 padding-top-15">TIME FOR CHECK-UP & TEETH CLEANING</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-10 padding-bottom-25">Your dentist recommended you to visit them '+$('.patient-contract-single-page-section').attr('data-checkups')+' times per year for a check-up and '+$('.patient-contract-single-page-section').attr('data-teeth-cleanings')+' times per year for a teeth cleaning. Did you have your teeth examined and cleaned already?</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 record-teeth-cleaning-action">YES, I\'VE BEEN THERE</a></div></div></div>');
                            scrollToPopupsCamper();

                            $('.record-teeth-cleaning-action').click(function() {
                                $('.camping-for-popups').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20 margin-right-10 inline-block"><img alt="Check up" src="/assets/uploads/check-up.svg" class="max-width-70 width-100"/></figure><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20 inline-block"><img alt="Teeth cleaning" src="/assets/uploads/teeth-cleaning.svg" class="max-width-70 width-100"/></figure><h2 class="lato-bold fs-20 padding-top-15">WHEN DID YOU HAVE YOUR CHECK-UP AND TEETH CLEANING?</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-20 padding-bottom-25"><label class="display-block fs-16 lato-semibold max-width-300 margin-0-auto text-left padding-bottom-5">Check-up Date:</label><input type="text" class="custom-input max-width-300 margin-0-auto check-up-datepicker datepicker"/></div><div class="fs-18 fs-xs-16 calibri-light padding-bottom-25"><label class="display-block fs-16 lato-semibold max-width-300 margin-0-auto text-left padding-bottom-5">Teeth Cleaning Date:</label><input type="text" class="custom-input max-width-300 margin-0-auto teeth-cleaning-datepicker datepicker"/></div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 record-check-up-and-teeth-cleaning-submit">SUBMIT</a></div></div></div>');
                                projectData.initiators.initDatepicker();

                                $('.camping-for-popups .check-up-datepicker').focus();

                                var sentRecord = false;
                                $('.record-check-up-and-teeth-cleaning-submit').click(function() {
                                    if ($('.camping-for-popups .check-up-datepicker').val() != '' || $('.camping-for-popups .teeth-cleaning-datepicker').val() != '') {
                                        if (!sentRecord) {
                                            sentRecord = true;
                                            showLoader();

                                            setTimeout(function() {
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '/patient/record-check-up-or-teeth-cleaning',
                                                    dataType: 'json',
                                                    data: {
                                                        type: 'check-up-and-teeth-cleaning',
                                                        contract: $('.patient-contract-single-page-section').attr('data-contract'),
                                                        check_up_date: $('.camping-for-popups .check-up-datepicker').val().trim(),
                                                        teeth_cleaning_date: $('.camping-for-popups .teeth-cleaning-datepicker').val().trim()
                                                    },
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    },
                                                    success: function (response) {
                                                        hideLoader();
                                                        if (response.success) {
                                                            $('.camping-for-popups').html('');
                                                            basic.showAlert('Record is saved successfully. Now your dentist have to approve it.', '', true);
                                                        } else if (response.error) {
                                                            sentRecord = false;
                                                            basic.showAlert(response.message, '', true);
                                                        }
                                                    }
                                                });
                                            }, 2000);
                                        }
                                    } else {
                                        basic.showAlert('Please select at least one valid date.', '', true);
                                    }
                                });
                            });
                        }

                        var get_params = projectData.utils.getGETParameters();
                        if (basic.property_exists(get_params, 'type')) {
                            if (get_params.type == 'check-up') {
                                $('.camping-for-popups').addClass('check-up');
                            } else if (get_params.type == 'teeth-cleaning') {
                                $('.camping-for-popups').addClass('teeth-cleaning');
                            } else if (get_params.type == 'check-up-and-teeth-cleaning') {
                                $('.camping-for-popups').addClass('check-up teeth-cleaning');
                            }
                        }

                        if ($('.camping-for-popups').hasClass('check-up') && $('.camping-for-popups').hasClass('teeth-cleaning')) {
                            initCheckUpAndRecordTeethCleaningLogic();
                        } else if ($('.camping-for-popups').hasClass('check-up')) {
                            initRecordCheckUpLogic();
                        } else if ($('.camping-for-popups').hasClass('teeth-cleaning')) {
                            initRecordTeethCleaningLogic();
                        }

                        function scrollToPopupsCamper() {
                            $('html, body').animate({
                                scrollTop: $('.camping-for-popups').offset().top - 100
                            }, {
                                duration: 500
                            });
                        }
                    } else if ($('.contract-header').hasClass('awaiting-approval')) {
                        var current_user_dcn_balance = parseInt(await dApp.dentacoin_token_methods.balanceOf($('.patient-contract-single-page-section').attr('data-patient')));
                        var on_load_exiting_contract = await dApp.assurance_state_methods.getPatient($('.patient-contract-single-page-section').attr('data-patient'), $('.patient-contract-single-page-section').attr('data-dentist'));
                        var monthly_premium_in_usd = parseInt(on_load_exiting_contract[4]);
                        var monthly_premium_in_dcn = parseInt(on_load_exiting_contract[5]);

                        patientWaitingForDentistApprovalLogic(current_user_dcn_balance);
                        function patientWaitingForDentistApprovalLogic(current_user_dcn_balance) {

                            $('.camping-for-popups').html('');
                            if (current_user_dcn_balance < monthly_premium_in_dcn) {
                                // checking every 3 seconds if user deposited BACK dcn
                                setTimeout(async function() {
                                    patientWaitingForDentistApprovalLogic(parseInt(await dApp.dentacoin_token_methods.balanceOf($('.patient-contract-single-page-section').attr('data-patient'))));
                                }, 3000);

                                //not enough DCN
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Fund icon" src="/assets/uploads/fund-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">Time to fund your account now!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">You should fund your account with DCN equivalent to <span class="calibri-bold blue-green-color">'+monthly_premium_in_usd+' USD</span> (at the moment: <span class="calibri-bold blue-green-color">'+monthly_premium_in_dcn+' DCN</span>) before <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 scroll-to-buy-section">FUND NOW</a></div></div></div>');
                                initPopupEvents(true);

                                $('.external-api-crypto-provider').removeClass('hide');
                            }``
                        }
                    } else if ($('.contract-header').hasClass('awaiting-payment')) {
                        var current_user_dcn_balance = parseInt(await dApp.dentacoin_token_methods.balanceOf(global_state.account));
                        console.log(current_user_dcn_balance, 'current_user_dcn_balance');
                        var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));
                        console.log(current_user_eth_balance, 'current_user_eth_balance');
                        var monthly_premium_in_dcn = Math.floor(projectData.utils.convertUsdToDcn(parseInt($('.patient-contract-single-page-section').attr('data-monthly-premium'))));
                        console.log(monthly_premium_in_dcn, 'monthly_premium_in_dcn');
                        var ethgasstation_json = await $.getJSON('https://ethgasstation.info/json/ethgasAPI.json');
                        const on_page_load_gwei = ethgasstation_json.safeLow;
                        //adding 10% just in case the transaction dont fail
                        const on_page_load_gas_price = on_page_load_gwei * 100000000 + ((on_page_load_gwei * 100000000) * projectData.variables.bonusPercentagesToGasEstimations / 100);

                        var approval_given = false;
                        //if approval is given already SOMEHOW ...
                        if (parseInt(await dApp.dentacoin_token_methods.allowance(projectData.utils.checksumAddress($('.patient-contract-single-page-section').attr('data-patient')), assurance_config.assurance_state_address)) > 0) {
                            approval_given = true;
                        }

                        if (!approval_given) {
                            //gas estimation for DentacoinToken approval method
                            var gas_cost_for_approval = await dApp.dentacoin_token_instance.methods.approve(assurance_config.assurance_state_address, assurance_config.dentacoins_to_approve).estimateGas({gas: 500000});
                        }

                        //for the estimation going to use our internal address which aldready did gave before his allowance in DentacoinToken contract. In order to receive the gas estimation we need to pass all the method conditions and requires
                        var gas_cost_for_contract_creation = await dApp.assurance_proxy_instance.methods.registerContract(assurance_config.dummy_address, projectData.utils.checksumAddress($('.patient-contract-single-page-section').attr('data-dentist')), Math.floor($('.patient-contract-single-page-section').attr('data-monthly-premium')), monthly_premium_in_dcn, parseInt($('.patient-contract-single-page-section').attr('data-date-start-contract')) + period_to_withdraw, $('.patient-contract-single-page-section').attr('data-contract-ipfs')).estimateGas({from: assurance_config.dummy_address, gas: 1000000});

                        var methods_gas_cost;
                        if (!approval_given) {
                            methods_gas_cost = gas_cost_for_approval + gas_cost_for_contract_creation;
                        } else {
                            methods_gas_cost = gas_cost_for_contract_creation;
                        }

                        //eth fee for firing blockchain transaction
                        var eth_fee = dApp.web3_1_0.utils.fromWei((methods_gas_cost * on_page_load_gas_price).toString(), 'ether');
                        // asking for 20% to cover gas price jump
                        eth_fee = (parseFloat(eth_fee) + (parseFloat(eth_fee) * 20/100)).toFixed(6);

                        // init navigation steps event
                        $('.steps-navigation a').click(function() {
                            if (!$(this).hasClass('disabled')) {
                                $('.steps-navigation a').removeClass('active');
                                $(this).addClass('active');

                                $('.contract-response-message').hide();
                                $('.contract-response-message.'+$(this).attr('data-step')).fadeIn();
                            }
                        });

                        patientApprovalAndContractCreationLogic(current_user_dcn_balance, current_user_eth_balance);
                        function patientApprovalAndContractCreationLogic(current_user_dcn_balance, current_user_eth_balance) {
                            if (current_user_dcn_balance < monthly_premium_in_dcn && parseFloat(eth_fee) > current_user_eth_balance) {
                                // 1st step
                                $('.camping-for-popups').html('');

                                // checking every 3 seconds if user deposit eth or dcn
                                setTimeout(async function() {
                                    patientApprovalAndContractCreationLogic(parseInt(await dApp.dentacoin_token_methods.balanceOf(global_state.account)), parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account))));
                                }, 3000);

                                //not enough DCN and ETH balance
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Fund icon" src="/assets/uploads/fund-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">Time to fund your account now!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">You should fund your account with DCN equivalent to <span class="calibri-bold blue-green-color">'+$('.patient-contract-single-page-section').attr('data-monthly-premium')+' USD</span> (at the moment: <span class="calibri-bold blue-green-color">'+monthly_premium_in_dcn+' DCN</span>) and <span class="calibri-bold blue-green-color">'+eth_fee+' ETH</span> before <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 scroll-to-buy-section">FUND NOW</a></div></div></div>');
                                initPopupEvents(true);
                            } else if (current_user_dcn_balance < monthly_premium_in_dcn) {
                                // 1st step
                                $('.camping-for-popups').html('');

                                // checking every 3 seconds if user deposit eth or dcn
                                setTimeout(async function() {
                                    patientApprovalAndContractCreationLogic(parseInt(await dApp.dentacoin_token_methods.balanceOf(global_state.account)), parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account))));
                                }, 3000);

                                //not enough DCN
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Fund icon" src="/assets/uploads/fund-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">Time to fund your account now!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">You should fund your account with DCN equivalent to <span class="calibri-bold blue-green-color">'+$('.patient-contract-single-page-section').attr('data-monthly-premium')+' USD</span> (at the moment: <span class="calibri-bold blue-green-color">'+monthly_premium_in_dcn+' DCN</span>) before <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 scroll-to-buy-section">FUND NOW</a></div></div></div>');
                                initPopupEvents(true);
                            } else if (parseFloat(eth_fee) > current_user_eth_balance) {
                                // 1st step
                                $('.camping-for-popups').html('');

                                // checking every 3 seconds if user deposit eth or dcn
                                setTimeout(async function() {
                                    patientApprovalAndContractCreationLogic(parseInt(await dApp.dentacoin_token_methods.balanceOf(global_state.account)), parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account))));
                                }, 3000);

                                //not enough ETH balance
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Fund icon" src="/assets/uploads/fund-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">Time to fund your account now!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">You should fund your account with <span class="calibri-bold blue-green-color">'+eth_fee+' ETH</span> before <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 scroll-to-buy-section">FUND NOW</a></div></div></div>');
                                initPopupEvents(true);
                            } else {
                                // 2nd step
                                $('.camping-for-popups').html('');

                                //hiding section where ETH and DCN can be bought because patient has enough of both
                                if ($('.ready-to-purchase-with-external-api').length) {
                                    $('.ready-to-purchase-with-external-api').remove();
                                }

                                $('.steps-navigation a[data-step="popup-step-one"]').addClass('passed-step');
                                $('.steps-navigation a[data-step="popup-step-two"]').removeClass('disabled');

                                //show CONTINUE TO BLOCKCHAIN BTN
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module popup-step-one"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Shield check" src="/assets/uploads/shield-check.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">is now funded!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">It seems you already have the needed amount of Dentacoin (DCN) in your wallet and you should activate your contract before or on <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 proceed-to-second-step">STEP 2 >></a></div></div></div><div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module popup-step-two"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Automatic payment icon" src="/assets/uploads/automatic-payments-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">MONTHLY AUTOPAYMENTS</h2><h3 class="fs-22 padding-top-5 lato-bold">Get your Assurance contract started!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">Your account is already funded. Last step: Activate your secure, automatic payments now! Due date: <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 call-recipe margin-bottom-10 width-xs-100 max-width-400">ACTIVATE NOW</a></div></div></div>');
                                initPopupEvents();

                                $('.proceed-to-second-step').click(function() {
                                    $('.contract-response-message.popup-step-one').hide();
                                    $('.contract-response-message.popup-step-two').fadeIn();

                                    $('.steps-navigation a[data-step="popup-step-one"]').removeClass('active');
                                    $('.steps-navigation a[data-step="popup-step-two"]').addClass('active');
                                });

                                $('.call-recipe').click(function() {
                                    if (metamask) {
                                        basic.showAlert('Using MetaMask is currently not supported in Dentacoin Assurance. Please switch off MetaMask extension and try again.');
                                    } else {
                                        var existingCachedKey = false;
                                        var existingCachedKeystore = '';
                                        var currentAccountsStorage = localStorage.getItem('current-accounts');
                                        if (currentAccountsStorage != null && currentAccountsStorage != undefined) {
                                            var currentAccounts = JSON.parse(currentAccountsStorage);

                                            for(var i = 0, len = currentAccounts.length; i < len; i+=1) {
                                                if (global_state.account == projectData.utils.checksumAddress(currentAccounts[i].address)) {
                                                    existingCachedKey = true;
                                                    existingCachedKeystore = currentAccounts[i].keystore;
                                                    break;
                                                }
                                            }
                                        }

                                        $.ajax({
                                            type: 'POST',
                                            url: '/get-recipe-popup',
                                            dataType: 'json',
                                            data: {
                                                /*to: assurance_config.assurance_proxy_address,*/
                                                cached_key: existingCachedKey,
                                                contract: $('.init-contract-section').attr('data-contract'),
                                                show_dcn_bar: true,
                                                recipe_title: 'Activate Autopayments',
                                                recipe_subtitle: 'to never worry about a missed due date',
                                                recipe_checkbox_text: 'By clicking on the button below you also agree that from now on your monthly premium amount will be automatically deducted from your wallet balance on the payment due date.',
                                                btn_label: 'CONFIRM',
                                                type: 'qr-scan'
                                            },
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            success: async function (response) {
                                                if (response.success) {
                                                    basic.closeDialog();
                                                    basic.showDialog(response.success, 'recipe-popup', null, true);

                                                    // proceed to dentacoin wallet scanning
                                                    $('.generate-qr-code-for-wallet-scanning').click(async function() {
                                                        if (parseFloat(eth_fee) > parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)))) {
                                                            //not enough ETH balance
                                                            basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. Please refill <a href="//wallet.dentacoin.com/buy" target="_blank">here</a>.</div>', '', true);
                                                        } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                            basic.showAlert('Please check the checkbox below to continue with the QR code generation.', '', true);
                                                        } else {
                                                            showLoader();

                                                            var scanObject = {
                                                                0 : eth_fee,
                                                                1 : dApp.web3_1_0.utils.toHex(on_page_load_gas_price),
                                                                2 : global_state.account,
                                                                3 : $('.patient-contract-single-page-section').attr('data-contract')
                                                            };
                                                            var approvalProperty = false;
                                                            if (!approval_given) {
                                                                approvalProperty = true;
                                                            }

                                                            getContractData($('.init-contract-section').attr('data-contract'), async function(response) {
                                                                if (response.success) {
                                                                    if(approvalProperty) {
                                                                        scanObject[4] = 'approval-creation';
                                                                    } else {
                                                                        scanObject[4] = 'creation';
                                                                    }

                                                                    generateQRCodeForDentacoinWalletScan(JSON.stringify(scanObject));
                                                                } else {
                                                                    basic.showAlert('Something went wrong, please try again later.', '', true);
                                                                    hideLoader();
                                                                }
                                                            });
                                                        }
                                                    });

                                                    $('.recipe-popup .usd_val span').html($('.patient-contract-single-page-section').attr('data-monthly-premium'));
                                                    $('.recipe-popup .dcn_val span').html(monthly_premium_in_dcn);

                                                    $('.recipe-popup .ether-fee .field').html(eth_fee);

                                                    $('.recipe-popup .ether-fee i').popover({
                                                        trigger: 'click',
                                                        html: true
                                                    });

                                                    var transaction_key;
                                                    if (!existingCachedKey) {
                                                        // if the current contract patient address has not been cached
                                                        bindVerifyAddressLogic(true);
                                                        $(document).on('on-transaction-recipe-agree', function(event) {
                                                            transaction_key = event.response_data;
                                                            setTimeout(function() {
                                                                hideLoader();

                                                                $('.proof-of-address').remove();
                                                                $('.proof-success').fadeIn(1500);
                                                            }, 500);
                                                        });
                                                    } else {
                                                        // if the current contract patient address has been cached
                                                        $('.camp-for-keystore-password').html('<div class="lato-regular fs-30 text-center padding-bottom-20 padding-top-15">Enter your keystore secret password</div><div class="padding-bottom-20"><div class="custom-google-label-style module  max-width-280 margin-0-auto" data-input-blue-green-border="true"><label for="keystore-password">Secret password:</label><input type="password" maxlength="30" id="keystore-password" class="full-rounded keystore-password"/></div></div>');
                                                    }

                                                    $('.recipe-popup .execute-transaction .fire-blockchain-transaction').click(async function() {
                                                        var this_btn = $(this);
                                                        if (parseFloat(eth_fee) > current_user_eth_balance) {
                                                            //not enough ETH balance
                                                            basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. Please refill <a href="//wallet.dentacoin.com/buy" target="_blank">here</a>.</div>', '', true);
                                                        } else {
                                                            if (!existingCachedKey && transaction_key == undefined) {
                                                                basic.showAlert('You must first enter your private key or keystore file in order to sign the transaction.', '', true);
                                                                return false;
                                                            } else if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() == '') {
                                                                basic.showAlert('Please enter the secret password for your keystore file.', '', true);
                                                                return false;
                                                            } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                                basic.showAlert('Please check the checkbox below to continue with the transaction creation.', '', true);
                                                                return false;
                                                            } else {
                                                                showLoader('Your transaction is now being sent to the blockchain. It might take some time until it gets approved.');
                                                                const EthereumTx = require('ethereumjs-tx');
                                                                setTimeout(async function() {
                                                                    if (existingCachedKey && existingCachedKeystore != '' && $('.camp-for-keystore-password input[type="password"]').val().trim() != '') {
                                                                        var decrypted_keystore_file_response = decryptKeystore(existingCachedKeystore, $('.camp-for-keystore-password input[type="password"]').val().trim());
                                                                        if (decrypted_keystore_file_response.success) {
                                                                            transaction_key = decrypted_keystore_file_response.to_string;
                                                                        } else if (decrypted_keystore_file_response.error) {
                                                                            hideLoader();
                                                                            basic.showAlert(decrypted_keystore_file_response.message, '', true);
                                                                            return false;
                                                                        }
                                                                    }

                                                                    this_btn.unbind();
                                                                    if (!approval_given) {
                                                                        var approval_function_abi = await dApp.dentacoin_token_instance.methods.approve(assurance_config.assurance_state_address, assurance_config.dentacoins_to_approve).encodeABI();
                                                                        dApp.web3_1_0.eth.getTransactionCount(global_state.account, 'pending', function (err, nonce) {
                                                                            var approval_transaction_obj = {
                                                                                gasLimit: dApp.web3_1_0.utils.toHex(Math.round(gas_cost_for_approval + (gas_cost_for_approval * projectData.variables.bonusPercentagesToGasEstimations / 100))),
                                                                                gasPrice: dApp.web3_1_0.utils.toHex(on_page_load_gas_price),
                                                                                from: global_state.account,
                                                                                nonce: dApp.web3_1_0.utils.toHex(nonce),
                                                                                chainId: dApp.chain_id,
                                                                                data: approval_function_abi,
                                                                                to: assurance_config.dentacoin_token_address
                                                                            };

                                                                            const approval_transaction = new EthereumTx(approval_transaction_obj);
                                                                            //signing the transaction
                                                                            approval_transaction.sign(new Buffer(transaction_key, 'hex'));

                                                                            //sending the transaction
                                                                            dApp.web3_1_0.eth.sendSignedTransaction('0x' + approval_transaction.serialize().toString('hex'), function (err, transactionHash) {
                                                                                fireAssuranceContractCreationTransaction(nonce + 1);
                                                                            });
                                                                        });
                                                                    } else {
                                                                        fireAssuranceContractCreationTransaction();
                                                                    }
                                                                }, 2000);

                                                                async function fireAssuranceContractCreationTransaction(nonce) {
                                                                    if (nonce == undefined) {
                                                                        nonce = await dApp.web3_1_0.eth.getTransactionCount(global_state.account, 'pending');
                                                                    }

                                                                    var contract_creation_function_abi = await dApp.assurance_proxy_instance.methods.registerContract(projectData.utils.checksumAddress(response.contract_data.patient), projectData.utils.checksumAddress(response.contract_data.dentist), Math.floor(response.contract_data.value_usd), monthly_premium_in_dcn, response.contract_data.date_start_contract + period_to_withdraw, response.contract_data.contract_ipfs_hash).encodeABI();

                                                                    var contract_creation_transaction_obj = {
                                                                        gasLimit: dApp.web3_1_0.utils.toHex(Math.round(gas_cost_for_contract_creation + (gas_cost_for_contract_creation * projectData.variables.bonusPercentagesToGasEstimations / 100))),
                                                                        gasPrice: dApp.web3_1_0.utils.toHex(on_page_load_gas_price),
                                                                        from: global_state.account,
                                                                        nonce: dApp.web3_1_0.utils.toHex(nonce),
                                                                        chainId: dApp.chain_id,
                                                                        data: contract_creation_function_abi,
                                                                        to: assurance_config.assurance_proxy_address
                                                                    };

                                                                    const contract_creation_transaction = new EthereumTx(contract_creation_transaction_obj);
                                                                    //signing the transaction
                                                                    contract_creation_transaction.sign(new Buffer(transaction_key, 'hex'));

                                                                    //sending the transaction
                                                                    dApp.web3_1_0.eth.sendSignedTransaction('0x' + contract_creation_transaction.serialize().toString('hex'), function (err, transactionHash) {
                                                                        var execute_ajax = true;
                                                                        //doing setinterval check to check if the smart creation transaction got mined
                                                                        var contract_creation_interval_check = setInterval(async function () {
                                                                            var contract_creation_status = await dApp.web3_1_0.eth.getTransactionReceipt(transactionHash);
                                                                            if (contract_creation_status != null && basic.property_exists(contract_creation_status, 'status')) {
                                                                                clearInterval(contract_creation_interval_check);
                                                                                if (contract_creation_status.status && execute_ajax) {
                                                                                    execute_ajax = false;
                                                                                    $.ajax({
                                                                                        type: 'POST',
                                                                                        url: '/patient/on-blockchain-contract-creation',
                                                                                        dataType: 'json',
                                                                                        data: {
                                                                                            ipfs_hash: response.contract_data.contract_ipfs_hash
                                                                                        },
                                                                                        headers: {
                                                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                                        },
                                                                                        success: function (inner_response) {
                                                                                            if (inner_response.success) {
                                                                                                hideLoader();
                                                                                                basic.showDialog(inner_response.success, '', null, true);
                                                                                                setTimeout(function () {
                                                                                                    window.location.reload();
                                                                                                }, 5000);
                                                                                            }
                                                                                        }
                                                                                    });
                                                                                } else {
                                                                                    hideLoader();
                                                                                    basic.showAlert('Your transaction and blockchain contract creation failed. Please try again later when the gas cost is low or contact <a href="mailto:assurance@dentacoin.com">assurance@dentacoin.com</a>. You can see your transaction on <a href="https://rinkeby.etherscan.io/tx/' + transactionHash + '" target="_blank" class="etherscan-hash">Etherscan</a>');
                                                                                }
                                                                            }
                                                                        }, 3000);
                                                                    });
                                                                }
                                                            }
                                                        }
                                                    });
                                                } else {
                                                    basic.showAlert(response.error, '', true);
                                                }
                                            }
                                        });
                                    }
                                });
                            }
                        }
                    }
                } else if ($('body').hasClass('dentist-contract-view')) {
                    console.log('dentist-contract-view');
                    return false;
                    if ($('.contract-header').hasClass('awaiting-payment')) {
                        trackForContractStatusChange($('.single-contract-view-section').attr('data-contract'), 'awaiting-payment');
                    } else if ($('.contract-header').hasClass('pending')) {
                        trackForContractStatusChange($('.single-contract-view-section').attr('data-contract'), 'pending');
                    } else if ($('.contract-header').hasClass('active')) {
                        trackForContractStatusChange($('.single-contract-view-section').attr('data-contract'), 'active');
                    } else if ($('.contract-header').hasClass('awaiting-approval')) {
                        trackForContractStatusChange($('.single-contract-view-section').attr('data-contract'), 'awaiting-approval');
                    }

                    if ($('.contract-header').hasClass('awaiting-payment') || $('.contract-header').hasClass('awaiting-approval')) {
                        var period_to_withdraw = parseInt(await dApp.assurance_state_methods.getPeriodToWithdraw());
                        var time_passed_since_signed = now_timestamp - parseInt($('.single-contract-view-section').attr('data-date-start-contract'));
                        var next_payment_timestamp_unix;
                        var contractCreationAndPeriodToWithdraw = parseInt($('.single-contract-view-section').attr('data-date-start-contract')) + period_to_withdraw;

                        var timer_label = '';
                        if (time_passed_since_signed > period_to_withdraw) {
                            // running grace period, because patient failed to execude the first payment in time
                            next_payment_timestamp_unix = contractCreationAndPeriodToWithdraw + dApp.grace_period - now_timestamp;

                            timer_label = 'Withdraw payment after (grace period):';
                            $('.clock').addClass('red-background');
                        }  else {
                            // running the period when patient has to execute the first payment
                            next_payment_timestamp_unix = period_to_withdraw - time_passed_since_signed;
                            timer_label = 'Withdraw payment after:';
                        }

                        $('.timer-label').html(timer_label);
                        projectData.initiators.initFlipClockTimer(next_payment_timestamp_unix);

                        $('.first-payment').html(projectData.utils.dateObjToFormattedDate(new Date((parseInt($('.single-contract-view-section').attr('data-created-at')) + parseInt(await dApp.assurance_state_methods.getPeriodToWithdraw())) * 1000)));

                        if ($('.single-contract-view-section').hasClass('awaiting-approval')) {
                            var ethgasstation_json = await $.getJSON('https://ethgasstation.info/json/ethgasAPI.json');
                            const on_page_load_gwei = ethgasstation_json.safeLow;
                            //adding 10% just in case the transaction dont fail
                            const on_page_load_gas_price = on_page_load_gwei * 100000000 + ((on_page_load_gwei * 100000000) * projectData.variables.bonusPercentagesToGasEstimations / 100);

                            //for the estimation going to use our internal address which aldready did gave before his allowance in DentacoinToken contract. In order to receive the gas estimation we need to pass all the method conditions and requires
                            var gas_cost_for_contract_approval = await dApp.assurance_proxy_instance.methods.dentistApproveContract($('.single-contract-view-section').attr('data-patient')).estimateGas({from: global_state.account, gas: 500000});

                            var eth_fee = dApp.web3_1_0.utils.fromWei((gas_cost_for_contract_approval * on_page_load_gas_price).toString(), 'ether');

                            var contract_approval_function_abi = await dApp.assurance_proxy_instance.methods.dentistApproveContract($('.single-contract-view-section').attr('data-patient')).encodeABI();

                            $('.approve-contract-recipe').click(function() {
                                if (metamask) {
                                    basic.showAlert('Using MetaMask is currently not supported in Dentacoin Assurance. Please switch off MetaMask extension and try again.');
                                } else {
                                    //custom
                                    if (!$('#read-the-contract-details').is(':checked')) {
                                        basic.showAlert('Please check the checkbox above to continue with the contract approval.', '', true);
                                        return false;
                                    }

                                    var existingCachedKey = false;
                                    var existingCachedKeystore = '';
                                    var currentAccountsStorage = localStorage.getItem('current-accounts');
                                    if (currentAccountsStorage != null && currentAccountsStorage != undefined) {
                                        var currentAccounts = JSON.parse(currentAccountsStorage);

                                        for(var i = 0, len = currentAccounts.length; i < len; i+=1) {
                                            if (global_state.account == projectData.utils.checksumAddress(currentAccounts[i].address)) {
                                                existingCachedKey = true;
                                                existingCachedKeystore = currentAccounts[i].keystore;
                                                break;
                                            }
                                        }
                                    }

                                    var ajax_data = {
                                        cached_key: existingCachedKey,
                                        contract: $('.init-contract-section').attr('data-contract'),
                                        show_dcn_bar: false,
                                        recipe_title: 'Approve This Contract',
                                        recipe_subtitle: 'and withdraw monthly payments',
                                        recipe_checkbox_text: 'By clicking on the button below you confirm that from now on every month you will withdraw the monthly premium amount on the payment due date or later.',
                                        btn_label: 'APPROVE NOW',
                                        type: 'qr-scan'
                                    };

                                    if ($(this).attr('data-sent-eth-to-dentist') == 'true') {
                                        ajax_data.sent_eth_to_dentist = true;
                                        ajax_data.recipe_checkbox_text = 'To approve this contract and activate it on the blockchain, you need to pay the small fee above only once. We have sent you the needed amount. By clicking on the button below you confirm that from now on every month you will withdraw the monthly premium amount on the payment due date or later.';
                                    }

                                    $.ajax({
                                        type: 'POST',
                                        url: '/get-recipe-popup',
                                        dataType: 'json',
                                        data: ajax_data,
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: async function (response) {
                                            if (response.success) {
                                                basic.closeDialog();
                                                basic.showDialog(response.success, 'recipe-popup', null, true);

                                                // proceed to dentacoin wallet scanning
                                                $('.generate-qr-code-for-wallet-scanning').click(async function() {
                                                    var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));
                                                    if (parseFloat(eth_fee) > current_user_eth_balance) {
                                                        //not enough ETH balance
                                                        basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. Please refill <a href="//wallet.dentacoin.com/buy" target="_blank">here</a>.</div>', '', true);
                                                    } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                        basic.showAlert('Please check the checkbox below to continue with the QR code generation.', '', true);
                                                    } else {
                                                        showLoader();

                                                        var scanObject = {
                                                            0 : eth_fee,
                                                            1 : dApp.web3_1_0.utils.toHex(on_page_load_gas_price),
                                                            2 : global_state.account,
                                                            3 : $('.single-contract-view-section').attr('data-contract'),
                                                            4 : 'dentist-approval'
                                                        };

                                                        generateQRCodeForDentacoinWalletScan(JSON.stringify(scanObject));
                                                    }
                                                });

                                                $('.recipe-popup .ether-fee .field').html(eth_fee);

                                                $('.recipe-popup .ether-fee i').popover({
                                                    trigger: 'click',
                                                    html: true
                                                });

                                                var transaction_key;
                                                if (!existingCachedKey) {
                                                    bindVerifyAddressLogic(true);
                                                    $(document).on('on-transaction-recipe-agree', function(event) {
                                                        transaction_key = event.response_data;
                                                        setTimeout(function() {
                                                            hideLoader();

                                                            $('.proof-of-address').remove();
                                                            $('.proof-success').fadeIn(1500);
                                                        }, 500);
                                                    });
                                                } else {
                                                    $('.camp-for-keystore-password').html('<div class="lato-regular fs-30 text-center padding-bottom-20 padding-top-15">Enter your keystore secret password</div><div class="padding-bottom-20"><div class="custom-google-label-style module max-width-280 margin-0-auto" data-input-blue-green-border="true"><label for="keystore-password">Secret password:</label><input type="password" maxlength="30" id="keystore-password" class="full-rounded keystore-password"/></div></div>');
                                                }

                                                $('.recipe-popup .execute-transaction .fire-blockchain-transaction').click(async function() {
                                                    var this_btn = $(this);
                                                    var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));
                                                    if (parseFloat(eth_fee) > current_user_eth_balance) {
                                                        //not enough ETH balance
                                                        basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. Please refill <a href="//wallet.dentacoin.com/buy" target="_blank">here</a>.</div>', '', true);
                                                    } else {
                                                        if (!existingCachedKey && transaction_key == undefined) {
                                                            basic.showAlert('You must first enter your private key or keystore file in order to sign the transaction.', '', true);
                                                            return false;
                                                        } else if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() == '') {
                                                            basic.showAlert('Please enter the secret password for your keystore file.', '', true);
                                                            return false;
                                                        } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                            basic.showAlert('Please check the checkbox below to continue with the transaction creation.', '', true);
                                                            return false;
                                                        } else {
                                                            showLoader('Your transaction is now being sent to the blockchain. It might take some time until it gets approved.');
                                                            setTimeout(async function() {
                                                                if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() != '') {
                                                                    var decrypted_keystore_file_response = decryptKeystore(existingCachedKeystore, $('.camp-for-keystore-password input[type="password"]').val().trim());
                                                                    if (decrypted_keystore_file_response.success) {
                                                                        transaction_key = decrypted_keystore_file_response.to_string;
                                                                    } else if (decrypted_keystore_file_response.error) {
                                                                        hideLoader();
                                                                        basic.showAlert(decrypted_keystore_file_response.message, '', true);
                                                                        return false;
                                                                    }
                                                                }

                                                                this_btn.unbind();
                                                                const EthereumTx = require('ethereumjs-tx');
                                                                var nonce = await dApp.web3_1_0.eth.getTransactionCount(global_state.account, 'pending');

                                                                var contract_approval_transaction_obj = {
                                                                    gasLimit: dApp.web3_1_0.utils.toHex(Math.round(gas_cost_for_contract_approval + (gas_cost_for_contract_approval * projectData.variables.bonusPercentagesToGasEstimations / 100))),
                                                                    gasPrice: dApp.web3_1_0.utils.toHex(on_page_load_gas_price),
                                                                    from: global_state.account,
                                                                    nonce: dApp.web3_1_0.utils.toHex(nonce),
                                                                    chainId: dApp.chain_id,
                                                                    data: contract_approval_function_abi,
                                                                    to: assurance_config.assurance_proxy_address
                                                                };

                                                                const contract_approval_transaction = new EthereumTx(contract_approval_transaction_obj);
                                                                //signing the transaction
                                                                contract_approval_transaction.sign(new Buffer(transaction_key, 'hex'));

                                                                //sending the transaction
                                                                dApp.web3_1_0.eth.sendSignedTransaction('0x' + contract_approval_transaction.serialize().toString('hex'), function (err, transactionHash) {
                                                                    var execute_ajax = true;
                                                                    //doing setinterval check to check if the smart creation transaction got mined
                                                                    var contract_approval_interval_check = setInterval(async function () {
                                                                        var contract_approval_status = await dApp.web3_1_0.eth.getTransactionReceipt(transactionHash);
                                                                        if (contract_approval_status != null && basic.property_exists(contract_approval_status, 'status')) {
                                                                            if (contract_approval_status.status && execute_ajax) {
                                                                                execute_ajax = false;

                                                                                clearInterval(contract_approval_interval_check);
                                                                                $.ajax({
                                                                                    type: 'POST',
                                                                                    url: '/dentist/on-blockchain-contract-approval',
                                                                                    dataType: 'json',
                                                                                    data: {
                                                                                        ipfs_hash: response.contract_data.contract_ipfs_hash
                                                                                    },
                                                                                    headers: {
                                                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                                    },
                                                                                    success: function (inner_response) {
                                                                                        if (inner_response.success) {
                                                                                            hideLoader();
                                                                                            basic.showDialog(inner_response.success, '', null, true);
                                                                                            setTimeout(function () {
                                                                                                window.location.reload();
                                                                                            }, 3000);
                                                                                        }
                                                                                    }
                                                                                });
                                                                            }
                                                                        }
                                                                    }, 3000);
                                                                });
                                                            }, 2000);
                                                        }
                                                    }
                                                });
                                            } else if (response.error) {
                                                basic.showAlert(response.error, '', true);
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    } else if ($('.contract-header').hasClass('active')) {
                        var get_params = projectData.utils.getGETParameters();
                        if (basic.property_exists(get_params, 'successful-withdraw')) {
                            basic.showDialog('<div class="text-center padding-top-30"><svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg><div class="lato-bold fs-30">SUCCESSFULLY WITHDRAWN</div><div class="padding-top-20 padding-bottom-15 fs-20">You have successfully withdrawn your Dentacoins from this contract. You will be notified via email when next withdraw is possible.</div><div class="btn-container padding-bottom-40"><a href="http://etherscan.io/tx/'+get_params['successful-withdraw']+'" target="_blank" class="white-blue-green-btn min-width-200">Check on Etherscan</a></div></div>', '', null, true);
                        }

                        var now_timestamp = Math.round((new Date()).getTime() / 1000);
                        var period_to_withdraw = parseInt(await dApp.assurance_state_methods.getPeriodToWithdraw());
                        var time_passed_since_signed = now_timestamp - parseInt($('.single-contract-view-section').attr('data-created-at'));

                        if (time_passed_since_signed > period_to_withdraw) {
                            var remainder = time_passed_since_signed % period_to_withdraw;
                            var next_payment_timestamp = (now_timestamp + period_to_withdraw - remainder) * 1000;
                            var next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                        } else {
                            var next_payment_timestamp = (now_timestamp + period_to_withdraw - time_passed_since_signed) * 1000;
                            var next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                        }

                        $('.single-contract-view-section .row-with-bottom-squares .next-payment').html(projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj));

                        var on_load_exiting_contract = await dApp.assurance_state_methods.getPatient($('.single-contract-view-section').attr('data-patient'), $('.single-contract-view-section').attr('data-dentist'));
                        var contract_dcn_amount = on_load_exiting_contract[5];
                        var contract_next_payment = parseInt(on_load_exiting_contract[0]);
                        var current_patient_dcn_balance = parseInt(await dApp.dentacoin_token_methods.balanceOf($('.single-contract-view-section').attr('data-patient')));

                        // check for pending patient records - check-up or teeth cleaning
                        var visibleRecord = false;
                        setInterval(function() {
                            if (!visibleRecord && allowAutomaticScripts) {
                                $.ajax({
                                    type: 'POST',
                                    url: '/dentist/check-for-pending-contract-records',
                                    dataType: 'json',
                                    data: {
                                        contract: $('.single-contract-view-section').attr('data-contract')
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (response) {
                                        if (response.success) {
                                            visibleRecord = true;
                                            basic.showDialog(response.html, 'pending-contract-record', null, true);

                                            $('.pending-contract-record .confirm-record').click(function() {
                                                var record = $(this).attr('data-record');

                                                var clickWarningObj = {};
                                                clickWarningObj.callback = function (result) {
                                                    if (result) {
                                                        showLoader();
                                                        setTimeout(function() {
                                                            $.ajax({
                                                                type: 'POST',
                                                                url: '/dentist/take-action-for-pending-contract-records',
                                                                dataType: 'json',
                                                                data: {
                                                                    record: record,
                                                                    action: 'confirm'
                                                                },
                                                                headers: {
                                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function (response) {
                                                                    if (response.success) {
                                                                        visibleRecord = false;
                                                                        basic.closeDialog();
                                                                        hideLoader();
                                                                    } else if (response.error) {
                                                                        hideLoader();
                                                                        basic.showAlert(response.message, '', true);
                                                                    }
                                                                }
                                                            });
                                                        }, 2000);
                                                    }
                                                };
                                                basic.showConfirm('Sure you want to continue with approving your patient record?', '', clickWarningObj, true);
                                            });

                                            $('.pending-contract-record .decline-record').click(function() {
                                                var record = $(this).attr('data-record');

                                                var clickWarningObj = {};
                                                clickWarningObj.callback = function (result) {
                                                    if (result) {
                                                        showLoader();
                                                        setTimeout(function() {
                                                            $.ajax({
                                                                type: 'POST',
                                                                url: '/dentist/take-action-for-pending-contract-records',
                                                                dataType: 'json',
                                                                data: {
                                                                    record: record,
                                                                    action: 'decline'
                                                                },
                                                                headers: {
                                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                },
                                                                success: function (response) {
                                                                    if (response.success) {
                                                                        visibleRecord = false;
                                                                        basic.closeDialog();
                                                                        hideLoader();
                                                                    } else if (response.error) {
                                                                        hideLoader();
                                                                        basic.showAlert(response.message, '', true);
                                                                    }
                                                                }
                                                            });
                                                        }, 2000);
                                                    }
                                                };
                                                basic.showConfirm('Sure you want to continue with declining your patient record?', '', clickWarningObj, true);
                                            });
                                        }
                                    }
                                });
                            }
                        }, 5000);

                        if (contract_next_payment > now_timestamp) {
                            /*$('.camping-withdraw-time-left-section').html('<div class="row"><div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 padding-top-30 padding-bottom-30 clock-container text-center"><div class="row"><div class="col-xs-12 col-md-8 col-md-offset-2"><h2 class="fs-20 fs-xs-17 padding-bottom-20 padding-bottom-xs-10 lato-bold ">MAKE YOUR NEXT WITHDRAW IN</h2></div> </div><div class="clock"></div><div class="flip-clock-message"></div></div></div>');*/
                            $('.timer-label').html('MAKE YOUR NEXT WITHDRAW IN');
                            projectData.initiators.initFlipClockTimer(contract_next_payment - now_timestamp);
                        } else if (contract_next_payment < now_timestamp && now_timestamp - contract_next_payment > period_to_withdraw * 2 && current_patient_dcn_balance < (Math.floor((now_timestamp - contract_next_payment) / period_to_withdraw) + 1) * contract_dcn_amount) {
                            var months_dentist_didnt_withdraw = Math.floor((now_timestamp - contract_next_payment) / period_to_withdraw) + 1;

                            basic.showAlert('You haven\'t withdraw from this patient for ' + months_dentist_didnt_withdraw + ' months in a row, but the patient currently have not enough Dentacoins to cover all the months. Contact him and let him know to refill Dentacoins inside his Wallet Address.', '', true);
                        } else if (contract_next_payment < now_timestamp && now_timestamp < contract_next_payment + dApp.grace_period && current_patient_dcn_balance < contract_dcn_amount) {
                            //show red counter (grace period)
                            /*$('.camping-withdraw-time-left-section').html('<div class="row"><div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 padding-top-30 padding-bottom-30 clock-container text-center"><div class="row"><div class="col-xs-12 col-md-8 col-md-offset-2"><h2 class="fs-20 fs-xs-17 padding-bottom-20 padding-bottom-xs-10 lato-bold">Overdue payment. If the patient doesn\'t fill in '+contract_dcn_amount+' Dentacoins inside his Wallet Address the contract will be canceled in:</h2></div> </div><div class="clock red-background"></div><div class="flip-clock-message"></div></div></div>');*/
                            $('.timer-label').html('Overdue payment. If the patient doesn\'t fill in '+contract_dcn_amount+' Dentacoins inside his Wallet Address the contract will be canceled in:');
                            projectData.initiators.initFlipClockTimer(contract_next_payment + dApp.grace_period - now_timestamp);
                        } else {
                            $('.contract-body .wrapper').remove();

                            // ready to withdraw
                            $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img alt="Check inside shield" src="/assets/uploads/shield-check.svg" class="max-width-70"></figure><div class="fs-20 padding-top-15">Your money is waiting for you.</div><div class="padding-bottom-20 fs-20">Withdraw the Dentacoin tokens collected by <span class="calibri-bold">'+$('.camping-for-popups').attr('data-patient-name')+'</span>.</div><div class="text-center"><a href="javascript:void(0)" class="dentist-withdraw white-blue-green-btn inline-block max-width-280 max-width-xs-400 margin-bottom-10 width-100"><svg class="inline-block-top margin-right-5" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 75.7 74.3" style="enable-background:new 0 0 75.7 74.3;max-width: 25px;" xml:space="preserve"><style type="text/css">.st0{fill:#FFFFFF;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds bottomLeftOrigin="true" height="74.3" width="75.7" x="12.2" y="37.8"></sliceSourceBounds></sfw></metadata><path class="st0" d="M29.7,32.2h-7.5l0,0c-0.1,0-0.2,0-0.3,0h-0.1c-0.1,0-0.1,0-0.2,0.1c-0.1,0-0.1,0-0.2,0.1c-0.1,0-0.1,0.1-0.2,0.1c-0.1,0-0.1,0.1-0.2,0.1l-0.1,0.1c-0.1,0-0.1,0.1-0.2,0.2l0,0L20.6,33c0,0.1-0.1,0.1-0.1,0.2s-0.1,0.1-0.1,0.2c0,0.1-0.1,0.1-0.1,0.2s0,0.1-0.1,0.2c0,0.1,0,0.1,0,0.2s0,0.1,0,0.2v0.1l0,0c0,0.1,0,0.2,0,0.2c0,0.1,0,0.1,0,0.2c0,0.1,0,0.1,0.1,0.2c0,0.1,0,0.1,0.1,0.2c0,0.1,0.1,0.1,0.1,0.2s0.1,0.1,0.1,0.2l0.1,0.1c0,0.1,0.1,0.1,0.1,0.2l0,0l15.4,14.5c0.4,0.4,0.9,0.5,1.4,0.5s1-0.2,1.4-0.5l15.4-14.5c0.8-0.8,0.8-2,0.1-2.8c-0.4-0.5-1-0.7-1.6-0.6h-0.1h-7.5v-2.9c0-1.1-0.9-2-2-2s-2,0.9-2,2v4.9c0,1.1,0.9,2,2,2H48l-10.4,9.8l-10.4-9.8h4.4c1.1,0,2-0.9,2-2v-4.9c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2L29.7,32.2L29.7,32.2z"/><path class="st0" d="M29.7,23.3c0,1.1,0.9,2,2,2c1.1,0,2-0.9,2-2v-3.2c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2V23.3z"/><path class="st0" d="M41.3,23.3c0,1.1,0.9,2,2,2s2-0.9,2-2v-3.2c0-1.1-0.9-2-2-2s-2,0.9-2,2V23.3z"/><path class="st0" d="M29.7,12.8c0,1.1,0.9,2,2,2c1.1,0,2-0.9,2-2v-1.4c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2V12.8z"/><path class="st0" d="M41.3,12.8c0,1.1,0.9,2,2,2s2-0.9,2-2v-1.4c0-1.1-0.9-2-2-2s-2,0.9-2,2V12.8z"/><path class="st0" d="M31.7,4.1c1.1,0,2-0.9,2-2V2c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2v0.1C29.7,3.2,30.6,4.1,31.7,4.1z"/><path class="st0" d="M43.3,4.1c1.1,0,2-0.9,2-2V2c0-1.1-0.9-2-2-2s-2,0.9-2,2v0.1C41.3,3.2,42.2,4.1,43.3,4.1z"/><path class="st0" d="M75.7,51.4V37.2c0-9.4-9.2-17.7-23.4-21.2c-1.1-0.3-2.2,0.4-2.4,1.5c-0.3,1.1,0.4,2.2,1.5,2.4c12.4,3,20.4,9.8,20.4,17.3c0,10.2-15.5,18.9-33.9,18.9S4,47.5,4,37.2c0-7.3,7.8-14.1,19.9-17.2c1.1-0.3,1.7-1.4,1.4-2.4c-0.3-1.1-1.4-1.7-2.4-1.4C9,19.8,0,28,0,37.2v14.2c0,12.8,16.6,22.9,37.9,22.9S75.7,64.2,75.7,51.4z M71.7,47.6v3.9c0,2.9-1.2,5.7-3.5,8.1V51C69.5,49.9,70.7,48.8,71.7,47.6z M11.2,53.6c1.4,0.8,2.9,1.6,4.6,2.3v9.7c-1.7-0.8-3.2-1.7-4.6-2.7V53.6zM19.8,57.4c1.8,0.6,3.6,1,5.5,1.4v10.1c-1.9-0.4-3.8-1-5.5-1.6V57.4z M29.3,59.5c2.1,0.3,4.3,0.5,6.5,0.5v10.2c-2.2-0.1-4.4-0.3-6.5-0.6V59.5z M39.8,60c2.2-0.1,4.3-0.2,6.3-0.5v10.2c-2,0.3-4.2,0.5-6.3,0.6V60z M50.1,58.8c1.9-0.4,3.8-0.9,5.5-1.4v9.9c-1.7,0.6-3.6,1.1-5.5,1.6C50.1,68.9,50.1,58.8,50.1,58.8z M59.7,56c1.6-0.7,3.2-1.4,4.6-2.3v9.4c-1.4,1-2.9,1.8-4.6,2.6V56z M3.9,51.4v-3.9c1,1.2,2.1,2.3,3.3,3.3v8.6C5.1,57,3.9,54.3,3.9,51.4z"/></svg> WITHDRAW NOW</a></div></div></div>');

                            var ethgasstation_json = await $.getJSON('https://ethgasstation.info/json/ethgasAPI.json');
                            const on_page_load_gwei = ethgasstation_json.safeLow;
                            //adding 10% just in case the transaction dont fail
                            const on_page_load_gas_price = on_page_load_gwei * 100000000 + ((on_page_load_gwei * 100000000) * projectData.variables.bonusPercentagesToGasEstimations / 100);

                            //for the estimation going to use our internal address which aldready did gave before his allowance in DentacoinToken contract. In order to receive the gas estimation we need to pass all the method conditions and requires
                            var gas_cost_for_withdraw = await dApp.assurance_proxy_instance.methods.singleWithdraw($('.single-contract-view-section').attr('data-patient')).estimateGas({
                                from: global_state.account,
                                gas: 500000
                            });

                            var eth_fee = dApp.web3_1_0.utils.fromWei((gas_cost_for_withdraw * on_page_load_gas_price).toString(), 'ether');

                            var withdraw_function_abi = await dApp.assurance_proxy_instance.methods.singleWithdraw($('.single-contract-view-section').attr('data-patient')).encodeABI();

                            $('.dentist-withdraw').click(async function() {
                                if (metamask) {
                                    basic.showAlert('Using MetaMask is currently not supported in Dentacoin Assurance. Please switch off MetaMask extension and try again.');
                                } else {
                                    var existingCachedKey = false;
                                    var existingCachedKeystore = '';
                                    var currentAccountsStorage = localStorage.getItem('current-accounts');
                                    if (currentAccountsStorage != null && currentAccountsStorage != undefined) {
                                        var currentAccounts = JSON.parse(currentAccountsStorage);

                                        for(var i = 0, len = currentAccounts.length; i < len; i+=1) {
                                            if (global_state.account == projectData.utils.checksumAddress(currentAccounts[i].address)) {
                                                existingCachedKey = true;
                                                existingCachedKeystore = currentAccounts[i].keystore;
                                                break;
                                            }
                                        }
                                    }

                                    $.ajax({
                                        type: 'POST',
                                        url: '/get-recipe-popup',
                                        dataType: 'json',
                                        data: {
                                            /*to: assurance_config.assurance_proxy_address,*/
                                            cached_key: existingCachedKey,
                                            contract: $('.single-contract-view-section').attr('data-contract'),
                                            show_dcn_bar: false,
                                            recipe_title: 'WITHDRAW NOW',
                                            recipe_subtitle: '',
                                            recipe_checkbox_text: 'By clicking on the button below you will withdraw your Dentacoins from your Patient.',
                                            btn_label: 'WITHDRAW NOW',
                                            type: 'qr-scan'
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: async function (response) {
                                            if (response.success) {
                                                basic.closeDialog();
                                                basic.showDialog(response.success, 'recipe-popup', null, true);

                                                // proceed to dentacoin wallet scanning
                                                $('.generate-qr-code-for-wallet-scanning').click(async function() {
                                                    var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));
                                                    if (parseFloat(eth_fee) > current_user_eth_balance) {
                                                        //not enough ETH balance
                                                        basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. Please refill <a href="//wallet.dentacoin.com/buy" target="_blank">here</a>.</div>', '', true);
                                                    } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                        basic.showAlert('Please check the checkbox below to continue with the QR code generation.', '', true);
                                                    } else {
                                                        showLoader();

                                                        var scanObject = {
                                                            0 : eth_fee,
                                                            1 : dApp.web3_1_0.utils.toHex(on_page_load_gas_price),
                                                            2 : global_state.account,
                                                            3 : $('.single-contract-view-section').attr('data-contract'),
                                                            4 : 'active-withdraw'
                                                        };

                                                        generateQRCodeForDentacoinWalletScan(JSON.stringify(scanObject));
                                                    }
                                                });

                                                $('.recipe-popup .ether-fee .field').html(eth_fee);

                                                $('.recipe-popup .ether-fee i').popover({
                                                    trigger: 'click',
                                                    html: true
                                                });

                                                var transaction_key;
                                                if (!existingCachedKey) {
                                                    bindVerifyAddressLogic(true);
                                                    $(document).on('on-transaction-recipe-agree', function(event) {
                                                        transaction_key = event.response_data;
                                                        setTimeout(function() {
                                                            hideLoader();

                                                            $('.proof-of-address').remove();
                                                            $('.proof-success').fadeIn(1500);
                                                        }, 500);
                                                    });
                                                } else {
                                                    $('.camp-for-keystore-password').html('<div class="lato-regular fs-30 text-center padding-bottom-20 padding-top-15">Enter your keystore secret password</div><div class="padding-bottom-20"><div class="custom-google-label-style module max-width-280 margin-0-auto" data-input-blue-green-border="true"><label for="keystore-password">Secret password:</label><input type="password" maxlength="30" id="keystore-password" class="full-rounded keystore-password"/></div></div>');
                                                }

                                                $('.recipe-popup .execute-transaction .fire-blockchain-transaction').click(async function() {
                                                    var this_btn = $(this);
                                                    var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));
                                                    if (parseFloat(eth_fee) > current_user_eth_balance) {
                                                        //not enough ETH balance
                                                        basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. Please refill <a href="//wallet.dentacoin.com/buy" target="_blank">here</a>.</div>', '', true);
                                                    } else {
                                                        if (!existingCachedKey && transaction_key == undefined) {
                                                            basic.showAlert('You must first enter your private key or keystore file in order to sign the transaction.', '', true);
                                                            return false;
                                                        } else if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() == '') {
                                                            basic.showAlert('Please enter the secret password for your keystore file.', '', true);
                                                            return false;
                                                        } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                            basic.showAlert('Please check the checkbox below to continue with the transaction creation.', '', true);
                                                            return false;
                                                        } else {
                                                            showLoader('Your transaction is now being sent to the blockchain. It might take some time until it gets approved.');
                                                            setTimeout(async function() {
                                                                if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() != '') {
                                                                    var decrypted_keystore_file_response = decryptKeystore(existingCachedKeystore, $('.camp-for-keystore-password input[type="password"]').val().trim());
                                                                    if (decrypted_keystore_file_response.success) {
                                                                        transaction_key = decrypted_keystore_file_response.to_string;
                                                                    } else if (decrypted_keystore_file_response.error) {
                                                                        hideLoader();
                                                                        basic.showAlert(decrypted_keystore_file_response.message, '', true);
                                                                        return false;
                                                                    }
                                                                }
                                                                this_btn.unbind();
                                                                const EthereumTx = require('ethereumjs-tx');
                                                                var nonce = await dApp.web3_1_0.eth.getTransactionCount(global_state.account, 'pending');

                                                                var withdraw_transaction_obj = {
                                                                    gasLimit: dApp.web3_1_0.utils.toHex(Math.round(gas_cost_for_withdraw + (gas_cost_for_withdraw * 5/100))),
                                                                    gasPrice: dApp.web3_1_0.utils.toHex(on_page_load_gas_price),
                                                                    from: global_state.account,
                                                                    nonce: dApp.web3_1_0.utils.toHex(nonce),
                                                                    chainId: dApp.chain_id,
                                                                    data: withdraw_function_abi,
                                                                    to: assurance_config.assurance_proxy_address
                                                                };

                                                                const withdraw_transaction = new EthereumTx(withdraw_transaction_obj);
                                                                //signing the transaction
                                                                withdraw_transaction.sign(new Buffer(transaction_key, 'hex'));

                                                                //sending the transaction
                                                                dApp.web3_1_0.eth.sendSignedTransaction('0x' + withdraw_transaction.serialize().toString('hex'), function (err, transactionHash) {
                                                                    var execute_ajax = true;
                                                                    //doing setinterval check to check if the smart creation transaction got mined
                                                                    var withdraw_interval_check = setInterval(async function() {
                                                                        var withdraw_status = await dApp.web3_1_0.eth.getTransactionReceipt(transactionHash);
                                                                        if (withdraw_status != null && basic.property_exists(withdraw_status, 'status')) {
                                                                            if (withdraw_status.status && execute_ajax) {
                                                                                execute_ajax = false;
                                                                                clearInterval(withdraw_interval_check);

                                                                                //SEND EMAIL TO PATIENT
                                                                                $.ajax({
                                                                                    type: 'POST',
                                                                                    url: '/dentist/notify-patient-for-successful-withdraw',
                                                                                    dataType: 'json',
                                                                                    data: {
                                                                                        transaction_hash: transactionHash,
                                                                                        contract: $('.single-contract-view-section').attr('data-contract')
                                                                                    },
                                                                                    headers: {
                                                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                                    },
                                                                                    success: async function (response) {
                                                                                        var url = window.location.href;
                                                                                        window.location.href = url + '?successful-withdraw=' + transactionHash;
                                                                                    }
                                                                                });
                                                                                /*hideLoader();

                                                                                basic.showDialog('<div class="text-center padding-top-30"><svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg><div class="lato-bold fs-30">SUCCESSFULLY WITHDRAWN</div><div class="padding-top-20 padding-bottom-15 fs-20">You have successfully withdrawn your Dentacoins from this contract. You will be notified via email when next withdraw is possible.</div><div class="btn-container padding-bottom-40"><a href="javascript:void(0)" class="white-blue-green-btn min-width-200 close-popup">OK</a></div></div>', '', null, true);
                                                                                setTimeout(function() {
                                                                                    window.location.reload();
                                                                                }, 3000);*/
                                                                            }
                                                                        }
                                                                    }, 3000);
                                                                });
                                                            }, 2000);
                                                        }
                                                    }
                                                });
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    }
                }

                if ($('.contract-decrypt').length) {
                    $('.contract-decrypt').click(async function() {
                        var this_btn = $(this);
                        var encrypted_pdf_content = await getEncryptedContractPdfContent(this_btn.attr('data-hash'), this_btn.attr('data-type'));
                        var render_form = $('form#render-pdf');
                        if (encrypted_pdf_content.success) {
                            var existingCachedKey = false;
                            var existingCachedKeystore = '';
                            var currentAccountsStorage = localStorage.getItem('current-accounts');
                            if (currentAccountsStorage != null && currentAccountsStorage != undefined) {
                                var currentAccounts = JSON.parse(currentAccountsStorage);

                                for(var i = 0, len = currentAccounts.length; i < len; i+=1) {
                                    if (global_state.account == projectData.utils.checksumAddress(currentAccounts[i].address)) {
                                        existingCachedKey = true;
                                        existingCachedKeystore = currentAccounts[i].keystore;
                                        break;
                                    }
                                }

                                if (existingCachedKey) {
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
                                            $('.keystore-file-password-validation .keystore-password').focus();

                                            $('.keystore-file-password-validation .btn-container a').click(async function() {
                                                if ($('.keystore-file-password-validation .keystore-password').val().trim() == '') {
                                                    basic.showAlert('Please enter your password.', '', true);
                                                }else {
                                                    var decrypt_response = await decryptDataByKeystore(encrypted_pdf_content.success, existingCachedKeystore, $('.keystore-file-password-validation .keystore-password').val().trim());
                                                    if (decrypt_response.success) {
                                                        basic.closeDialog();
                                                        render_form.find('input[name="pdf_data"]').val(decrypt_response.success.decrypted);
                                                        render_form.submit();
                                                    } else if (decrypt_response.error) {
                                                        basic.showAlert(decrypt_response.message, '', true);
                                                    }
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    basic.closeDialog();
                                    openCacheKeyPopup(encrypted_pdf_content.success);
                                }
                            } else {
                                basic.closeDialog();
                                openCacheKeyPopup(encrypted_pdf_content.success);
                            }
                        } else if (encrypted_pdf_content.error) {
                            basic.showAlert(encrypted_pdf_content.error, '', true);
                        }
                    });
                }
            }
        }
    }
};

projectData.initiators.initDatepicker();

projectData.initiators.fixSelectsOnMac();

projectData.initiators.initPopoverTooltips();

projectData.initiators.initMobileMenu();

projectData.initiators.initDateTimePicker();


projectData.pagesData.onInit();

//LOGGED USER LOGIC
if ($('body').hasClass('logged-in')) {
    if ($('body').hasClass('create-contract')) {
        var signature_pad_inited = false;
        styleAvatarUploadButton('.steps-body .avatar .btn-wrapper label');

        bindTrackerClickDentistCreateWallet();

        projectData.initiators.initTooltips();

        multipleUseWalletAddressesLogic();

        if ($('.single-row.proof-of-address').length) {
            bindVerifyAddressLogic();
        }

        //showing the list for each service category
        $('.show-category-list a').click(function() {
            if ($(this).attr('data-hidden-list') == 'true') {
                $(this).attr('data-hidden-list', 'false').html($(this).attr('data-label-opened'));
            } else {
                $(this).attr('data-hidden-list', 'true').html($(this).attr('data-label-closed'));
            }
            $(this).closest('.show-category-list').find('ul').toggle(300);
        });

        var form_props_arr = ['professional-company-number', 'postal-address', 'country', 'phone', 'website', 'address', 'fname', 'lname', 'email', 'monthly-premium', 'check-ups-per-year', 'teeth-cleaning-per-year'];
        var create_contract_form = $('form#dentist-create-contract');
        create_contract_form.find('.terms-and-conditions-long-list').mCustomScrollbar();

        //on second step of contract creation when entering patient email execute query to check if this patient is already existing in the CoreDB
        var checkingPatientInterval;
        $('.step.two #patient-email').on('input paste change', function() {
            clearInterval(checkingPatientInterval);
            checkingPatientInterval = setTimeout(async function() {
                if (basic.validateEmail($('.step.two #patient-email').val().trim())) {
                    $('.step.two #patient-email').addClass('loading-background');

                    var checkEmail = await checkEmailAndReturnData($('.step.two #patient-email').val().trim(), 'patient');
                    if (checkEmail.success && checkEmail.data.indexOf(' ') >= 0) {
                        $('.step.two #fname').val(checkEmail.data.substr(0, checkEmail.data.indexOf(' ')));
                        $('.step.two #lname').val(checkEmail.data.substr(checkEmail.data.indexOf(' ')+1));
                    }
                    $('.step.two #patient-email').removeClass('loading-background');
                }
            }, 1000);
        });

        $('.step.three [name="monthly-premium"]').on('input', function() {
            if (parseInt($(this).val()) < 0) {
                $(this).val(0);
            } else {
                $(this).val(Math.floor($(this).val()));
            }

            $(this).closest('.single-row').find('.absolute-currency-label').fadeIn(300).css({'left' : 'calc(40% + ' + (15 + $(this).val().length * 10) + 'px)'});
        });

        //validation for all fields for each step
        async function validateStepFields(step_fields, step) {
            step_fields.removeClass('with-error');
            $('.step.'+step+' .single-row').removeClass('row-with-error');
            $('.step.'+step+' .single-row > label span').remove();

            var inner_error = false;

            if (step == 'three' && $('.step.three [name="general-dentistry[]"]:checked').val() == undefined) {
                $('.step.three .checkboxes-right-container').removeClass('with-error');

                if ($('.step.three [name="general-dentistry[]"]:checked').val() == undefined) {
                    $('.step.three .checkboxes-right-container').prev().find('span').remove();
                    customCreateContractErrorHandle($('.step.three .checkboxes-right-container'), 'Please select at least one service.');
                    inner_error = true;
                }
            } else if (step == 'one') {
                var validate_dentist_address = false;
                var dentist_address;
                if ($('.step.one #dcn_address').is('input')) {
                    dentist_address = $('.step.one #dcn_address').val().trim();
                } else {
                    dentist_address = $('.step.one #dcn_address').html().trim();
                }

                if (projectData.utils.innerAddressCheck(dentist_address)) {
                    //method for first step validating the dentist address
                    validate_dentist_address = await validateUserAddress(dentist_address, $('.step.one #dcn_address'));

                    if (validate_dentist_address) {
                        inner_error = true;
                    }
                }
            }

            for(var i = 0, len = step_fields.length; i < len; i+=1) {
                if (step_fields.eq(i).val().trim() == '' || step_fields.eq(i).val().trim() == '0') {
                    customCreateContractErrorHandle(step_fields.eq(i), 'Required field cannot be left blank.');
                    inner_error = true;
                } else if (step_fields.eq(i).attr('data-type') == 'email' && !basic.validateEmail(step_fields.eq(i).val().trim())) {
                    customCreateContractErrorHandle(step_fields.eq(i), 'Please enter valid email.');
                    inner_error = true;
                } else if (step_fields.eq(i).attr('data-type') == 'address' && !projectData.utils.innerAddressCheck(step_fields.eq(i).val().trim())) {
                    customCreateContractErrorHandle(step_fields.eq(i), 'Please enter valid wallet address.');
                    inner_error = true;
                } else if (step_fields.eq(i).attr('data-type') == 'website' && !basic.validateUrl(step_fields.eq(i).val().trim())) {
                    customCreateContractErrorHandle(step_fields.eq(i), 'Please enter your website URL starting with http:// or https://.');
                    inner_error = true;
                } else if (step_fields.eq(i).attr('data-type') == 'phone' && !basic.validatePhone(step_fields.eq(i).val().trim())) {
                    customCreateContractErrorHandle(step_fields.eq(i), 'Please enter valid phone.');
                    inner_error = true;
                }
            }

            if (inner_error) {
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
            if (this_btn.index() > $('.contract-creation-steps-container button[data-step="'+create_contract_form.find('.next').attr('data-current-step')+'"]').index()) {
                var validate_steps_arr;
                if (this_btn_step == 'two') {
                    validate_steps_arr = ['one'];
                } else if (this_btn_step == 'three') {
                    validate_steps_arr = ['one', 'two'];
                } else if (this_btn_step == 'four') {
                    validate_steps_arr = ['one', 'two', 'three'];
                }

                //if validate_steps_arr is defined and if no errors until now
                if (validate_steps_arr.length && !current_step_error) {
                    for(var y = 0, len = validate_steps_arr.length; y < len; y+=1) {
                        current_step_error = await validateStepFields($('.step.'+validate_steps_arr[y]+' input.right-field'), validate_steps_arr[y]);
                    }
                } else if (current_step_error) {
                    $('html, body').animate({scrollTop: create_contract_form.offset().top}, 500);
                }

                if (!current_step_error) {
                    //update the html of the NEXT button
                    if (this_btn_step == 'one' || this_btn_step == 'two') {
                        create_contract_form.find('.next').html('NEXT');
                    } else if (this_btn_step == 'three') {
                        create_contract_form.find('.next').html('GENERATE SAMPLE CONTRACT');
                    } else if (this_btn_step == 'four') {
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
                showLoader(undefined, undefined, 500);

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
                if (this_btn_step == 'one' || this_btn_step == 'two') {
                    create_contract_form.find('.next').html('NEXT');
                } else if (this_btn_step == 'three') {
                    create_contract_form.find('.next').html('GENERATE SAMPLE CONTRACT');
                } else if (this_btn_step == 'four') {
                    create_contract_form.find('.next').html('SIGN CONTRACT');
                }
            }
        });

        //method
        function onStepValidationSuccess(current_step, next_step, button) {
            if (next_step == 'four') {
                showLoader(undefined, 'contract-response-layer', 3000);
            }else {
                showLoader(undefined, undefined, 500);
            }

            $('.steps-body .step').hide();
            $('.step.'+next_step).show();
            button.attr('data-current-step', next_step);
            window.scrollTo(0, $('.contract-creation-steps-container').offset().top);

            if (next_step == 'four') {
                fourthStepValidation();
            }

            $('.contract-creation-steps-container button[data-step="'+next_step+'"]').removeClass('not-allowed-cursor').addClass('active');
            $('.contract-creation-steps-container button[data-step="'+current_step+'"]').removeClass('active not-passed').addClass('passed');
        }

        function firstStepPassedSuccessfully(button, next_step) {
            fireGoogleAnalyticsEvent('Contract Dentist', 'Next', 'Contract 1 Dentist Details');
            onStepValidationSuccess('one', next_step, button);
        }

        function secondStepPassedSuccessfully(button, next_step) {
            fireGoogleAnalyticsEvent('Contract Dentist', 'Next', 'Contract 2 Patient Details');
            onStepValidationSuccess('two', next_step, button);
        }

        function thirdStepPassedSuccessfully(button, next_step) {
            fireGoogleAnalyticsEvent('Contract Dentist', 'Generate', 'Contract 3 Conditions', $('.step.three [name="monthly-premium"]').val());
            onStepValidationSuccess('three', next_step, button);

            //update the fields on the sample contract
            for(var i = 0, len = form_props_arr.length; i < len; i+=1) {
                if (create_contract_form.find('[name="'+form_props_arr[i]+'"]').is('input')) {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('input[name="'+form_props_arr[i]+'"]').val().trim());
                } else if (create_contract_form.find('[name="'+form_props_arr[i]+'"]').is('select')) {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('select[name="'+form_props_arr[i]+'"]').val().trim());
                } else {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('[name="'+form_props_arr[i]+'"]').html().trim());
                }
            }

            $('.terms-and-conditions-long-list .terms-monthly-premium').html(create_contract_form.find('input[name="monthly-premium"]').val().trim());
            $('.terms-and-conditions-long-list .terms-check-ups-per-year').html(create_contract_form.find('select[name="check-ups-per-year"]').val().trim());
            $('.terms-and-conditions-long-list .terms-teeth-cleaning-per-year').html(create_contract_form.find('select[name="teeth-cleaning-per-year"]').val().trim());


            //clear step three services error
            $('.step.three .checkboxes-right-container').removeClass('with-error');

            $('.prophylaxis-list').html('');

            $('.step.four .checkboxes-right-container input[type="checkbox"]').prop('checked', false);
            //update the disabled checkboxes on the sample contract
            for(var i = 0, len = $('.step.three [name="general-dentistry[]"]:checked').length; i < len; i+=1) {
                $('.step.four input[type="checkbox"]#'+$('[name="general-dentistry[]"]:checked').eq(i).val()).prop('checked', true);

                //update the contract details in step four
                var parent = $('[name="general-dentistry[]"]:checked').eq(i).closest('.single-checkbox-container');
                $('.prophylaxis-list').append('<div class="'+$('[name="general-dentistry[]"]:checked').eq(i).val()+'"><div class="fs-18 calibri-bold padding-top-15 prophylaxis-title"></div><ul class="inner-list"></ul></div>');
                $('.terms-and-conditions-long-list .prophylaxis-list .'+$('[name="general-dentistry[]"]:checked').eq(i).val()+ ' .prophylaxis-title').html(parent.find('label').html());
                $('.terms-and-conditions-long-list .prophylaxis-list .'+$('[name="general-dentistry[]"]:checked').eq(i).val()+ ' .inner-list').html(parent.next().find('ul').html())
            }

            //init the signature logic
            if (!signature_pad_inited) {
                initSignaturePad();
                signature_pad_inited = true;
            }
        }

        //method for final step validation
        function fourthStepValidation() {
            //update fourth step html based on previous steps
            for(var i = 0, len = form_props_arr.length; i < len; i+=1) {
                if (create_contract_form.find('[name="'+form_props_arr[i]+'"]').is('input')) {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('input[name="'+form_props_arr[i]+'"]').val().trim());
                } else if (create_contract_form.find('[name="'+form_props_arr[i]+'"]').is('select')) {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('select[name="'+form_props_arr[i]+'"]').val().trim());
                } else {
                    $('.step.four #'+form_props_arr[i]).html(create_contract_form.find('[name="'+form_props_arr[i]+'"]').html().trim());
                }
            }

            //update the proposed monthly premium, based on the checked services
            $('.step.four #suggested-price').html($('.step.three .suggested-price').html());

            //update the disabled checkboxes on the sample contract
            $('.step.four .checkboxes-right-container input[type="checkbox"]').prop('checked', false);
            for(var i = 0, len = $('.step.three [name="general-dentistry[]"]:checked').length; i < len; i+=1) {
                $('.step.four input[type="checkbox"]#'+$('[name="general-dentistry[]"]:checked').eq(i).val()).prop('checked', true);
            }

            create_contract_form.unbind().on('submit', function(event) {
                var this_form = this;
                var form_errors = false;
                if (signature_pad.isEmpty()) {
                    basic.showAlert('Please sign the contract sample. Use your mouse or touch screen to sign.', '', true);
                    event.preventDefault();
                    form_errors = true;
                }else if (!$('.step.four input#terms').is(':checked')) {
                    basic.showAlert('Please accept the Terms and Conditions', '', true);
                    event.preventDefault();
                    form_errors = true;
                }else if (!$('.step.four input#privacy-policy').is(':checked')) {
                    basic.showAlert('Please accept the Privacy Policy', '', true);
                    event.preventDefault();
                    form_errors = true;
                }

                if (!form_errors) {
                    //save the base64 signature image in hidden value
                    $(this_form).find('input[name="dentist_signature"]').val(signature_pad.toDataURL('image/png'));

                    //delay the form submission so we can init loader animation
                    event.preventDefault();
                    showLoader('Sending ...', 'contract-response-success-layer');

                    fireGoogleAnalyticsEvent('Contract Dentist', 'Sign', 'Contract 4 Sign');
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
            if (checked_services.length) {
                $('.show-on-services-pick').fadeIn(1000);

                var checked_services_arr = [];
                for(var i = 0, len = checked_services.length; i < len; i+=1) {
                    checked_services_arr.push(checked_services.eq(i).val());
                }

                if ($.inArray('param_gd', checked_services_arr) != -1 && $.inArray('param_cd', checked_services_arr) != -1 && $.inArray('param_id', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-gd-cd-id');
                } else if ($.inArray('param_gd', checked_services_arr) != -1 && $.inArray('param_cd', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-gd-cd');
                } else if ($.inArray('param_gd', checked_services_arr) != -1 && $.inArray('param_id', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-gd-id');
                } else if ($.inArray('param_cd', checked_services_arr) != -1 && $.inArray('param_id', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-cd-id');
                } else if ($.inArray('param_gd', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-gd');
                } else if ($.inArray('param_cd', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-cd');
                } else if ($.inArray('param_id', checked_services_arr) != -1) {
                    suggested_price = create_contract_form.attr('data-param-id');
                }

                create_contract_form.find('.suggested-price').html(suggested_price);
                create_contract_form.find('.step.three [name="monthly-premium"]').val(suggested_price).closest('.single-row').find('.absolute-currency-label').fadeIn(300).css({'left' : 'calc(40% + ' + (15 + create_contract_form.find('.step.three [name="monthly-premium"]').val().length * 10) + 'px)'});
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
    }
}

function initSliders() {
    if ($('.contracts-list.slider').length) {
        var slides_to_show = 3;
        for(var i = 0, len = $('.contracts-list.slider').length; i < len; i+=1) {
            if ($('.contracts-list.slider').eq(i).attr('data-slides-number') != undefined) {
                slides_to_show = parseInt($('.contracts-list.slider').eq(i).attr('data-slides-number'));
            }

            var slider_params = {
                slidesToShow: slides_to_show,
                slidesToScroll: 3,
                autoplaySpeed: 8000
            };

            if ($('.contracts-list.slider').eq(i).hasClass('active-contracts')) {
                slider_params.responsive = [
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 650,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ];
            } else if ($('.contracts-list.slider').eq(i).hasClass('cancelleds') || $('.contracts-list.slider').eq(i).hasClass('pendings')) {
                slider_params.responsive = [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 1,
                            arrows: false
                        }
                    }
                ];
            } else if ($('.contracts-list.slider').eq(i).hasClass('patient-contract-list')) {
                slider_params.responsive = [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 650,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ];
            }

            $('.contracts-list.slider').eq(i).slick(slider_params);
        }
    }
}

//THIS IS FUNCTIONALITY ONLY FOR LOGGED IN USERS (MODULES)
if ($('body').hasClass('logged-in')) {
    var add_overflow_hidden_on_hidden_box_show = false;
    var sm_screen_width = false;
    $('body').addClass('overflow-hidden');
    if ($(window).width() < 992) {
        add_overflow_hidden_on_hidden_box_show = true;
        if ($(window).width() > 767) {
            sm_screen_width = true;
        }
    }
    $('body').removeClass('overflow-hidden');

    if (sm_screen_width) {
        $(document).on('click', 'body', function(){
            if (!$('.hidden-box-parent').find(event.target).length) {
                $('.logged-user-nav .hidden-box').removeClass('show-this');
                $('.logged-user-nav .up-arrow').removeClass('show-this');
            }
        });
    }

    if (add_overflow_hidden_on_hidden_box_show) {
        $('.logged-user-nav .user-name, .logged-user-nav .header-avatar').click(function() {
            $('.logged-user-nav .hidden-box').toggleClass('show-this');
            if (sm_screen_width) {
                $('.logged-user-nav .up-arrow').toggleClass('show-this');
            } else {
                $('body').toggleClass('overflow-hidden');
            }
        });
    } else {
        $('.logged-user-nav > .hidden-box-parent').hover(function () {
            $('.logged-user-nav .hidden-box').addClass('show-this');
            $('.logged-user-nav .up-arrow').addClass('show-this');
        }, function () {
            $('.logged-user-nav .hidden-box').removeClass('show-this');
            $('.logged-user-nav .up-arrow').removeClass('show-this');
        });
    }

    $('.logged-user-nav .close-btn a').click(function() {
        $('.logged-user-nav .hidden-box').removeClass('show-this');
        if (add_overflow_hidden_on_hidden_box_show) {
            $('body').removeClass('overflow-hidden');

            if (sm_screen_width) {
                $('.logged-user-nav .up-arrow').removeClass('show-this');
            }
        }
    });

    $(document).on('click', '.module.contract-tile', function() {
        showLoader();
    });

    $(document).on('click', '.renew-contract-btn', function(event) {
        event.preventDefault();
        fireGoogleAnalyticsEvent('Contract Dentist', 'Renew', 'Contract Renewal');

        window.open($(this).attr('href'));
    });

    if ($('.open-mobile-single-page-nav').length) {
        $('.open-mobile-single-page-nav > a').click(function() {
            $(this).closest('.contract-single-page-nav').find('ul').toggle(300);
        });
    }

    /*if ($('.logged-user-hamburger').length) {
        $('.logged-user-hamburger').click(function() {
            $('.logged-mobile-profile-menu').addClass('active');
        });

        $('.close-logged-mobile-profile-menu').click(function() {
            $('.logged-mobile-profile-menu').removeClass('active');
        });
    }*/

    initSliders();

    if ($('select.dropdown-with-clinics').length) {
        $('select.dropdown-with-clinics').on('change', function() {
            var this_select = $(this);
            $.ajax({
                type: 'POST',
                url: '/patient/get-contact-clinic-popup',
                dataType: 'json',
                data: {
                    clinic_id: this_select.val().trim()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    basic.showDialog(response.success, 'before-sending-email-confirmation-popup', null, true);

                    var custom_form_obj = {
                        clinic_id: this_select.val().trim(),
                        redirect: this_select.attr('data-current-route'),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };
                    $('.before-sending-email-confirmation-popup .send-mail').click(function() {
                        showLoader();

                        //clear spamming
                        $(this).unbind();

                        setTimeout(function() {
                            fireGoogleAnalyticsEvent('Sample Contract', 'Request', 'Contact Request by Patient');

                            customJavascriptForm('/patient/submit-contact-clinic', custom_form_obj, 'post');
                        }, 1500);
                    });
                }
            });
        });
    }

    if ($('section.open-new-assurance-contact-section input[type="text"].combobox').length) {
        $('section.open-new-assurance-contact-section input[type="text"].combobox').attr('placeholder', 'Search for a clinic...');
    }

    if ($('section.ready-to-purchase-with-external-api').length) {
        //currency conversion logic
        var current_active_currency = 'dcn';
        var dcn_for_one_usd = parseFloat($('section.ready-to-purchase-with-external-api').attr('data-dcn-for-one-usd'));
        var eth_for_one_usd = parseFloat($('section.ready-to-purchase-with-external-api').attr('data-eth-for-one-usd'));
        $('section.ready-to-purchase-with-external-api #crypto-amount').val(Math.floor(dcn_for_one_usd * parseFloat($('section.ready-to-purchase-with-external-api #usd-value').val().trim())));

        $('section.ready-to-purchase-with-external-api #usd-value').on('input', function() {
            if ($(this).val().trim() < 10)   {
                $(this).parent().addClass('error-field');
            }else {
                $(this).parent().removeClass('error-field');
            }

            if (parseFloat($(this).val().trim()) < 0)    {
                $(this).val(10);
            }else if (parseFloat($(this).val().trim()) > 6000)    {
                $(this).val(6000);
            }

            if ($('section.ready-to-purchase-with-external-api nav ul li a.active').attr('data-currency') == 'dcn') {
                $('section.ready-to-purchase-with-external-api #crypto-amount').val(Math.floor(dcn_for_one_usd * parseFloat($(this).val().trim())));
            } else if ($('section.ready-to-purchase-with-external-api nav ul li a.active').attr('data-currency') == 'eth') {
                $('section.ready-to-purchase-with-external-api #crypto-amount').val(eth_for_one_usd * parseFloat($(this).val().trim()));
            }
        });

        $('section.ready-to-purchase-with-external-api nav ul li a').on('click', function() {
            $('section.ready-to-purchase-with-external-api nav ul li a').removeClass('active');
            $(this).addClass('active');
            if (current_active_currency != $(this).attr('data-currency')) {
                current_active_currency = $(this).attr('data-currency');

                $('section.ready-to-purchase-with-external-api #usd-value').val(10);
                $('section.ready-to-purchase-with-external-api #usd-value').parent().removeClass('error-field');

                $('section.ready-to-purchase-with-external-api .crypto-label').html(current_active_currency.toUpperCase());

                if (current_active_currency == 'dcn') {
                    $('section.ready-to-purchase-with-external-api #crypto-amount').val(dcn_for_one_usd * 10);
                } else if (current_active_currency == 'eth') {
                    $('section.ready-to-purchase-with-external-api #crypto-amount').val(eth_for_one_usd * 10);
                }
            }
        });

        $('section.ready-to-purchase-with-external-api #crypto-amount').on('input', function() {
            var divisor;
            if ($('section.ready-to-purchase-with-external-api nav ul li a.active').attr('data-currency') == 'dcn') {
                divisor = dcn_for_one_usd;
            } else if ($('section.ready-to-purchase-with-external-api nav ul li a.active').attr('data-currency') == 'eth') {
                divisor = eth_for_one_usd;
            }

            if (parseFloat($(this).val().trim()) / divisor > 6000)   {
                $(this).val(divisor * 6000);
            }
            $('section.ready-to-purchase-with-external-api #usd-value').val(parseFloat($(this).val().trim()) / divisor);
        });

        $('section.ready-to-purchase-with-external-api .buy-crypto-btn').click(function() {
            var currency = $('section.ready-to-purchase-with-external-api nav ul li a.active').attr('data-currency');
            var currency_amount_for_one_usd;
            if (currency == 'dcn') {
                currency_amount_for_one_usd = dcn_for_one_usd;
                var event_obj = {
                    'event_category': 'Purchase',
                    'value': parseInt($('section.ready-to-purchase-with-external-api #crypto-amount').val().trim()),
                    'event_label': currency
                };
            } else if (currency == 'eth') {
                currency_amount_for_one_usd = eth_for_one_usd;
                var event_obj = {
                    'event_category': 'Purchase',
                    'value': parseInt($('section.ready-to-purchase-with-external-api #usd-value').val().trim()),
                    'event_label': 'USD in ETH'
                };
            }

            if (parseFloat($('section.ready-to-purchase-with-external-api #usd-value').val().trim()) < 10)  {
                basic.showAlert('The minimum transaction limit is 10 USD.', '', true);
            }else if (parseFloat($('section.ready-to-purchase-with-external-api #usd-value').val().trim()) > 6000)  {
                basic.showAlert('The maximum transaction limit is 6000 USD.', '', true);
            }else if (parseFloat($('section.ready-to-purchase-with-external-api #crypto-amount').val().trim()) < currency_amount_for_one_usd * 30)  {
                basic.showAlert('The minimum transaction limit is 10 USD in '+currency.toUpperCase()+'.', '', true);
            }else if (parseFloat($('section.ready-to-purchase-with-external-api #crypto-amount').val().trim()) > currency_amount_for_one_usd * 6000)  {
                basic.showAlert('The maximum transaction limit is 6000 USD in '+currency.toUpperCase()+'.', '', true);
            }else if (!projectData.utils.innerAddressCheck($('section.ready-to-purchase-with-external-api input#dcn_address').val().trim())) {
                basic.showAlert('Please enter a valid wallet address. It should start with "0x" and be followed by 40 characters (numbers and letters).', '', true);
            }else if (!basic.validateEmail($('section.ready-to-purchase-with-external-api input#email').val().trim()))  {
                basic.showAlert('Please enter a valid email.', '', true);
            }else if (!$('section.ready-to-purchase-with-external-api #privacy-policy-agree').is(':checked')) {
                basic.showAlert('Please agree with our Privacy Policy.', '', true);
            }else {
                //sending GTAG event
                gtag('event', 'Buy', event_obj);

                window.open('https://indacoin.com/gw/payment_form?partner=dentacoin&cur_from=USD&cur_to='+currency.toUpperCase()+'&amount='+$('section.ready-to-purchase-with-external-api #usd-value').val().trim()+'&address='+$('section.ready-to-purchase-with-external-api input#dcn_address').val().trim()+'&user_id='+$('section.ready-to-purchase-with-external-api input#email').val().trim(), '_blank');
            }
        });
    }

    if ($('.dentist-no-contracts-section').length) {
        bindTrackerClickDentistSignFirstContract();
    }

    if ($('.dentist-contracts-section').length) {
        bindTrackerClickDentistCreateContract();
    }
} else {
    bindTrackerClickSignUpToSeeDetails();
}

function calculateLogic() {
    $('.calculate').click(function() {
        var patients_number = $('#number-of-patients').val();
        var params_type;
        if ($('#general-dentistry').is(':checked') && $('#cosmetic-dentistry').is(':checked') && $('#implant-dentistry').is(':checked')) {
            params_type = 'param_gd_cd_id';
        } else if ($('#general-dentistry').is(':checked') && $('#cosmetic-dentistry').is(':checked')) {
            params_type = 'param_gd_cd';
        } else if ($('#general-dentistry').is(':checked') && $('#implant-dentistry').is(':checked')) {
            params_type = 'param_gd_id';
        } else if ($('#cosmetic-dentistry').is(':checked') && $('#implant-dentistry').is(':checked')) {
            params_type = 'param_cd_id';
        } else if ($('#general-dentistry').is(':checked')) {
            params_type = 'param_gd';
        } else if ($('#cosmetic-dentistry').is(':checked')) {
            params_type = 'param_cd';
        } else if ($('#implant-dentistry').is(':checked')) {
            params_type = 'param_id';
        }

        var country = $('#country').val();
        var currency = $('#currency').val();

        if (patients_number == '' || parseInt(patients_number) <= 0) {
            basic.showAlert('Please enter valid number of patients per day.', '', true);
            return false;
        } else if (params_type == undefined) {
            basic.showAlert('Please select specialties.', '', true);
            return false;
        } else if (country == undefined) {
            basic.showAlert('Please select country.', '', true);
            return false;
        } else if (currency == undefined) {
            basic.showAlert('Please select currency.', '', true);
            return false;
        }
        var calculator_data = {
            'patients_number' : patients_number.trim(),
            'params_type' : params_type,
            'country' : country.trim(),
            'currency' : currency.trim()
        };

        showLoader();
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
                    hideLoader();

                    var comma_separator_number_step = $.animateNumber.numberStepFactories.separator(',');
                    $('.calculator-result-popup .price-container .result .amount').animateNumber({
                        number: parseFloat($('.calculator-result-popup .price-container .result .amount').attr('data-result')),
                        numberStep: comma_separator_number_step
                    }, 1000);

                    fireGoogleAnalyticsEvent('Dentist Income', 'Calculate', 'Income Increase');

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
        if (params.hasOwnProperty(key)) {
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

//camp for popups from responses and bind the close event for the button in the popup
$(document).on('click', '.close-popup', function() {
    basic.closeDialog();
});

if (!$('body').hasClass('logged-in')) {
    dcnGateway.init({
        'platform' : 'assurance',
        /*'environment' : 'staging',*/
        'forgotten_password_link' : 'https://account.dentacoin.com/forgotten-password?platform=assurance'
    });

    $(document).on('dentistAuthSuccessResponse', async function (event) {
        console.log('dentistAuthSuccessResponse');
        window.location.reload();
    });

    $(document).on('patientAuthSuccessResponse', async function (event) {
        console.log('patientAuthSuccessResponse');
        window.location.reload();
    });
}

var croppie_instance;
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#cropper-container').addClass('width-and-height');
            if (croppie_instance != undefined) {
                croppie_instance.croppie('destroy');
                $('#cropper-container').html('');
            }

            croppie_instance = $('#cropper-container').croppie({
                viewport: {
                    width: 120,
                    height: 120
                }
            });

            $('.avatar.module .btn-wrapper').hide();
            $('.max-size-label').addClass('active');

            croppie_instance.croppie('bind', {
                url: e.target.result,
                points: [77,469,280,739]
            });

            $('#cropper-container').on('update.croppie', function(ev, cropData) {
                croppie_instance.croppie('result', {
                    type: 'canvas',
                    size: {width: 300, height: 300}
                }).then(function (src) {
                    $('#hidden-image').val(src);
                });
            });
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function styleAvatarUploadButton(label_el)    {
    if (jQuery(".upload-file.avatar").length) {
        jQuery(".upload-file.avatar").each(function(key, form){
            var this_file_btn_parent = jQuery(this);
            if (this_file_btn_parent.attr('data-current-user-avatar')) {
                this_file_btn_parent.find('.btn-wrapper').append('<label for="custom-upload-avatar" role="button" style="background-image:url('+this_file_btn_parent.attr('data-current-user-avatar')+');"><div class="inner"><i class="fa fa-plus fs-0" aria-hidden="true"></i><div class="inner-label fs-0">Add profile photo</div></div></label>');
            } else {
                this_file_btn_parent.find('.btn-wrapper').append('<label for="custom-upload-avatar" role="button"><div class="inner"><i class="fa fa-plus" aria-hidden="true"></i><div class="inner-label">Add profile photo</div></div></label>');
            }

            var inputs = document.querySelectorAll('.inputfile');
            Array.prototype.forEach.call(inputs, function(input) {
                var label    = input.nextElementSibling,
                    labelVal = label.innerHTML;

                input.addEventListener('change', function(e) {
                    if (2 < projectData.utils.bytesToMegabytes(this.files[0].size)) {
                        basic.showAlert('The image you selected is large. Max size: 2MB.', '', true);
                        $(this).val('');
                    } else {
                        readURL(this, label_el);

                        var fileName = '';
                        if (this.files && this.files.length > 1)
                            fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length);
                        else
                            fileName = e.target.value.split('\\').pop();

                        /*if (fileName) {
                            if (load_filename_to_other_el)    {
                                $(this).closest('.form-row').find('.file-name').html('<i class="fa fa-file-text-o" aria-hidden="true"></i>' + fileName);
                            }else {
                                label.querySelector('span').innerHTML = fileName;
                            }
                        }else{
                            label.innerHTML = labelVal;
                        }*/
                    }
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
    $(document).on('click', '.bootbox', function(event){
        var classname = event.target.className;

        classname = classname.replace(/ /g, '.');

        if (classname && !$('.' + classname).parents('.modal-dialog').length) {
            if ($('.bootbox.login-signin-popup').length) {
                $('.hidden-login-form').html(hidden_popup_content);
            }
            bootbox.hideAll();
        }
    });
}
hidePopupOnBackdropClick();

function apiEventsListeners() {
    //login
    $(document).on('successResponseCoreDBApi', async function (event) {
        if (event.response_data.token) {
            var custom_form_obj = {
                token: event.response_data.token,
                id: event.response_data.data.id,
                email: event.response_data.data.email,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            if ($('input[type="hidden"][name="route"]').length && $('input[type="hidden"][name="slug"]').length) {
                custom_form_obj.route = $('input[type="hidden"][name="route"]').val();
                custom_form_obj.slug = $('input[type="hidden"][name="slug"]').val();
            }

            //check if CoreDB returned address for this user and if its valid one
            if (basic.property_exists(custom_form_obj, 'address') != null && projectData.utils.innerAddressCheck(custom_form_obj.address)) {
                //var current_dentists_for_logging_user = await dApp.assurance_methods.getWaitingContractsForPatient(custom_form_obj.address);
                //if (current_dentists_for_logging_user.length > 0) {
                //custom_form_obj.have_contracts = true;
                //}
            }

            if (event.response_data.new_account) {
                //REGISTER
                if (event.platform_type == 'facebook') {
                    fireGoogleAnalyticsEvent('PatientRegistration', 'ClickFB', 'Patient Registration FB');
                } else if (event.platform_type == 'civic') {
                    fireGoogleAnalyticsEvent('PatientRegistration', 'ClickNext', 'Patient Registration Civic');
                }
            } else {
                //LOGIN
                if (event.platform_type == 'facebook') {
                    fireGoogleAnalyticsEvent('PatientLogin', 'Click', 'Login FB');
                } else if (event.platform_type == 'civic') {
                    fireGoogleAnalyticsEvent('PatientLogin', 'Click', 'Login Civic');
                }
            }

            customJavascriptForm('/patient/authenticate', custom_form_obj, 'post');
        }
    });

    $(document).on('errorResponseCoreDBApi', function (event) {
        var error_popup_html = '';
        if (event.response_data.errors) {
            for(var key in event.response_data.errors) {
                error_popup_html += event.response_data.errors[key]+'<br>';
            }
        }

        $('.response-layer').hide();
        basic.showAlert(error_popup_html, 'style-anchors', true);
    });
}
apiEventsListeners();

//INIT LOGIC FOR ALL STEPS
function customErrorHandle(el, string) {
    el.append('<div class="error-handle">'+string+'</div>');
}

if ($('form#invite-dentists').length) {
    $('form#invite-dentists').on('submit', function(event) {
        event.preventDefault();
        var this_form = $(this);

        var form_fields = this_form.find('.custom-input.required');
        var errors = false;
        this_form.find('.error-handle').remove();

        //check custom-input fields
        for(var i = 0, len = form_fields.length; i < len; i+=1) {
            if (form_fields.eq(i).is('input')) {
                //IF INPUT TAG
                if (form_fields.eq(i).val().trim() == '') {
                    customErrorHandle(form_fields.eq(i).parent(), 'This field is required.');
                    errors = true;
                }
            }
        }

        if (this_form.find('input[name="dcn_address"]').length) {
            if (this_form.find('input[name="dcn_address"]').val().trim() == '' || !projectData.utils.innerAddressCheck(this_form.find('input[name="dcn_address"]').val().trim())) {
                customErrorHandle(this_form.find('input[name="dcn_address"]').parent(), 'This field is required. Please enter valid Wallet Address.');
                errors = true;
            }
        }

        if (!errors) {
            fireGoogleAnalyticsEvent('Invitation by Patient', 'Send', 'Invite Dentist Step 1');

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
                    basic.showDialog(response.success, 'before-sending-email-confirmation-popup', null, true);

                    var serialized_values = this_form.serializeArray();
                    var custom_form_obj = {};
                    $('.before-sending-email-confirmation-popup .send-mail').click(function() {
                        showLoader();

                        //clear spamming
                        $(this).unbind();

                        for(var i = 0, len = serialized_values.length; i < len; i+=1) {
                            custom_form_obj[serialized_values[i].name] = serialized_values[i].value;
                        }

                        setTimeout(function() {
                            fireGoogleAnalyticsEvent('Invitation by Patient', 'Send', 'Invite Dentist Complete');

                            customJavascriptForm('/patient/submit-invite-dentists', custom_form_obj, 'post');
                        }, 1500);
                    });
                }
            });
        }
    });
}

//showing front end loader
function showLoader(message, type, time) {
    if (message == undefined) {
        message = 'Loading ...';
    }
    if (type === undefined) {
        $('.camping-loader').html('<div class="response-layer"><div class="wrapper"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img src="/assets/images/loader.gif" class="max-width-160" alt="Loader"></figure><div class="message text-center fs-24 fs-xs-30 lato-semibold">'+message+'</div></div></div>');
        $('.response-layer').show();
    } else {
        if (type == 'contract-response-success-layer') {
            $('.camping-loader').html('<div class="contract-response-success-layer"><div class="wrapper"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img src="/assets/images/contract-assurance-loading-success.gif" class="max-width-440 min-width-300" alt="Loader"></figure><div class="message text-center fs-24 fs-xs-30 lato-semibold">'+message+'</div></div></div>');
            $('.contract-response-success-layer').show();
        } else if (type == 'contract-response-layer') {
            $('.camping-loader').html('<div class="contract-response-layer"><div class="wrapper"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img src="/assets/images/contract-assurance-loading.gif" class="max-width-440 min-width-300" alt="Loader"></figure><div class="message text-center fs-24 fs-xs-30 lato-semibold">'+message+'</div></div></div>');
            $('.contract-response-layer').show();
        }
    }

    if (time != undefined) {
        setTimeout(function() {
            $('.camping-loader').html('');
        }, time);
    }
}

//hiding front end loader
function hideLoader() {
    $('.camping-loader').html('');
}

var signature_pad;
function initSignaturePad() {
    if ($('#signature-pad').length) {
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

        if ($('.clear-signature').length) {
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

//if cancel contract button exist add the event for it
function cancelContractEventInit() {
    if ($('.cancel-contract-btn').length) {
        $('.cancel-contract-btn').click(async function() {
            var this_btn = $(this);

            if (this_btn.attr('data-patient') != undefined && this_btn.attr('data-dentist') != undefined) {
                //CHECK FOR CONTRACT ON THE BLOCKCHAIN
                var exiting_contract = await dApp.assurance_state_methods.getPatient(this_btn.attr('data-patient'), this_btn.attr('data-dentist'));
                if ((new Date(parseInt(exiting_contract[0]) * 1000)).getTime() > 0) {
                    if (metamask) {
                        basic.showAlert('Using MetaMask is currently not supported in Dentacoin Assurance. Please switch off MetaMask extension and try again.');
                    } else {
                        var existingCachedKey = false;
                        var existingCachedKeystore = '';
                        var currentAccountsStorage = localStorage.getItem('current-accounts');
                        if (currentAccountsStorage != null && currentAccountsStorage != undefined) {
                            var currentAccounts = JSON.parse(currentAccountsStorage);

                            for(var i = 0, len = currentAccounts.length; i < len; i+=1) {
                                if (global_state.account == projectData.utils.checksumAddress(currentAccounts[i].address)) {
                                    existingCachedKey = true;
                                    existingCachedKeystore = currentAccounts[i].keystore;
                                    break;
                                }
                            }
                        }

                        $.ajax({
                            type: 'POST',
                            url: '/get-recipe-popup',
                            dataType: 'json',
                            data: {
                                cached_key: existingCachedKey,
                                contract: this_btn.attr('data-contract'),
                                show_dcn_bar: false,
                                recipe_title: this_btn.attr('data-recipe-title'),
                                recipe_subtitle: this_btn.attr('data-recipe-subtitle'),
                                recipe_checkbox_text: this_btn.attr('data-recipe-checkbox-text'),
                                btn_label: 'CANCEL NOW',
                                type: 'qr-scan'
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: async function (response) {
                                if (response.success) {
                                    basic.closeDialog();
                                    basic.showDialog(response.success, 'recipe-popup', null, true);
                                    var contract_cancellation_function_abi = await dApp.assurance_proxy_instance.methods.breakContract(projectData.utils.checksumAddress(response.contract_data.patient), projectData.utils.checksumAddress(response.contract_data.dentist)).encodeABI();

                                    var select_options = '';
                                    if (response.contract_data.type == 'dentist') {
                                        select_options = '<option value="Overdue payments">Overdue payments</option><option value="Missed regular check-ups">Missed regular check-ups</option><option value="Inappropriate behaviour">Inappropriate behaviour</option>';
                                    } else if (response.contract_data.type == 'patient') {
                                        if (response.contract_data.status == 'pending') {
                                            select_options = '<option value="I don\'t need this contract.">I don\'t need this contract.</option><option value="The monthly premium is too high.">The monthly premium is too high.</option><option value="I don\'t like the conditions.">I don\'t like the conditions.</option><option value="I think I\'ve received this by mistake.">I think I\'ve received this by mistake.</option>';
                                        } else {
                                            select_options = '<option value="I don\'t need this contract anymore.">I don\'t need this contract anymore.</option><option value="I decided to go to a different dentist.">I decided to go to a different dentist.</option><option value="Inappropriate behaviour or service.">Inappropriate behaviour or service.</option>';
                                        }
                                    }

                                    $('.recipe-popup .extra-recipe-html').html('<div class="input-row padding-top-0 padding-bottom-0"><label for="cancel-contract-reason" class="inline-block">Cancellation reason</label><div class="field inline-block"><select id="cancel-contract-reason"><option selected disabled value="">Choose from the list</option>'+select_options+'<option data-open-bonus-field="true" value="Other">Other</option></select></div></div><div class="camp-for-row"></div><div class="input-row padding-top-0 padding-bottom-0"><label for="cancel-contract-comments" class="inline-block">Comments:</label><div class="field inline-block"><textarea id="cancel-contract-comments" maxlength="3000" class="pencil-background" placeholder="Describe the reason for cancelling this contract in more details (optional)"></textarea></div></div>');

                                    projectData.initiators.fixSelectsOnMac();

                                    $('.recipe-popup #cancel-contract-reason').on('change', function() {
                                        if ($(this).find('option:selected').attr('data-open-bonus-field') == 'true') {
                                            $('.recipe-popup .camp-for-row').html('<div class="input-row padding-top-0 padding-bottom-0"><label for="cancel-contract-other-reason" class="inline-block">Other reason:</label><div class="field inline-block"><input type="text" id="cancel-contract-other-reason" placeholder="Please specify" class="pencil-background" maxlength="255"/></div></div>');
                                        } else {
                                            $('.recipe-popup .camp-for-row').html('');
                                        }
                                    });

                                    var ethgasstation_json = await $.getJSON('https://ethgasstation.info/json/ethgasAPI.json');
                                    const on_page_load_gwei = ethgasstation_json.safeLow;
                                    //adding 10% just in case the transaction dont fail
                                    const on_page_load_gas_price = on_page_load_gwei * 100000000 + ((on_page_load_gwei * 100000000) * projectData.variables.bonusPercentagesToGasEstimations / 100);

                                    //for the estimation going to use our internal address which aldready did gave before his allowance in DentacoinToken contract. In order to receive the gas estimation we need to pass all the method conditions and requires
                                    var gas_cost_for_contract_cancellation = await dApp.assurance_proxy_instance.methods.breakContract(projectData.utils.checksumAddress(response.contract_data.patient), projectData.utils.checksumAddress(response.contract_data.dentist)).estimateGas({
                                        from: global_state.account,
                                        gas: 500000
                                    });

                                    var eth_fee = dApp.web3_1_0.utils.fromWei((gas_cost_for_contract_cancellation * on_page_load_gas_price).toString(), 'ether');
                                    $('.recipe-popup .ether-fee .field').html(eth_fee);

                                    $('.recipe-popup .ether-fee i').popover({
                                        trigger: 'click',
                                        html: true
                                    });

                                    var transaction_key;
                                    if (!existingCachedKey) {
                                        bindVerifyAddressLogic(true);
                                        $(document).on('on-transaction-recipe-agree', function (event) {
                                            transaction_key = event.response_data;
                                            setTimeout(function () {
                                                hideLoader();

                                                $('.proof-of-address').remove();
                                                $('.proof-success').fadeIn(1500);
                                            }, 500);
                                        });
                                    } else {
                                        $('.camp-for-keystore-password').html('<div class="lato-regular fs-30 text-center padding-bottom-20 padding-top-15">Enter your keystore secret password</div><div class="padding-bottom-20"><div class="custom-google-label-style module max-width-280 margin-0-auto" data-input-blue-green-border="true"><label for="keystore-password">Secret password:</label><input type="password" maxlength="30" id="keystore-password" class="full-rounded keystore-password"/></div></div>');
                                    }

                                    // proceed to dentacoin wallet scanning
                                    $('.generate-qr-code-for-wallet-scanning').click(async function() {
                                        var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));
                                        if (parseFloat(eth_fee) > current_user_eth_balance) {
                                            //not enough ETH balance
                                            basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. Please refill <a href="//wallet.dentacoin.com/buy" target="_blank">here</a>.</div>', '', true);
                                        } else if ($('.recipe-popup #cancel-contract-other-reason').length && $('.recipe-popup #cancel-contract-other-reason').val().trim() == '') {
                                            basic.showAlert('Please enter other reason.', '', true);
                                        } else if ($('.recipe-popup #cancel-contract-reason').val() == null) {
                                            basic.showAlert('Please select cancellation reason.', '', true);
                                        } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                            basic.showAlert('Please check the checkbox below to continue with the QR code generation.', '', true);
                                        } else {
                                            showLoader();

                                            var scanObject = {
                                                0 : eth_fee,
                                                1 : dApp.web3_1_0.utils.toHex(on_page_load_gas_price),
                                                2 : global_state.account,
                                                3 : this_btn.attr('data-contract'),
                                                4 : 'cancel',
                                                5 : response.contract_data.type
                                            };

                                            if ($('.recipe-popup #cancel-contract-other-reason').length) {
                                                scanObject[6] = $('.recipe-popup #cancel-contract-other-reason').val().trim();
                                            } else {
                                                scanObject[6] = $('.recipe-popup #cancel-contract-reason option:selected').html();
                                            }

                                            generateQRCodeForDentacoinWalletScan(JSON.stringify(scanObject));
                                        }
                                    });

                                    // continue with assurance blockchain execution
                                    $('.recipe-popup .execute-transaction .fire-blockchain-transaction').click(async function () {
                                        var this_execute_transaction_btn = $(this);
                                        var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));

                                        if (parseFloat(eth_fee) > current_user_eth_balance) {
                                            //not enough ETH balance
                                            basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. Please refill <a href="//wallet.dentacoin.com/buy" target="_blank">here</a>.</div>', '', true);
                                        } else {
                                            if ($('.recipe-popup #cancel-contract-other-reason').length && $('.recipe-popup #cancel-contract-other-reason').val().trim() == '') {
                                                basic.showAlert('Please enter other reason.', '', true);
                                            } else if ($('.recipe-popup #cancel-contract-reason').val() == null) {
                                                basic.showAlert('Please select cancellation reason.', '', true);
                                            } /*else if ($('.recipe-popup #cancel-contract-comments').val().trim() == '') {
                                                basic.showAlert('Please enter comments.', '', true);
                                            }*/ else if (!existingCachedKey && transaction_key == undefined) {
                                                basic.showAlert('You must first enter your private key or keystore file in order to sign the transaction.', '', true);
                                                return false;
                                            } else if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() == '') {
                                                basic.showAlert('Please enter the secret password for your keystore file.', '', true);
                                                return false;
                                            } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                basic.showAlert('Please check the checkbox below to continue with the transaction creation.', '', true);
                                                return false;
                                            } else {
                                                showLoader('Your transaction is now being sent to the blockchain. It might take some time until it gets approved.');
                                                setTimeout(async function() {
                                                    if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() != '') {
                                                        var decrypted_keystore_file_response = decryptKeystore(existingCachedKeystore, $('.camp-for-keystore-password input[type="password"]').val().trim());
                                                        if (decrypted_keystore_file_response.success) {
                                                            transaction_key = decrypted_keystore_file_response.to_string;
                                                        } else if (decrypted_keystore_file_response.error) {
                                                            hideLoader();
                                                            basic.showAlert(decrypted_keystore_file_response.message, '', true);
                                                            return false;
                                                        }
                                                    }
                                                    this_execute_transaction_btn.unbind();

                                                    //fire google analytics event
                                                    if ($('.recipe-popup #cancel-contract-reason').val() != '' && $('.recipe-popup #cancel-contract-reason').val() != 'Other') {
                                                        if (this_btn.attr('data-type') == 'dentist') {
                                                            fireGoogleAnalyticsEvent('Contract Dentist', 'Cancel', $('.recipe-popup #cancel-contract-reason').val());
                                                        } else if (this_btn.attr('data-type') == 'patient') {
                                                            fireGoogleAnalyticsEvent('Contract Patient', 'Cancel', $('.recipe-popup #cancel-contract-reason').val());
                                                        }
                                                    } else if ($('.popup-cancel-contract #cancel-contract-reason').val() == 'Other') {
                                                        if (this_btn.attr('data-type') == 'dentist') {
                                                            fireGoogleAnalyticsEvent('Contract Dentist', 'Cancel', $('.recipe-popup #cancel-contract-other-reason').val().trim());
                                                        } else if (this_btn.attr('data-type') == 'patient') {
                                                            fireGoogleAnalyticsEvent('Contract Patient', 'Cancel', $('.recipe-popup #cancel-contract-other-reason').val().trim());
                                                        }
                                                    }

                                                    var cancellation_ajax_data = {
                                                        contract: this_btn.attr('data-contract'),
                                                        status: 'cancelled',
                                                        comments: $('.recipe-popup #cancel-contract-comments').val().trim()
                                                    };

                                                    if ($('.recipe-popup #cancel-contract-other-reason').length) {
                                                        cancellation_ajax_data.reason = $('.recipe-popup #cancel-contract-other-reason').val().trim();
                                                    } else {
                                                        cancellation_ajax_data.reason = $('.recipe-popup #cancel-contract-reason option:selected').html();
                                                    }

                                                    const EthereumTx = require('ethereumjs-tx');
                                                    var nonce = await dApp.web3_1_0.eth.getTransactionCount(global_state.account, 'pending');

                                                    var contract_cancellation_transaction_obj = {
                                                        gasLimit: dApp.web3_1_0.utils.toHex(Math.round(gas_cost_for_contract_cancellation + (gas_cost_for_contract_cancellation * projectData.variables.bonusPercentagesToGasEstimations / 100))),
                                                        gasPrice: dApp.web3_1_0.utils.toHex(on_page_load_gas_price),
                                                        from: global_state.account,
                                                        nonce: dApp.web3_1_0.utils.toHex(nonce),
                                                        chainId: dApp.chain_id,
                                                        data: contract_cancellation_function_abi,
                                                        to: assurance_config.assurance_proxy_address
                                                    };

                                                    const contract_cancellation_transaction = new EthereumTx(contract_cancellation_transaction_obj);
                                                    //signing the transaction
                                                    contract_cancellation_transaction.sign(new Buffer(transaction_key, 'hex'));

                                                    //sending the transaction
                                                    dApp.web3_1_0.eth.sendSignedTransaction('0x' + contract_cancellation_transaction.serialize().toString('hex'), function (err, transactionHash) {
                                                        var execute_ajax = true;
                                                        //doing setinterval check to check if the smart creation transaction got mined
                                                        var contract_cancellation_interval_check = setInterval(async function () {
                                                            var contract_cancellation_status = await dApp.web3_1_0.eth.getTransactionReceipt(transactionHash);
                                                            if (contract_cancellation_status != null && basic.property_exists(contract_cancellation_status, 'status')) {
                                                                if (contract_cancellation_status.status && execute_ajax) {
                                                                    execute_ajax = false;
                                                                    clearInterval(contract_cancellation_interval_check);

                                                                    $.ajax({
                                                                        type: 'POST',
                                                                        url: '/update-contract-status',
                                                                        dataType: 'json',
                                                                        data: cancellation_ajax_data,
                                                                        headers: {
                                                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                                        },
                                                                        success: function (inner_response) {
                                                                            showLoader();
                                                                            if (inner_response.success) {
                                                                                window.location = '/' + inner_response.path + '/contract/' + this_btn.attr('data-contract');
                                                                            } else if (inner_response.error) {
                                                                                hideLoader();
                                                                                basic.showAlert(inner_response.error, '', true);
                                                                            }
                                                                        }
                                                                    });
                                                                }
                                                            }
                                                        }, 3000);
                                                    });
                                                }, 2000);
                                            }
                                        }
                                    });
                                } else if (response.error) {
                                    basic.showAlert(response.error, '', true);
                                }
                            }
                        });
                    }
                }
            } else {
                $.ajax({
                    type: 'POST',
                    url: '/get-popup-cancel-contract',
                    dataType: 'json',
                    data: {
                        contract: this_btn.attr('data-contract')
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            basic.closeDialog();
                            basic.showDialog(response.success, 'popup-cancel-contract', null, true);

                            projectData.initiators.fixSelectsOnMac();

                            $('.popup-cancel-contract #cancel-contract-reason').on('change', function() {
                                if ($(this).find('option:selected').attr('data-open-bonus-field') == 'true') {
                                    $('.camp-for-row').html('<div class="popup-row"><label for="cancel-contract-other-reason" class="inline-block-top">Other reason:</label><input type="text" id="cancel-contract-other-reason" placeholder="Please specify" class="pencil-background inline-block-top" maxlength="255"/></div>');
                                } else {
                                    $('.camp-for-row').html('');
                                }
                            });

                            $('.popup-cancel-contract .cancel-contract-popup-confirmation').click(function() {
                                if ($('.popup-cancel-contract #cancel-contract-other-reason').length && $('.popup-cancel-contract #cancel-contract-other-reason').val().trim() == '') {
                                    basic.showAlert('Please enter other reason.', '', true);
                                } else if ($('.popup-cancel-contract #cancel-contract-reason').val() == null) {
                                    basic.showAlert('Please select cancellation reason.', '', true);
                                } /*else if ($('.popup-cancel-contract #cancel-contract-comments').val().trim() == '') {
                                    basic.showAlert('Please enter comments.', '', true);
                                }*/ else {
                                    var data = {
                                        contract: this_btn.attr('data-contract'),
                                        status: 'cancelled',
                                        comments: $('.popup-cancel-contract #cancel-contract-comments').val().trim()
                                    };

                                    if ($('.popup-cancel-contract #cancel-contract-other-reason').length) {
                                        data.reason = $('.popup-cancel-contract #cancel-contract-other-reason').val().trim();
                                    } else {
                                        data.reason = $('#cancel-contract-reason option:selected').html();
                                    }

                                    if ($('.popup-cancel-contract #cancel-contract-reason').val() != '' && $('.popup-cancel-contract #cancel-contract-reason').val() != 'Other') {
                                        if (this_btn.attr('data-type') == 'dentist') {
                                            fireGoogleAnalyticsEvent('Contract Dentist', 'Cancel', $('.popup-cancel-contract #cancel-contract-reason').val());
                                        } else if (this_btn.attr('data-type') == 'patient') {
                                            fireGoogleAnalyticsEvent('Contract Patient', 'Cancel', $('.popup-cancel-contract #cancel-contract-reason').val());
                                        } else if (this_btn.attr('data-type') == 'patient-rejecting') {
                                            fireGoogleAnalyticsEvent('Contract Patient Rejected', 'Reject', $('.popup-cancel-contract #cancel-contract-reason').val());
                                        }
                                    } else if ($('.popup-cancel-contract #cancel-contract-reason').val() == 'Other') {

                                        if (this_btn.attr('data-type') == 'dentist') {
                                            fireGoogleAnalyticsEvent('Contract Dentist', 'Cancel', $('.popup-cancel-contract #cancel-contract-other-reason').val());
                                        } else if (this_btn.attr('data-type') == 'patient') {
                                            fireGoogleAnalyticsEvent('Contract Patient', 'Cancel', $('.popup-cancel-contract #cancel-contract-other-reason').val());
                                        } else if (this_btn.attr('data-type') == 'patient-rejecting') {
                                            fireGoogleAnalyticsEvent('Contract Patient Rejected', 'Reject', $('.popup-cancel-contract #cancel-contract-other-reason').val());
                                        }
                                    }

                                    $.ajax({
                                        type: 'POST',
                                        url: '/update-contract-status',
                                        dataType: 'json',
                                        data: data,
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function (inner_response) {
                                            showLoader();
                                            if (inner_response.success) {
                                                window.location = '/' + inner_response.path + '/contract/' + this_btn.attr('data-contract');
                                            } else if (inner_response.error) {
                                                hideLoader();
                                                basic.showAlert(inner_response.error, '', true);
                                            }
                                        }
                                    });
                                }
                            });
                        } else if (response.error) {
                            basic.showAlert('Wrong contract.', '', true);
                        }
                    }
                });
            }
        })
    }
}

function styleUploadFileButton(button_label, render_pdf, encrypted_pdf_content, for_transactions)    {
    if (button_label == undefined) {
        button_label = null;
    }
    if (render_pdf == undefined) {
        render_pdf = null;
    }
    if (encrypted_pdf_content == undefined) {
        encrypted_pdf_content = null;
    }
    if (for_transactions == undefined) {
        for_transactions = null;
    }

    $('.custom-upload-file').each(function(key, form){
        var this_btn = $(this);
        var caching = false;
        if (this_btn.hasClass('caching')) {
            caching = true;
        }

        var this_btn_parent = this_btn.closest('.upload-file-container');
        this_btn_parent.find('.btn-wrapper').append("<label for='"+this_btn_parent.attr('data-id')+"'  role='button' class='white-blue-green-btn display-block-important'><span class='display-block-important fs-18'>"+this_btn_parent.attr('data-label')+"</span></label>");

        var inputs = document.querySelectorAll('.custom-upload-file');
        Array.prototype.forEach.call(inputs, function(input) {
            input.addEventListener('change', function(e) {
                var fileName = '';
                if (this.files && this.files.length > 1) {
                    fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length);
                } else {
                    fileName = e.target.value.split('\\').pop();
                }

                if (this_btn.attr('id') == 'upload-keystore-file') {
                    var uploaded_file = this.files[0];
                    var reader = new FileReader();
                    reader.addEventListener('load', async function (e) {
                        // get all multiple user addresses
                        var currentUserAddressesResponse = await getUserAddresses($('.proof-of-address').attr('data-id'));
                        if (currentUserAddressesResponse.success) {
                            var currentUserAddresses = [];
                            for (var i = 0, len = currentUserAddressesResponse.data.length; i < len; i += 1) {
                                currentUserAddresses.push(projectData.utils.checksumAddress(currentUserAddressesResponse.data[i].dcn_address));
                            }

                            if (projectData.utils.isJsonString(e.target.result) && basic.property_exists(JSON.parse(e.target.result), 'address')) {
                                var keystore_string = e.target.result;
                                var keystoreFileAddress = projectData.utils.checksumAddress(('0x' + JSON.parse(e.target.result).address));
                                if (currentUserAddresses.indexOf(keystoreFileAddress) != -1) {
                                    if (caching) {
                                        $('.proof-of-address .on-change-result').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-top-5"><div class="fs-14 light-gray-color text-center padding-bottom-10 file-name">'+fileName+'</div><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-secret-key-password">Secret password:</label><input type="password" id="your-secret-key-password" maxlength="100" class="full-rounded"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn cache-key-btn">REMEMBER</a></div></div>');
                                        bindCacheKeyEvent(keystore_string);
                                    } else {
                                        var btn_name = 'VERIFY';
                                        if (button_label != null) {
                                            btn_name = button_label;
                                        }
                                        $('.proof-of-address .on-change-result').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-top-5"><div class="fs-14 light-gray-color text-center padding-bottom-10 file-name">'+fileName+'</div><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-secret-key-password">Secret password:</label><input type="password" id="your-secret-key-password" maxlength="100" class="full-rounded"/></div><div class="checkbox-container"><div class="pretty p-svg p-curve on-white-background margin-bottom-0"><input type="checkbox" id="remember-my-keystore-file" checked/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label class="fs-14 calibri-bold" for="remember-my-keystore-file">Remember my keystore file <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Remembering your keystore file allows for easier and faster transactions. It is stored only in your browser and nobody else has access to it."></i></label></div></div></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn verify-address-btn">'+btn_name+'</a></div></div>');
                                        projectData.initiators.initTooltips();

                                        if (render_pdf != null && encrypted_pdf_content != null) {
                                            //if we have to render pdf
                                            $('.proof-of-address .verify-address-btn').click(async function() {
                                                //if remember me option is checked
                                                if ($('#remember-my-keystore-file').is(':checked')) {
                                                    //if remember me option is checked
                                                    var currentAccountsStorage = localStorage.getItem('current-accounts');
                                                    if (currentAccountsStorage != null && currentAccountsStorage != undefined) {
                                                        var currentAccounts = JSON.parse(currentAccountsStorage);
                                                        currentAccounts.push({'address' : keystoreFileAddress, 'keystore' : keystore_string});

                                                        localStorage.setItem('current-accounts', JSON.stringify(currentAccounts));
                                                    } else {
                                                        localStorage.setItem('current-accounts', JSON.stringify([{'address' : keystoreFileAddress, 'keystore' : keystore_string}]));
                                                    }
                                                }

                                                var decrypt_response = await decryptDataByKeystore(encrypted_pdf_content, keystore_string, $('.proof-of-address #your-secret-key-password').val().trim());
                                                if (decrypt_response.success) {
                                                    var render_form = $('form#render-pdf');
                                                    basic.closeDialog();
                                                    render_form.find('input[name="pdf_data"]').val(decrypt_response.success.decrypted);
                                                    render_form.submit();
                                                } else if (decrypt_response.error) {
                                                    basic.showAlert(decrypt_response.message, '', true);
                                                }
                                            });
                                        } else {
                                            if (for_transactions != null) {
                                                //if we have to validate this address (store it in our local db)
                                                bindTransactionAddressVerify(keystore_string, keystoreFileAddress);
                                            } else {
                                                //if we have to validate this address (store it in our local db)
                                                bindVerifyAddressEvent(keystore_string);
                                            }
                                        }
                                    }
                                } else {
                                    $('#upload-keystore-file').val('');
                                    basic.showAlert('Please upload valid keystore file which is related to the Wallet Address saved in your profile.', '', true);
                                }
                            } else {
                                $('#upload-keystore-file').val('');
                                basic.showAlert('Please upload valid keystore file which is related to the Wallet Address saved in your profile.', '', true);
                            }
                        } else {
                            basic.showAlert('You don\'t have any Wallet Address saved in our database.', '', true);
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
    $('body').on('click', '.custom-google-label-style label', function() {
        $(this).addClass('active-label');
        if ($('.custom-google-label-style').attr('data-input-blue-green-border') == 'true') {
            $(this).parent().find('input').addClass('blue-green-border');
        }
    });

    $('body').on('keyup change focusout', '.custom-google-label-style input', function() {
        var value = $(this).val().trim();
        if (value.length) {
            $(this).closest('.custom-google-label-style').find('label').addClass('active-label');
            if ($(this).closest('.custom-google-label-style').attr('data-input-blue-green-border') == 'true') {
                $(this).addClass('blue-green-border');
            }
        } else {
            $(this).closest('.custom-google-label-style').find('label').removeClass('active-label');
            if ($(this).closest('.custom-google-label-style').attr('data-input-blue-green-border') == 'true') {
                $(this).removeClass('blue-green-border');
            }
        }
    });
}
bindGoogleAlikeButtonsEvents();

//bind the logic when address is not verified
function bindVerifyAddressLogic(for_transactions) {
    if (for_transactions === undefined) {
        for_transactions = null;
        styleUploadFileButton();
    } else {
        styleUploadFileButton(null, null, null, true);
    }

    $('.enter-private-key').unbind().click(function() {
        $('.proof-of-address .on-change-result').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-top-20"><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-private-key">Your Private Key:</label><input type="text" id="your-private-key" maxlength="64" class="full-rounded"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn verify-address-btn">VERIFY</a></div></div>');
        projectData.initiators.initTooltips();
        $('.proof-of-address #upload-keystore-file').val('');

        if (for_transactions != null) {
            bindTransactionAddressVerify();
        } else {
            bindVerifyAddressEvent();
        }
    });

    $('.upload-file-container button').unbind().click(function() {
        $('.proof-of-address .on-change-result').html('');
    });
}

function bindVerifyAddressEvent(keystore_file, render_pdf, encrypted_pdf_content) {
    if (keystore_file === undefined) {
        keystore_file = null;
    }
    if (render_pdf === undefined) {
        render_pdf = null;
    }
    if (encrypted_pdf_content === undefined) {
        encrypted_pdf_content = null;
    }
    $('.proof-of-address .verify-address-btn').click(async function() {
        // get all multiple user addresses
        var currentUserAddressesResponse = await getUserAddresses($('.proof-of-address').attr('data-id'));
        if (currentUserAddressesResponse.success) {
            var keystoreFileAddress = projectData.utils.checksumAddress('0x' + JSON.parse(keystore_file).address);
            var currentUserAddresses = [];
            for(var i = 0, len = currentUserAddressesResponse.data.length; i< len; i+=1) {
                currentUserAddresses.push(projectData.utils.checksumAddress(currentUserAddressesResponse.data[i].dcn_address));
            }

            console.log(currentUserAddresses, 'currentUserAddresses1');

            if (keystore_file != null) {
                //import with keystore
                if (currentUserAddresses.indexOf(keystoreFileAddress) != -1) {
                    basic.showAlert('Please enter valid keystore file for your Wallet Address.', '', true);
                } else if ($('.proof-of-address #your-secret-key-password').val().trim() == '' || $('.proof-of-address #your-secret-key-password').val().trim().length > 100 || $('.proof-of-address #your-secret-key-password').val().trim().length < 6) {
                    basic.showAlert('Please enter valid secret key password with length between 6 and 100 symbols.', '', true);
                } else {
                    showLoader();
                    setTimeout(function() {
                        var import_response = importKeystoreFile(keystore_file, $('.proof-of-address #your-secret-key-password').val().trim());
                        if (import_response.success) {
                            //if remember me option is checked
                            if ($('#remember-my-keystore-file').is(':checked')) {
                                //if remember me option is checked
                                var currentAccountsStorage = localStorage.getItem('current-accounts');
                                if (currentAccountsStorage != null && currentAccountsStorage != undefined) {
                                    var currentAccounts = JSON.parse(currentAccountsStorage);
                                    currentAccounts.push({'address' : keystoreFileAddress, 'keystore' : import_response.success});

                                    localStorage.setItem('current-accounts', JSON.stringify(currentAccounts));
                                } else {
                                    localStorage.setItem('current-accounts', JSON.stringify([{'address' : keystoreFileAddress, 'keystore' : import_response.success}]));
                                }
                            }

                            $.ajax({
                                type: 'POST',
                                url: '/update-public-keys',
                                dataType: 'json',
                                data: {
                                    address: keystoreFileAddress,
                                    public_key: import_response.public_key
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (inner_response) {
                                    hideLoader();
                                    if (inner_response.success) {
                                        $('.proof-of-address').remove();
                                        $('.proof-success').fadeIn(1500);
                                    } else {
                                        basic.showAlert(inner_response.error, '', true);
                                    }
                                }
                            });
                        } else if (import_response.error) {
                            hideLoader();
                            basic.showAlert(import_response.message, '', true);
                        }
                    }, 1000);
                }
            } else {
                //import with private key
                if ($('.proof-of-address #your-private-key').val().trim() == '' || $('.proof-of-address #your-private-key').val().trim().length > 64) {
                    basic.showAlert('Please enter valid private key.', '', true);
                } else {
                    showLoader();
                    setTimeout(async function () {
                        if (render_pdf != null) {
                            var render_form = $('form#render-pdf');
                            var decrypted_pdf_response = await decryptDataByPlainKey(encrypted_pdf_content, $('.proof-of-address #your-private-key').val().trim());

                            hideLoader();
                            if (decrypted_pdf_response.success) {

                                basic.closeDialog();
                                render_form.find('input[name="pdf_data"]').val(decrypted_pdf_response.success.decrypted);
                                render_form.submit();
                            } else if (decrypted_pdf_response.error) {
                                basic.showAlert(decrypted_pdf_response.message, '', true);
                            }
                        } else {
                            var import_response = importPrivateKey($('.proof-of-address #your-private-key').val().trim());
                            //now with the address and the public key received from the nodejs api update the db
                            if (import_response.success) {
                                //checking if fake private key or just miss spell it
                                if (currentUserAddresses.indexOf(projectData.utils.checksumAddress(import_response.address)) != -1) {
                                    basic.showAlert('Please enter private key related to the Wallet Address you have entered in Wallet Address field.', '', true);
                                    hideLoader();
                                } else {

                                    $.ajax({
                                        type: 'POST',
                                        url: '/update-public-keys',
                                        dataType: 'json',
                                        data: {
                                            address: projectData.utils.checksumAddress(import_response.address),
                                            public_key: import_response.public_key
                                        },
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function (inner_response) {
                                            hideLoader();
                                            if (inner_response.success) {
                                                $('.proof-of-address').remove();
                                                $('.proof-success').fadeIn(1500);
                                            } else {
                                                basic.showAlert(inner_response.error, '', true);
                                            }
                                        }
                                    });
                                }
                            } else if (import_response.error) {
                                hideLoader();
                                basic.showAlert(import_response.message, '', true);
                            }
                        }
                    }, 1000);
                }
            }
        } else {
            basic.showAlert('You don\'t have any Wallet Address saved in our database.', '', true);
        }
    });
}

function bindTransactionAddressVerify(keystore_file, keystoreFileAddress) {
    if (keystore_file === undefined) {
        keystore_file = null;
    }
    if (keystoreFileAddress === undefined) {
        keystoreFileAddress = null;
    }
    $('.proof-of-address .verify-address-btn').click(function() {
        showLoader();
        setTimeout(async function() {
            if (keystore_file != null) {
                //import with keystore
                if ($('.proof-of-address #your-secret-key-password').val().trim() == '' || $('.proof-of-address #your-secret-key-password').val().trim().length > 100 || $('.proof-of-address #your-secret-key-password').val().trim().length < 6) {
                    hideLoader();
                    basic.showAlert('Please enter valid secret key password with length between 6 and 100 symbols.', '', true);
                } else {
                    var decrypt_response = decryptKeystore(keystore_file, $('.proof-of-address #your-secret-key-password').val().trim());
                    if (decrypt_response.success) {
                        //if remember me option is checked
                        if ($('#remember-my-keystore-file').is(':checked')) {
                            //if remember me option is checked
                            var currentAccountsStorage = localStorage.getItem('current-accounts');
                            if (currentAccountsStorage != null && currentAccountsStorage != undefined) {
                                var currentAccounts = JSON.parse(currentAccountsStorage);
                                currentAccounts.push({'address' : keystoreFileAddress, 'keystore' : keystore_file});

                                localStorage.setItem('current-accounts', JSON.stringify(currentAccounts));
                            } else {
                                localStorage.setItem('current-accounts', JSON.stringify([{'address' : keystoreFileAddress, 'keystore' : keystore_file}]));
                            }
                        }

                        $.event.trigger({
                            type: 'on-transaction-recipe-agree',
                            time: new Date(),
                            response_data: decrypt_response.to_string
                        });
                    } else if (decrypt_response.error) {
                        basic.showAlert(decrypt_response.message, '', true);
                        hideLoader();
                    }
                }
            } else {
                //import with private key
                if ($('.proof-of-address #your-private-key').val().trim() == '' || $('.proof-of-address #your-private-key').val().trim().length > 64) {
                    basic.showAlert('Please enter valid private key.', '', true);
                } else {
                    var import_response = importPrivateKey($('.proof-of-address #your-private-key').val().trim());
                    if (import_response.success) {
                        //checking if fake private key or just miss spell it
                        if (global_state.account != projectData.utils.checksumAddress(import_response.address)) {
                            basic.showAlert('Please enter private key related to the Wallet Address you have saved in your profile.', '', true);
                            hideLoader();
                        } else {

                            $.event.trigger({
                                type: 'on-transaction-recipe-agree',
                                time: new Date(),
                                response_data: $('.proof-of-address #your-private-key').val().trim()
                            });
                        }
                    } else if (import_response.error) {
                        basic.showAlert(import_response.message, '', true);
                        hideLoader();
                    }
                }
            }
        }, 1000);
    });
}

function bindCacheKeyEvent(keystore_file) {
    $('.proof-of-address .cache-key-btn').click(async function() {
        // get all multiple user addresses
        var currentUserAddressesResponse = await getUserAddresses($('.proof-of-address').attr('data-id'));
        if (currentUserAddressesResponse.success) {
            var currentUserAddresses = [];
            for (var i = 0, len = currentUserAddressesResponse.data.length; i < len; i += 1) {
                currentUserAddresses.push(projectData.utils.checksumAddress(currentUserAddressesResponse.data[i].dcn_address));
            }

            console.log(currentUserAddresses, 'currentUserAddresses2');

            var keystoreFileAddress = projectData.utils.checksumAddress('0x' + JSON.parse(keystore_file).address);
            if (currentUserAddresses.indexOf(keystoreFileAddress) != -1) {
                if ($('.proof-of-address #your-secret-key-password').val().trim() == '' || $('.proof-of-address #your-secret-key-password').val().trim().length > 100 || $('.proof-of-address #your-secret-key-password').val().trim().length < 6) {
                    basic.showAlert('Please enter valid secret key password with length between 6 and 100 symbols.', '', true);
                } else {
                    showLoader();
                    setTimeout(function() {
                        var import_response = importKeystoreFile(keystore_file, $('.proof-of-address #your-secret-key-password').val().trim());
                        if (import_response.success) {
                            //if remember me option is checked
                            var currentAccountsStorage = localStorage.getItem('current-accounts');
                            if (currentAccountsStorage != null && currentAccountsStorage != undefined) {
                                var currentAccounts = JSON.parse(currentAccountsStorage);
                                currentAccounts.push({'address' : keystoreFileAddress, 'keystore' : import_response.success});

                                localStorage.setItem('current-accounts', JSON.stringify(currentAccounts));
                            } else {
                                localStorage.setItem('current-accounts', JSON.stringify([{'address' : keystoreFileAddress, 'keystore' : import_response.success}]));
                            }

                            $.ajax({
                                type: 'POST',
                                url: '/update-public-keys',
                                dataType: 'json',
                                data: {
                                    address: keystoreFileAddress,
                                    public_key: import_response.public_key
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (inner_response) {
                                    hideLoader();
                                    $('.remember-my-wallet-camp').remove();
                                    basic.showAlert('Your wallet has been remembered successfully. If you want to delete your private key or keystore file you can do this from Manage Privacy section in your profile.', '', true);
                                }
                            });
                        } else if (import_response.error) {
                            hideLoader();
                            basic.showAlert(import_response.message, '', true);
                        }
                    }, 1000);
                }
            } else {
                basic.showAlert('Please enter valid keystore file for your Wallet Address.', '', true);
            }
        } else {
            basic.showAlert('You don\'t have any Wallet Address saved in our database.', '', true);
        }
    });
}

function openCacheKeyPopup(encrypted_pdf_content) {
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
                basic.closeDialog();
                basic.showDialog('<div class="lato-regular fs-24 text-center padding-bottom-40 padding-top-15">Unlock the PDF file with your private key or your keystore file</div>' + response.success, 'address-validation-or-remember-me', null, true);

                styleUploadFileButton('UNLOCK', true, encrypted_pdf_content);

                $('.enter-private-key').unbind().click(function() {
                    $('.proof-of-address .on-change-result').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-top-20"><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-private-key">Your Private Key:</label><input type="text" id="your-private-key" maxlength="64" class="full-rounded"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn verify-address-btn">UNLOCK</a></div></div>');
                    projectData.initiators.initTooltips();
                    $('.proof-of-address #upload-keystore-file').val('');
                    bindVerifyAddressEvent(null, true, encrypted_pdf_content);
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
        if (value_element.is('input')) {
            $('.camping-for-validation').html('<div class="single-row proof-of-address padding-bottom-20"><div class="text-center calibri-bold fs-18 padding-top-20 padding-bottom-15">PLEASE VERIFY YOU OWN THIS ADDRESS</div><div class="container-fluid"><div class="row fs-0"><div class="col-xs-12 col-sm-5 inline-block padding-left-xs-15"><a href="javascript:void(0)" class="blue-green-white-btn text-center enter-private-key display-block-important fs-18 line-height-18"><span>Enter your Private Key<div class="fs-16">(not recommended)</div></span></a></div><div class="col-xs-12 col-sm-2 text-center calibri-bold fs-20 inline-block">or</div><div class="col-xs-12 col-sm-5 inline-block padding-right-xs-15"><div class="upload-file-container" data-id="upload-keystore-file" data-label="Upload your Keystore file"><input type="file" id="upload-keystore-file" class="custom-upload-file hide-input"/><div class="btn-wrapper"></div></div></div></div><div class="row on-change-result"></div></div></div><div class="single-row proof-success no-transition padding-top-20 padding-bottom-20 fs-20 calibri-bold text-center">Successful address verification.</div>');
            $('.proof-of-address').addClass('proof-failed');

            bindVerifyAddressLogic();
            error = true;
        } else {
            $('.proof-of-address').addClass('proof-failed');
            error = true;
        }
    }
    return error;
}

function triggerIframeSizeEventForParent(width, height) {
    window.parent.postMessage(
        {
            event_id: 'iframe_size_event',
            data: {
                width: width,
                height: height
            }
        },
        "*"
    );
}

function initDataTable(filter_param, stop_table_init)    {
    if (filter_param == undefined) {
        filter_param = null;
    }
    if (stop_table_init == undefined) {
        stop_table_init = null;
    }

    var params = projectData.utils.getGETParameters();
    if (basic.property_exists(params, 'status') && filter_param == null) {
        filter_param = [params.status];
    }

    if ($('table.table.table-without-reorder').length > 0) {
        if (stop_table_init == null) {
            $('table.table.table-without-reorder').DataTable({
                ordering: true,
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: 'no-sort'
                }],
                aaSorting: []
            });
        }

        var pending_check = 'checked';
        var active_check = 'checked';
        var awaiting_approval_check = 'checked';
        var awaiting_payment_check = 'checked';
        var cancelled_check = 'checked';

        if (filter_param != null) {
            if (basic.property_exists(params, 'status')) {
                filter_param.push(params.status);
            }
            if ($.inArray('pending', filter_param) != -1) {
                pending_check = 'checked';
            } else {
                pending_check = '';
            }

            if ($.inArray('active', filter_param) != -1) {
                active_check = 'checked';
            } else {
                active_check = '';
            }
            if ($.inArray('awaiting-approval', filter_param) != -1) {
                awaiting_approval_check = 'checked';
            } else {
                awaiting_approval_check = '';
            }
            if ($.inArray('awaiting-payment', filter_param) != -1) {
                awaiting_payment_check = 'checked';
            } else {
                awaiting_payment_check = '';
            }
            if ($.inArray('cancelled', filter_param) != -1) {
                cancelled_check = 'checked';
            } else {
                cancelled_check = '';
            }
        }

        if ($('table.table.table-without-reorder').hasClass('my-contracts')) {
            $('.dataTables_filter').append('<div class="custom-filter"><a href="javascript:void(0)" class="custom-btn"><img alt="Filter icon" class="filter-icon" src="/assets/images/filter-icon.svg"/> Filter <img alt="Caret icon" class="caret-down" src="/assets/images/caret-down.svg"/><div class="custom-filter-body"><div class="custom-title">Filter by Status</div><div class="filter-row"><input type="checkbox" class="filter-contracts" id="pending" '+pending_check+'/> <label for="pending">Pending</label></div><div class="filter-row"><input type="checkbox" class="filter-contracts" id="active" '+active_check+'/> <label for="active">Active</label></div><div class="filter-row"><input type="checkbox" class="filter-contracts" id="awaiting-payment" '+awaiting_payment_check+'/> <label for="awaiting-payment">Active - awaiting payment</label></div><div class="filter-row"><input type="checkbox" class="filter-contracts" id="awaiting-approval" '+awaiting_approval_check+'/> <label for="awaiting-approval">Active - awaiting approval</label></div><div class="filter-row"><input type="checkbox" class="filter-contracts" id="cancelled" '+cancelled_check+'/> <label for="cancelled">Cancelled</label></div></div></a></div>');

            if (basic.isMobile()) {
                $('.my-contracts-container .custom-btn').click(function() { $('.my-contracts-container .custom-filter-body').toggle(300)});
            }

            $('.dataTables_filter > label > input').addClass('custom-input green-arrow-background').attr('placeholder', 'Search for contract');

            triggerIframeSizeEventForParent($('.my-contracts-container').width(), $('.my-contracts-container').height());

            $('input[type="checkbox"].filter-contracts').on('change', function() {
                var filter_arr = [];
                for(var i = 0, len = $('input[type="checkbox"].filter-contracts').length; i < len; i+=1) {
                    if ($('input[type="checkbox"].filter-contracts').eq(i).is(':checked')) {
                        filter_arr.push($('input[type="checkbox"].filter-contracts').eq(i).attr('id'));
                    }
                }

                $('.table-container').html('<div class="table-response-layer padding-top-80 text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img src="/assets/images/loader.gif" class="max-width-160" alt="Loader"></figure>');
                $.ajax({
                    type: 'POST',
                    url: '/filter-my-contracts',
                    dataType: 'json',
                    data: {
                        filter_arr: filter_arr
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.table-container').html(response.success);
                            initDataTable(filter_arr);

                            hideLoader();

                            triggerIframeSizeEventForParent($('.my-contracts-container').width(), $('.my-contracts-container').height());
                        }
                    }
                });
            });
        }
    }
}

function onEnrichProfileFormSubmit() {
    $(document).on('submit', '.enrich-profile-container #enrich-profile', function(event) {
        var errors = false;
        var this_form = $(this);
        this_form.find('.error-handle').remove();
        if (this_form.find('[name="description"]').val().trim() == '') {
            errors = true;
            customErrorHandle(this_form.find('[name="description"]').parent(), 'Please enter short description.');
        }

        if (!errors) {
            if ($('.enrich-profile-container').attr('data-type') == 'dentist') {
                fireGoogleAnalyticsEvent('DentistRegistration', 'ClickSave', 'DentistDescr');
            } else if ($('.enrich-profile-container').attr('data-type') == 'clinic') {
                fireGoogleAnalyticsEvent('DentistRegistration', 'ClickSave', 'ClinicDescr');
            }
        } else {
            event.preventDefault();
        }
    });
}
onEnrichProfileFormSubmit();

function getContractData(contract, callback) {
    $.ajax({
        type: 'POST',
        url: '/get-contract-data',
        dataType: 'json',
        data: {
            contract: contract
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            callback(response);
        }
    });
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

async function getUserAddresses(id) {
    return await $.ajax({
        type: 'GET',
        url: 'https://api.dentacoin.com/api/wallet-addresses/' + id,
        dataType: 'json'
    });
}

async function checkIfFreeEmail(email) {
    return await $.ajax({
        type: 'POST',
        url: '/check-email',
        dataType: 'json',
        data: {
            email: email
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

async function checkEmailAndReturnData(email, type) {
    return await $.ajax({
        type: 'POST',
        url: '/check-email-and-return-data',
        dataType: 'json',
        data: {
            email: email,
            type: type
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

async function validatePhone(phone, country_code) {
    return await $.ajax({
        type: 'POST',
        url: 'https://api.dentacoin.com/api/phone/',
        dataType: 'json',
        data: {
            phone: phone,
            country_code: country_code
        }
    });
}

async function checkCaptcha(captcha) {
    return await $.ajax({
        type: 'POST',
        url: '/check-captcha',
        dataType: 'json',
        data: {
            captcha: captcha
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

function showWarningTestingVersion() {
    if (basic.cookies.get('warning-test-version') != '1' && !$('.my-contracts-iframe').length) {
        basic.showDialog('<div class="container-fluid"><div class="row fs-0"><div class="col-xs-12 col-sm-6 col-md-5 col-md-offset-1 inline-block"><img src="/assets/images/warning-pop-up.png" class="hide-xs"></div><div class="col-xs-12 col-md-5 col-sm-6 text-center inline-block padding-top-20 padding-bottom-20"><div class="warning"><img class="max-width-50" src="/assets/images/attention.svg" alt="attention icon"></div><div class="lato-bold fs-30" style="color: #ff8d8d;">WARNING:</div><div class="black-warning lato-bold fs-30 dark-color">THIS IS A TEST WEBSITE VERSION.</div><div class="additional-text padding-top-20 padding-bottom-20 fs-20">Please do not make any transactions as your funds will be lost.We will notify you via email when the official version is launched.</div><div class="btn-container"><a href="javascript:void(0)" class="white-blue-green-btn min-width-220 understood">I UNDERSTAND</a></div></div></div></div>', 'warning-test-version', true);
        $('.warning-test-version .understood').click(function() {
            if (basic.cookies.get('strictly_necessary_policy') != '1') {
                basic.showAlert('Please accept the strictly necessary cookies.', '', true);
            } else {
                basic.cookies.set('warning-test-version', 1);
                basic.closeDialog();
            }
        });

    }
}
showWarningTestingVersion();

//binding the refresh captcha event to existing button
function initCaptchaRefreshEvent()  {
    if ($('.refresh-captcha').length > 0)    {
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

function initPopupEvents(scroll_to_buy_section) {
    projectData.initiators.initTooltips();

    if ($('.contract-response-message .second-custom-close-btn')) {
        $('.contract-response-message .second-custom-close-btn').click(function() {
            $(this).closest('.contract-response-message').remove();
        });
    }

    if (scroll_to_buy_section != undefined) {
        $('.scroll-to-buy-section').click(function() {
            $('html, body').animate({
                scrollTop: $('.ready-to-purchase-with-external-api .form-container').offset().top
            }, {
                duration: 500
            });
        });
    }
}

function makeElementsInContractListWithSameHeight() {
    if ($('.contract-tile').length) {
        $('.contract-tile .tile-wrapper').outerHeight('auto');
        var max_height = 0;
        for (var i = 0, len = $('.contract-tile .tile-wrapper').length; i < len; i += 1) {
            if ($('.contract-tile .tile-wrapper').eq(i).outerHeight() > max_height) {
                max_height = $('.contract-tile .tile-wrapper').eq(i).outerHeight();
            }
        }
        $('.contract-tile .tile-wrapper').outerHeight(max_height);
    }
}

function closeTooltipPopupsWhenClickedOutside() {
    $(document).on('click', function (e) {
        $('[data-toggle="popover"],[data-original-title]').each(function () {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
            }

        });
    });
}
closeTooltipPopupsWhenClickedOutside();

// generating qr code into popup so users can scan with Dentacoin Wallet and automatically finish the transactions
function generateQRCodeForDentacoinWalletScan(object) {
    basic.showDialog('<figure itemscope="" itemtype="http://schema.org/ImageObject" id="popup-qrcode"></figure>', 'qr-code-for-dentacoin-wallet-scan', true);

    var qrcode = new QRCode(document.getElementById('popup-qrcode'), {
        width : 500,
        height : 500
    });

    qrcode.makeCode(encodeURIComponent(object));

    setTimeout(function() {
        $('#popup-qrcode').removeAttr('title');
        hideLoader();
    }, 1000);
}

// track for $contract status change
function trackForContractStatusChange(contract, currentStatus) {
    var changeInStatusFound = false;
    setInterval(function() {
        if (allowAutomaticScripts) {
            $.ajax({
                type: 'POST',
                url: '/check-contract-status',
                dataType: 'json',
                data: {
                    contract: contract,
                    currentStatus: currentStatus
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: async function (response) {
                    if (response.success && !changeInStatusFound) {
                        changeInStatusFound = true;
                        window.location.reload();
                    }
                }
            });
        }
    }, 5000);
}

// =================================== GOOGLE ANALYTICS TRACKING LOGIC ======================================

function bindTrackerClickDownloadBrochure() {
    $(document).on('click', '.track-event-download-brochure', function(event) {
        event.preventDefault();
        fireGoogleAnalyticsEvent('Assets', 'Download', 'Assurance Brochure');

        window.open($(this).attr('href'));
    });
}
bindTrackerClickDownloadBrochure();

function bindTrackerClickSignUpToSeeDetails() {
    $(document).on('click', '.track-event-sign-up-to-see-details', function() {
        fireGoogleAnalyticsEvent('PatientRegistration', 'Signup', 'Contact Proposal Signup');
    });
}

function bindTrackerClickDentistSignFirstContract() {
    $(document).on('click', '.track-event-dentist-sign-first-contract', function(event) {
        event.preventDefault();
        fireGoogleAnalyticsEvent('Contract Dentist', 'Click', 'First Contract Start');

        window.open($(this).attr('href'));
    });
}

function bindTrackerClickDentistCreateContract() {
    $(document).on('click', '.track-event-dentist-create-contract', function(event) {
        event.preventDefault();
        fireGoogleAnalyticsEvent('Contract Dentist', 'Click', 'Contract Start');

        window.open($(this).attr('href'));
    });
}

function bindTrackerClickDentistCreateWallet() {
    $(document).on('click', '.track-event-dentist-create-wallet', function(event) {
        event.preventDefault();
        fireGoogleAnalyticsEvent('Contract Dentist', 'Click', 'Wallet Link');

        window.open($(this).attr('href'));
    });
}

$(document).on('click', '.logged-user-nav .application', function() {
    var this_btn = $(this);

    fireGoogleAnalyticsEvent('Tools', 'Click', this_btn.attr('data-platform'))
});

function fireGoogleAnalyticsEvent(category, action, label, value) {
    var event_obj = {
        'event_action' : action,
        'event_category': category,
        'event_label': label
    };

    if (value != undefined) {
        event_obj.value = value;
    }

    gtag('event', label, event_obj);
}

// =================================== /GOOGLE ANALYTICS TRACKING LOGIC ======================================

function checkIfClickedOutsideElement(id, callback) {
    var specifiedElement = document.getElementById(id);

    //I'm using "click" but it works with any event
    document.addEventListener('click', function(event) {
        var isClickInside = specifiedElement.contains(event.target);
        if (!isClickInside) {
            callback();
        }
    });
}

function multipleUseWalletAddressesLogic() {
    if ($('.search-input').length) {
        $('.search-input').on('focus', function() {
            $('.search-result').show();
        });

        $(document).on('click', '.search-result .search-body ul li a', function() {
            $('.search-input').val($(this).attr('data-value')).trigger('change');
            $('.search-result').hide();
        });

        checkIfClickedOutsideElement('search-result-parent', function() {
            $('.search-result').hide();
        });

        var ajaxSent = false;
        $(document).on('click', '.search-body ul li button', function() {
            var this_btn = $(this);
            var delete_address_book_wallet_address = {};
            delete_address_book_wallet_address.callback = function (result) {
                if (result && !ajaxSent) {
                    ajaxSent = true;
                    $.ajax({
                        type: 'POST',
                        url: '/delete-address',
                        data: {
                            id: this_btn.closest('li').attr('data-id')
                        },
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            ajaxSent = false;
                            if (response.success) {
                                this_btn.closest('li').remove();
                                $('.search-result').hide();
                                $('.search-input').val('');
                                basic.showAlert(response.message, '', true);
                            } else if (response.error) {
                                basic.showAlert(response.message, '', true);
                            }
                        }
                    });
                }
            };
            basic.showConfirm('Are you sure you want to remove this Wallet Address from Address Book?', '', delete_address_book_wallet_address, true);
        });

        $(document).on('click', '.search-result .search-footer .add-to-address-book', function() {
            basic.closeDialog();
            basic.showDialog('<h2 class="fs-24 fs-xs-22 padding-bottom-20 text-center">Save to Address Book</h2><div class="max-width-350 margin-0-auto"><label for="contact-name" class="fs-16 fs-xs-14">Name:</label><input type="text" id="contact-name" maxlength="50" class="custom-input"></div><div class="max-width-350 margin-0-auto padding-top-15"><label for="wallet-address" class="fs-16 fs-xs-14">Wallet Address:</label><input type="text" id="wallet-address" maxlength="42" class="custom-input"></div><div class="padding-top-20 padding-bottom-15 text-center"><a href="javascript:void(0);" class="blue-green-white-btn save-to-address-book min-width-160">Save</a></div>', 'popup-save-to-address-book', null, true);

            var ajaxSent = false;
            $('.save-to-address-book').click(function() {
                if ($('.popup-save-to-address-book #contact-name').val().trim() == '') {
                    basic.showAlert('Please enter name.', '', true);
                } else if ($('.popup-save-to-address-book #wallet-address').val().trim() == '' || !projectData.utils.innerAddressCheck($('.popup-save-to-address-book #wallet-address').val().trim())){
                    basic.showAlert('Please enter valid Wallet Address.', '', true);
                } else if (!ajaxSent) {
                    ajaxSent = true;
                    var addressName = $('.popup-save-to-address-book #contact-name').val().trim();
                    var walletAddress = $('.popup-save-to-address-book #wallet-address').val().trim();

                    $.ajax({
                        type: 'POST',
                        url: '/save-address',
                        data: {
                            name: addressName,
                            address: walletAddress
                        },
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            ajaxSent = false;
                            if (response.success) {
                                basic.closeDialog();
                                basic.showAlert(response.message, '', true);


                                if (response.addresses) {
                                    var addressesHtml = '';

                                    for(var i = 0, len = response.addresses.length; i < len; i+=1) {
                                        var addressLabel = '';
                                        if (response.addresses[i].dcn_address_label != '' && response.addresses[i].dcn_address_label != undefined && response.addresses[i].dcn_address_label != null) {
                                            addressLabel = response.addresses[i].dcn_address_label+' ('+response.addresses[i].dcn_address+')';
                                        } else {
                                            addressLabel = '('+response.addresses[i].dcn_address+')';
                                        }
                                        addressesHtml += '<li class="removeable-element fs-0" data-id="'+response.addresses[i].id+'"><a href="javascript:void(0);" class="inline-block" data-value="'+response.addresses[i].dcn_address+'">'+addressLabel+'</a><button type="button" class="remove-address-book-element inline-block">×</button></li>';
                                    }

                                    $('#addresses-list').html(addressesHtml);
                                }
                            } else if (response.error) {
                                basic.showAlert(response.message, '', true);
                            }
                        }
                    });
                }
            });
        });
    }
}