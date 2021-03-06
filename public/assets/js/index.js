console.log('Don\'t touch the code. Or do ... ¯\\_(ツ)_/¯');

var {getWeb3, importKeystoreFile, decryptKeystore, decryptDataByPlainKey, importPrivateKey, decryptDataByKeystore} = require('./helper');
var {config_variable} = require('./config');

if ($('body').attr('data-network') == 'mainnet') {
    var {assurance_config} = require('./assurance_config_mainnet');
} else if ($('body').attr('data-network') == 'rinkeby') {
    var {assurance_config} = require('./assurance_config_rinkeby');
}

console.log(assurance_config, 'assurance_config');

$(document).ready(async function() {
    await dApp.init();

    projectData.pagesData.onDocumentReady();

    fixButtonsFocus();
});

var allowAutomaticScripts = true;
$(window).bind('beforeunload',function(){
    allowAutomaticScripts = false;
    $(window).scrollTop(0);
});

$(window).on('load', function() {

});

$(window).on('resize', function() {
    if ($('.my-contracts-container').length) {
        triggerIframeSizeEventForParent($('.my-contracts-container').width(), $('.my-contracts-container').height());
    }
});

$(window).on('scroll', function()  {
    loadDeferImages();
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

// camping for event when user didn't accept strictly necessary cookies
$(document).on('cannotLoginBecauseOfMissingCookies', function (event) {
    basic.showAlert('Please accept the strictly necessary cookies in order to continue with logging in.', 'boobox-alert', true);
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
                if ($('.patient-contract-single-page-section').length && $('.patient-contract-single-page-section').attr('data-patient') != '') {
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
        },
        getMinAllowedAmount: function()  {
            return dApp.assurance_state_instance.methods.getMinAllowedAmount().call({}, function(error, result)   {
                if (!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        },
        getUsdOverDcn: function()  {
            return dApp.assurance_state_instance.methods.getUsdOverDcn().call({}, function(error, result)   {
                if (!error)  {
                    return result;
                }else {
                    console.error(error);
                }
            });
        }
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
        bonusPercentagesToGasEstimations: 10
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
        convertDcnToUsd: function(dcn_val) {
            if ($("[data-dcn-for-one-usd]").length) {
                return parseInt($("[data-usd-for-one-dcn]").attr('data-usd-for-one-dcn')) * dcn_val;
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
        },
        readURL: function(input, megaBytesLimit, allowedImagesExtensions, callback, failedMaxSizeCallback, failedExtensionsCallback) {
            if (input.files && input.files[0]) {
                var filename = input.files[0].name;

                // check file size
                if (megaBytesLimit < projectData.utils.bytesToMegabytes(input.files[0].size)) {
                    if (failedMaxSizeCallback != undefined) {
                        failedMaxSizeCallback();
                    }
                    return false;
                } else {
                    //check file extension
                    if ($.inArray(filename.split('.').pop().toLowerCase(), allowedImagesExtensions) !== -1) {
                        if ($('.avatar.module .error-handle').length) {
                            $('.avatar.module .error-handle').remove();
                        }

                        if (callback != undefined) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                callback(e, filename);
                            };
                            reader.readAsDataURL(input.files[0]);
                        }
                    } else {
                        if (failedExtensionsCallback != undefined) {
                            failedExtensionsCallback();
                        }

                        /*var allowedExtensionsHtml = '';
                        var firstLoop = true;
                        for(var i = 0, len = allowedImagesExtensions.length; i < len; i+=1) {
                            if (firstLoop) {
                                firstLoop = false;
                                allowedExtensionsHtml += allowedImagesExtensions[i];
                            } else {
                                allowedExtensionsHtml += ', ' + allowedImagesExtensions[i];
                            }
                        }

                        $(input).closest('.upload-btn-parent').append('<div class="error-handle task-error">'+$('.popup-body.translations').attr('data-translation-file-size-error')+allowedExtensionsHtml+' format.</div>');*/
                        return false;
                    }
                }
            }
        },
        clearUrlFromGetParam(regex) {
            // remove get param from url
            // example - projectData.utils.clearUrlFromGetParam(/[\?&]successful-withdraw=[^&]+/);
            history.replaceState && history.replaceState(
                null, '', location.pathname + location.search.replace(regex, '').replace(/^&/, '?')
            );
        }
    },
    requests: {
        getGasPrice: async function () {
            return await $.getJSON('https://api.etherscan.io/api?module=gastracker&action=gasoracle&apikey=' + config_variable.etherscan_api_key);
        }
    },
    initiators: {
        initDatepicker: function(startDate) {
            if ($('.datepicker').length > 0) {
                if (startDate == undefined) {
                    startDate = new Date(2020, 0, 1);
                }

                $('.datepicker').datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: new Date(parseInt(startDate) * 1000),
                    maxDate: new Date()
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
                clock = $('.clock').FlipClock(time_left, {
                    clockFace: 'DailyCounter',
                    autoStart: false,
                    showSeconds: false,
                    callbacks: {
                        stop: function() {
                            window.onbeforeunload = null;
                            window.location.reload();
                        }
                    }
                });
                clock.setCountdown(true);
                clock.start();
            }else {
                $('.countdown-section').hide();
            }
        },
        initTooltips: function() {
            if ($('[data-toggle="tooltip"]').length) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        initComboboxes: function() {
            $("select.combobox").each(function () {
                $(this).combobox();
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
                    fireGoogleAnalyticsEvent('Video', 'Play', 'Video Demo', demo_video_time_watched);
                });
            } else if ($('body').hasClass('forgotten-password')) {
                $('form#forgotten-password').on('submit', function(event) {
                    var this_form = $(this);
                    if (this_form.find('input[type="email"]').val().trim() == '' || !basic.validateEmail(this_form.find('input[type="email"]').val().trim())) {
                        basic.showAlert('Please try again with valid email.', 'boobox-alert', true);
                        event.preventDefault();
                    }
                });
            } else if ($('body').hasClass('password-recover')) {
                $('form#recover-password').on('submit', function(event) {
                    var this_form = $(this);
                    if (this_form.find('input[type="password"]').val().trim() == '' || this_form.find('input[type="password"]').val().trim().length < 8 || this_form.find('input[type="email"]').val().trim().length > 100) {
                        basic.showAlert('Please try again with valid password between 8 and 30 symbols.', 'boobox-alert', true);
                        event.preventDefault();
                    }
                });
            }
        },
        onDocumentReady: async function() {
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
                                if (allowAutomaticScripts) {
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
                                                        $('.contracts-list.slider').prepend('<a href="'+response.data[i].url+'" class="module contract-tile padding-bottom-10 pending"><div class="tile-wrapper fs-0"> <div class="inline-block-top figure-container"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Dentist avatar" src="'+response.data[i].dentistAvatar+'"><figcaption class="fs-14 calibri-light text-center padding-left-5 padding-right-5">'+response.data[i].status+'</figcaption></figure></div><div class="contract-info inline-block-top"><div class="calibri-bold fs-18 title">'+response.data[i].dentistName+'</div><time class="display-block fs-14 calibri-light">Sent on: '+response.data[i].createdAt+'</time><div class="lato-semibold fs-24 line-height-24">'+response.data[i].monthlyPremium+'$</div><div class="btn-container"><div class="white-blue-green-btn">'+response.data[i].btnLabel+'</div></div></div></div></a>', 0);
                                                        initSliders();
                                                    }
                                                }
                                            }
                                        }
                                    });
                                }
                            }, 5000);
                        } else if ($('.start-first-contract').length) {
                            var contractFound = false;
                            setInterval(function() {
                                if (!contractFound && allowAutomaticScripts) {
                                    $.ajax({
                                        type: 'POST',
                                        url: '/patient/check-contracts-count',
                                        dataType: 'json',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function (response) {
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
                        // dentist side
                        cancelContractEventInit();

                        if ($('.terms-and-conditions-long-list').length) {
                            $('.terms-and-conditions-long-list').mCustomScrollbar({
                                contentTouchScroll: false
                            });
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
                            await $.getScript('https://dentacoin.com/assets/libs/dentacoin-login-gateway/js/address-combined-login.js?v='+new Date().getTime(), function() {});
                        }

                        if ($('.terms-and-conditions-long-list').length) {
                            $('.terms-and-conditions-long-list').mCustomScrollbar({
                                contentTouchScroll: false
                            });
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
                                                    basic.showAlert('Please enter valid monthly premium proposal', 'boobox-alert', true);
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
                                                                basic.showDialog(response.success, '', 'boobox-alert', true);
                                                            } else if (response.error) {
                                                                basic.showAlert(response.error, 'boobox-alert', true);
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        } else if (response.error) {
                                            basic.showAlert(response.success, 'boobox-alert', true);
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
                                    basic.showAlert('This contract proposal has expired.', 'boobox-alert', true);
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
                                        basic.showAlert('Please sign the contract sample. Use your mouse or touch screen to sign.', 'boobox-alert', true);
                                    }else if (!this_form.find('input#terms').is(':checked')) {
                                        basic.showAlert('Please accept the Terms and Conditions', 'boobox-alert', true);
                                    }else if (!this_form.find('input#privacy-policy').is(':checked')) {
                                        basic.showAlert('Please accept the Privacy Policy', 'boobox-alert', true);
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
                            $('.terms-and-conditions-long-list').mCustomScrollbar({
                                contentTouchScroll: false
                            });
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
                    // reading the monthly premium from the smart contract
                    var usdOverDcn = await dApp.assurance_state_methods.getUsdOverDcn();
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

                            window.onbeforeunload = function(e) {
                                return '';
                            };

                            trackForContractStatusChange($('.patient-contract-single-page-section').attr('data-contract'), 'awaiting-payment');
                            trackForWalletSigning($('.patient-contract-single-page-section').attr('data-contract'), 'awaiting-approval');
                        } else if ($('.contract-header').hasClass('awaiting-approval')) {
                            var on_load_exiting_contract = await dApp.assurance_state_methods.getPatient($('.patient-contract-single-page-section').attr('data-patient'), $('.patient-contract-single-page-section').attr('data-dentist'));

                            if (usdOverDcn) {
                                dcn_needed_to_be_payed_to_dentist = Math.round(projectData.utils.convertUsdToDcn(parseInt(on_load_exiting_contract[4])));
                            } else {
                                dcn_needed_to_be_payed_to_dentist = parseInt(on_load_exiting_contract[5]);
                            }

                            nextWithdrawTimestamp = parseInt(on_load_exiting_contract[0]);

                            trackForContractStatusChange($('.patient-contract-single-page-section').attr('data-contract'), 'awaiting-approval');
                            trackForWalletCancel($('.patient-contract-single-page-section').attr('data-contract'));
                        }

                        var timer_label = '';
                        if (time_passed_since_signed > period_to_withdraw && current_patient_dcn_balance < dcn_needed_to_be_payed_to_dentist && dApp.grace_period > time_passed_since_signed % period_to_withdraw) {
                            next_payment_timestamp = (nextWithdrawTimestamp + dApp.grace_period) * 1000;
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                            next_payment_timestamp_unix = (nextWithdrawTimestamp + dApp.grace_period - now_timestamp);

                            timer_label = 'If you doesn\'t fill in '+projectData.utils.convertUsdToDcn(dcn_needed_to_be_payed_to_dentist)+' DCN inside your  Wallet Address the contract will be canceled in:';
                            $('.contract-header').html('ACTIVE - OVERDUE PAYMENT');
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
                        trackForWalletCancel($('.patient-contract-single-page-section').attr('data-contract'));

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

                        if (usdOverDcn) {
                            var monthly_premium_in_dcn = Math.round(projectData.utils.convertUsdToDcn(on_load_exiting_contract[4]));
                            var dcn_needed_to_be_payed_to_dentist = months_passed_for_reward * monthly_premium_in_dcn;
                            var monthly_premium_in_usd = parseInt(on_load_exiting_contract[4]);
                        } else {
                            var monthly_premium_in_dcn = parseInt(on_load_exiting_contract[5]);
                            var dcn_needed_to_be_payed_to_dentist = months_passed_for_reward * monthly_premium_in_dcn;
                            var monthly_premium_in_usd = Math.round(projectData.utils.convertDcnToUsd(monthly_premium_in_dcn));
                        }

                        var timer_label = '';
                        if (time_passed_since_signed > period_to_withdraw && months_passed_for_reward == 1 && current_patient_dcn_balance < dcn_needed_to_be_payed_to_dentist && dApp.grace_period > time_passed_since_signed % period_to_withdraw) {
                            next_payment_timestamp = (parseInt(on_load_exiting_contract[0]) + dApp.grace_period - now_timestamp) * 1000;
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                            next_payment_timestamp_unix = (parseInt(on_load_exiting_contract[0]) + dApp.grace_period - now_timestamp);

                            timer_label = 'If you doesn\'t fill in '+projectData.utils.convertUsdToDcn(dcn_needed_to_be_payed_to_dentist)+' DCN inside your  Wallet Address the contract will be canceled in:';
                            $('.contract-header').html('ACTIVE - OVERDUE PAYMENT');
                            $('.clock').addClass('red-background');
                        } else if (time_passed_since_signed > period_to_withdraw) {
                            var remainder = time_passed_since_signed % period_to_withdraw;
                            next_payment_timestamp_unix = period_to_withdraw - remainder;
                            next_payment_timestamp = (next_payment_timestamp_unix + now_timestamp) * 1000;
                            next_payment_timestamp_date_obj = new Date(next_payment_timestamp);
                            if (current_patient_dcn_balance < monthly_premium_in_dcn) {
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
                            if (current_patient_dcn_balance < monthly_premium_in_dcn) {
                                timer_label = 'Fund your account until:';
                            } else {
                                timer_label = 'Next payment processed in:';
                            }
                        }

                        $('.timer-label').html(timer_label);
                        projectData.initiators.initFlipClockTimer(next_payment_timestamp_unix);

                        cancelContractEventInit();

                        var patientDcnBalanceLogicAnimation = true;
                        patientDcnBalanceLogic(current_patient_dcn_balance);
                        function patientDcnBalanceLogic(current_patient_dcn_balance) {
                            $('.camping-for-popups').html('');
                            if (current_patient_dcn_balance > dcn_needed_to_be_payed_to_dentist) {
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img alt="Check inside shield" src="/assets/uploads/shield-check.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-20 padding-top-15">ALL SET FOR YOUR NEXT PAYMENT</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-10 padding-bottom-25">It seems you have the needed amount of DCN and ETH in your wallet so your dentist will be able to successfully process your next monthly payment on '+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 second-custom-close-btn">SOUNDS GOOD</a></div></div></div>');
                                initPopupEvents();
                            } else {
                                //showing section where ETH and DCN can be bough when doesnt have enough DCN
                                $('.external-api-crypto-provider').removeClass('hide');
                                $('.external-api-crypto-provider .ready-to-charge-account').removeClass('hide');

                                //not enough DCN
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Fund icon" src="/assets/uploads/fund-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">Time to fund your account now!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">You should fund your account with DCN equivalent to <span class="calibri-bold blue-green-color">'+(months_passed_for_reward * monthly_premium_in_usd)+' USD</span> (at the moment: <span class="calibri-bold blue-green-color">'+dcn_needed_to_be_payed_to_dentist+' DCN</span>) before <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 scroll-to-buy-section">FUND NOW</a></div></div></div>');

                                if (patientDcnBalanceLogicAnimation) {
                                    patientDcnBalanceLogicAnimation = false;
                                    $('.camping-for-popups .wrapper').addClass('box-shadow-animation');
                                    $('html, body').animate({scrollTop: $('.row.camping-for-popups').offset().top}, 500);
                                }

                                initPopupEvents(true);

                                // checking every 3 seconds if user deposited dentacoins
                                setTimeout(async function() {
                                    patientDcnBalanceLogic(parseInt(await dApp.dentacoin_token_methods.balanceOf($('.patient-contract-single-page-section').attr('data-patient'))), dcn_needed_to_be_payed_to_dentist);
                                }, 10000);
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
                                $('.camping-for-popups').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img alt="Check up" src="/assets/uploads/check-up.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-20 padding-top-15">WHEN DID YOU HAVE YOUR CHECK-UP?</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-20 padding-bottom-25"><input type="text" class="custom-input max-width-300 margin-0-auto datepicker" readonly/></div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 record-check-up-submit">SUBMIT</a></div></div></div>');
                                projectData.initiators.initDatepicker($('.patient-contract-single-page-section').attr('data-date-start-contract'));

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
                                                            $('.records-history.module table tbody').prepend('<tr data-id="'+response.id+'"> <td>'+Math.round(new Date($('.camping-for-popups .datepicker').val().trim()).getTime() / 1000)+'</td><td class="action-td" data-label="Check-up recorded">Check-up recorded ('+response.event+'/'+response.mustRecordsCount+')</td><td class="details"><span class="lato-bold">PENDING</span></td></tr>');

                                                            $('.camping-for-popups').html('');
                                                            basic.showAlert('Check-up recorded successfully. Now your dentist has to approve it.', 'boobox-alert', true);
                                                        } else if (response.error) {
                                                            sentRecord = false;
                                                            basic.showAlert(response.message, 'boobox-alert', true);
                                                        }
                                                    }
                                                });
                                            }, 2000);
                                        }
                                    } else {
                                        basic.showAlert('Please select valid date.', 'boobox-alert', true);
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
                                $('.camping-for-popups').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img alt="Teeth cleaning" src="/assets/uploads/teeth-cleaning.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-20 padding-top-15">WHEN DID YOU HAVE YOUR TEETH CLEANING?</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-20 padding-bottom-25"><input type="text" class="custom-input max-width-300 margin-0-auto datepicker" readonly/></div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 record-check-up-submit">SUBMIT</a></div></div></div>');
                                projectData.initiators.initDatepicker($('.patient-contract-single-page-section').attr('data-date-start-contract'));

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
                                                            $('.records-history.module table tbody').prepend('<tr data-id="'+response.id+'"> <td>'+Math.round(new Date($('.camping-for-popups .datepicker').val().trim()).getTime() / 1000)+'</td><td class="action-td" data-label="Teeth cleaning recorded">Teeth cleaning recorded ('+response.event+'/'+response.mustRecordsCount+')</td><td class="details"><span class="lato-bold">PENDING</span></td></tr>');

                                                            $('.camping-for-popups').html('');
                                                            basic.showAlert('Teeth cleaning recorded successfully. Now your dentist has to approve it.', 'boobox-alert', true);
                                                        } else if (response.error) {
                                                            sentRecord = false;
                                                            basic.showAlert(response.message, 'boobox-alert', true);
                                                        }
                                                    }
                                                });
                                            }, 2000);
                                        }
                                    } else {
                                        basic.showAlert('Please select valid date.', 'boobox-alert', true);
                                    }
                                });
                            });
                        }

                        function initCheckUpAndRecordTeethCleaningLogic() {
                            $('.camping-for-popups').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20 margin-right-10 inline-block"><img alt="Check up" src="/assets/uploads/check-up.svg" class="max-width-70 width-100"/></figure><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20 inline-block"><img alt="Teeth cleaning" src="/assets/uploads/teeth-cleaning.svg" class="max-width-70 width-100"/></figure><h2 class="lato-bold fs-20 padding-top-15">TIME FOR CHECK-UP & TEETH CLEANING</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-10 padding-bottom-25">Your dentist recommended you to visit them '+$('.patient-contract-single-page-section').attr('data-checkups')+' times per year for a check-up and '+$('.patient-contract-single-page-section').attr('data-teeth-cleanings')+' times per year for a teeth cleaning. Did you have your teeth examined and cleaned already?</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 record-teeth-cleaning-action">YES, I\'VE BEEN THERE</a></div></div></div>');
                            scrollToPopupsCamper();

                            $('.record-teeth-cleaning-action').click(function() {
                                $('.camping-for-popups').html('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20 margin-right-10 inline-block"><img alt="Check up" src="/assets/uploads/check-up.svg" class="max-width-70 width-100"/></figure><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20 inline-block"><img alt="Teeth cleaning" src="/assets/uploads/teeth-cleaning.svg" class="max-width-70 width-100"/></figure><h2 class="lato-bold fs-20 padding-top-15">WHEN DID YOU HAVE YOUR CHECK-UP AND TEETH CLEANING?</h2><div class="fs-18 fs-xs-16 calibri-light padding-top-20 padding-bottom-25"><label class="display-block fs-16 lato-semibold max-width-300 margin-0-auto text-left padding-bottom-5">Check-up Date:</label><input type="text" class="custom-input max-width-300 margin-0-auto check-up-datepicker datepicker" readonly/></div><div class="fs-18 fs-xs-16 calibri-light padding-bottom-25"><label class="display-block fs-16 lato-semibold max-width-300 margin-0-auto text-left padding-bottom-5">Teeth Cleaning Date:</label><input type="text" class="custom-input max-width-300 margin-0-auto teeth-cleaning-datepicker datepicker" readonly/></div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 record-check-up-and-teeth-cleaning-submit">SUBMIT</a></div></div></div>');
                                projectData.initiators.initDatepicker($('.patient-contract-single-page-section').attr('data-date-start-contract'));

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
                                                            $('.records-history.module table tbody').prepend('<tr data-id="'+response.teethCleaningId+'"> <td>'+Math.round(new Date($('.camping-for-popups .teeth-cleaning-datepicker').val().trim()).getTime() / 1000)+'</td><td class="action-td" data-label="Teeth cleaning recorded">Teeth cleaning recorded ('+response.teethCleaningUpEvent+'/'+response.aMustTeethCleaningRecordsCount+')</td><td class="details"><span class="lato-bold">PENDING</span></td></tr>');

                                                            $('.records-history.module table tbody').prepend('<tr data-id="'+response.checkUpId+'"> <td>'+Math.round(new Date($('.camping-for-popups .check-up-datepicker').val().trim()).getTime() / 1000)+'</td><td class="action-td" data-label="Check-up recorded">Check-up recorded ('+response.checkUpEvent+'/'+response.aMustCheckUpRecordsCount+')</td><td class="details"><span class="lato-bold">PENDING</span></td></tr>');

                                                            $('.camping-for-popups').html('');

                                                            basic.showAlert('Record is saved successfully. Now your dentist have to approve it.', 'boobox-alert', true);
                                                        } else if (response.error) {
                                                            sentRecord = false;
                                                            basic.showAlert(response.message, 'boobox-alert', true);
                                                        }
                                                    }
                                                });
                                            }, 2000);
                                        }
                                    } else {
                                        basic.showAlert('Please select at least one valid date.', 'boobox-alert', true);
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

                        if (usdOverDcn) {
                            var monthly_premium_in_usd = parseInt(on_load_exiting_contract[4]);
                            var monthly_premium_in_dcn = Math.round(projectData.utils.convertUsdToDcn(monthly_premium_in_usd));
                        } else {
                            var monthly_premium_in_dcn = parseInt(on_load_exiting_contract[5]);
                            var monthly_premium_in_usd = Math.round(projectData.utils.convertDcnToUsd(monthly_premium_in_dcn));
                        }

                        var patientDcnBalanceLogicAnimation = true;
                        patientWaitingForDentistApprovalLogic(current_user_dcn_balance);
                        function patientWaitingForDentistApprovalLogic(current_user_dcn_balance) {

                            $('.camping-for-popups').html('');
                            if (current_user_dcn_balance < monthly_premium_in_dcn) {
                                // checking every 3 seconds if user deposited BACK dcn
                                setTimeout(async function() {
                                    patientWaitingForDentistApprovalLogic(parseInt(await dApp.dentacoin_token_methods.balanceOf($('.patient-contract-single-page-section').attr('data-patient'))));
                                }, 10000);

                                //not enough DCN
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Fund icon" src="/assets/uploads/fund-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">Time to fund your account now!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">You should fund your account with DCN equivalent to <span class="calibri-bold blue-green-color">'+monthly_premium_in_usd+' USD</span> (at the moment: <span class="calibri-bold blue-green-color">'+monthly_premium_in_dcn+' DCN</span>) before <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 scroll-to-buy-section">FUND NOW</a></div></div></div>');

                                if (patientDcnBalanceLogicAnimation) {
                                    patientDcnBalanceLogicAnimation = false;
                                    $('.camping-for-popups .wrapper').addClass('box-shadow-animation');
                                    $('html, body').animate({scrollTop: $('.row.camping-for-popups').offset().top}, 500);
                                }

                                initPopupEvents(true);

                                $('.external-api-crypto-provider').removeClass('hide');
                                $('.external-api-crypto-provider .ready-to-charge-account').removeClass('hide');
                            }
                        }
                    } else if ($('.contract-header').hasClass('awaiting-payment')) {
                        var current_user_dcn_balance = parseInt(await dApp.dentacoin_token_methods.balanceOf(global_state.account));
                        var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));
                        var monthly_premium_in_dcn = Math.round(projectData.utils.convertUsdToDcn(parseInt($('.patient-contract-single-page-section').attr('data-monthly-premium'))));
                        //var ethgasstation_json = await $.getJSON('https://ethgasstation.info/json/ethgasAPI.json');
                        //const on_page_load_gwei = ethgasstation_json.safeLow;
                        var gasPriceObject = await projectData.requests.getGasPrice();
                        const on_page_load_gwei = gasPriceObject.result.SafeGasPrice;

                        //adding 10% just in case the transaction dont fail
                        const on_page_load_gas_price = on_page_load_gwei * 1000000000 + ((on_page_load_gwei * 1000000000) * projectData.variables.bonusPercentagesToGasEstimations / 100);

                        var approval_given = false;
                        var min_allowed_amount = parseInt(await dApp.assurance_state_methods.getMinAllowedAmount());
                        //if approval is given already SOMEHOW ...
                        if (parseInt(await dApp.dentacoin_token_methods.allowance(projectData.utils.checksumAddress($('.patient-contract-single-page-section').attr('data-patient')), assurance_config.assurance_state_address)) >= min_allowed_amount) {
                            approval_given = true;
                        }

                        if (!approval_given) {
                            //gas estimation for DentacoinToken approval method
                            var gas_cost_for_approval = await dApp.dentacoin_token_instance.methods.approve(assurance_config.assurance_state_address, assurance_config.dentacoins_to_approve).estimateGas({gas: 100000});
                        }

                        console.log(assurance_config.dummy_address, projectData.utils.checksumAddress($('.patient-contract-single-page-section').attr('data-dentist')), Math.round($('.patient-contract-single-page-section').attr('data-monthly-premium')), monthly_premium_in_dcn, parseInt($('.patient-contract-single-page-section').attr('data-date-start-contract')) + period_to_withdraw, $('.patient-contract-single-page-section').attr('data-contract-ipfs'), 'RegisterContract');

                        //for the estimation going to use our internal address which aldready did gave before his allowance in DentacoinToken contract. In order to receive the gas estimation we need to pass all the method conditions and requires
                        var gas_cost_for_contract_creation = await dApp.assurance_proxy_instance.methods.registerContract(assurance_config.dummy_address, projectData.utils.checksumAddress($('.patient-contract-single-page-section').attr('data-dentist')), Math.round($('.patient-contract-single-page-section').attr('data-monthly-premium')), monthly_premium_in_dcn, parseInt($('.patient-contract-single-page-section').attr('data-date-start-contract')) + period_to_withdraw, $('.patient-contract-single-page-section').attr('data-contract-ipfs')).estimateGas({from: assurance_config.dummy_address, gas: 1000000});

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

                        var patientDcnBalanceLogicAnimation = true;
                        var patientEthBalanceLogicAnimation = true;
                        var patientEthDcnBalanceLogicAnimation = true;
                        var patientHavingEthBalanceLogicAnimation = true;


                        if ($('.patient-contract-single-page-section').attr('data-processing-contract') != 'true') {
                            patientApprovalAndContractCreationLogic(current_user_dcn_balance, current_user_eth_balance);
                        }

                        function patientApprovalAndContractCreationLogic(current_user_dcn_balance, current_user_eth_balance) {
                            if (current_user_dcn_balance < monthly_premium_in_dcn && parseFloat(eth_fee) > current_user_eth_balance) {
                                // 1st step
                                $('.camping-for-popups').html('');

                                // checking every 3 seconds if user deposit eth or dcn
                                setTimeout(async function() {
                                    patientApprovalAndContractCreationLogic(parseInt(await dApp.dentacoin_token_methods.balanceOf(global_state.account)), parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account))));
                                }, 10000);

                                //not enough DCN and ETH balance
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Fund icon" src="/assets/uploads/fund-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">Time to fund your account now!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">You should fund your account with DCN equivalent to <span class="calibri-bold blue-green-color">'+$('.patient-contract-single-page-section').attr('data-monthly-premium')+' USD</span> (at the moment: <span class="calibri-bold blue-green-color">'+monthly_premium_in_dcn+' DCN</span>) and <span class="calibri-bold blue-green-color">'+eth_fee+' ETH</span> before <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 scroll-to-buy-section">FUND NOW</a></div></div></div>');

                                if (patientEthDcnBalanceLogicAnimation) {
                                    patientEthDcnBalanceLogicAnimation = false;
                                    $('.camping-for-popups .wrapper').addClass('box-shadow-animation');
                                    $('html, body').animate({scrollTop: $('.row.camping-for-popups').offset().top}, 500);
                                }

                                initPopupEvents(true);
                            } else if (current_user_dcn_balance < monthly_premium_in_dcn) {
                                // 1st step
                                $('.camping-for-popups').html('');

                                // checking every 3 seconds if user deposit eth or dcn
                                setTimeout(async function() {
                                    patientApprovalAndContractCreationLogic(parseInt(await dApp.dentacoin_token_methods.balanceOf(global_state.account)), parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account))));
                                }, 10000);

                                //not enough DCN
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Fund icon" src="/assets/uploads/fund-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">Time to fund your account now!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">You should fund your account with DCN equivalent to <span class="calibri-bold blue-green-color">'+$('.patient-contract-single-page-section').attr('data-monthly-premium')+' USD</span> (at the moment: <span class="calibri-bold blue-green-color">'+monthly_premium_in_dcn+' DCN</span>) before <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 scroll-to-buy-section">FUND NOW</a></div></div></div>');

                                if (patientDcnBalanceLogicAnimation) {
                                    patientDcnBalanceLogicAnimation = false;
                                    $('.camping-for-popups .wrapper').addClass('box-shadow-animation');
                                    $('html, body').animate({scrollTop: $('.row.camping-for-popups').offset().top}, 500);
                                }

                                initPopupEvents(true);
                            } else if (parseFloat(eth_fee) > current_user_eth_balance) {
                                // 1st step
                                $('.camping-for-popups').html('');

                                // checking every 3 seconds if user deposit eth or dcn
                                setTimeout(async function() {
                                    patientApprovalAndContractCreationLogic(parseInt(await dApp.dentacoin_token_methods.balanceOf(global_state.account)), parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account))));
                                }, 10000);

                                //not enough ETH balance
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center padding-top-30"><figure itemscope="" itemtype="http://schema.org/ImageObject"><img alt="Fund icon" src="/assets/uploads/fund-icon.svg" class="max-width-70"/></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">YOUR CONTRACT</h2><h3 class="fs-22 padding-top-5 lato-bold">Time to fund your account now!</h3><div class="fs-18 fs-xs-16 calibri-light padding-top-15 padding-bottom-25">You should fund your account with <span class="calibri-bold blue-green-color">'+eth_fee+' ETH</span> before <span class="calibri-bold blue-green-color">'+projectData.utils.dateObjToFormattedDate(next_payment_timestamp_date_obj)+'</span>.</div><div><a href="javascript:void(0)" class="white-blue-green-btn min-width-150 scroll-to-buy-section">FUND NOW</a></div></div></div>');

                                if (patientEthBalanceLogicAnimation) {
                                    patientEthBalanceLogicAnimation = false;
                                    $('.camping-for-popups .wrapper').addClass('box-shadow-animation');
                                    $('html, body').animate({scrollTop: $('.row.camping-for-popups').offset().top}, 500);
                                }

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

                                if (patientHavingEthBalanceLogicAnimation) {
                                    patientEthBalanceLogicAnimation = false;
                                    $('.camping-for-popups .wrapper').addClass('box-shadow-animation');
                                    $('html, body').animate({scrollTop: $('.steps-navigation').offset().top}, 500);
                                }

                                initPopupEvents();

                                $('.proceed-to-second-step').click(function() {
                                    $('.contract-response-message.popup-step-one').hide();
                                    $('.contract-response-message.popup-step-two').fadeIn();

                                    $('.steps-navigation a[data-step="popup-step-one"]').removeClass('active');
                                    $('.steps-navigation a[data-step="popup-step-two"]').addClass('active');
                                });

                                $('.call-recipe').click(function() {
                                    if ($('.single-contract-view-section').attr('data-processing-contract') == 'true') {
                                        showContractAttentionInProcess();
                                    } else {

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
                                                                basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. <a href="javascript:void(0);" target="_blank" class="text-decoration-underline blue-green-color please-refill">Please refill</a></div>', 'boobox-alert', true);
                                                            } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                                basic.showAlert('Please check the checkbox below to continue with the QR code generation.', 'boobox-alert', true);
                                                            } else {
                                                                $('.onsite-transaction-signing').fadeOut(500);
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
                                                                        basic.showAlert('Something went wrong, please try again later.', 'boobox-alert', true);
                                                                        hideLoader();
                                                                    }
                                                                });
                                                            }
                                                        });

                                                        // $('.recipe-popup .usd_val span').html($('.patient-contract-single-page-section').attr('data-monthly-premium'));
                                                        // $('.recipe-popup .dcn_val span').html(monthly_premium_in_dcn);

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
                                                            $('.camp-for-keystore-password').html('<div class="lato-regular fs-30 text-center padding-bottom-20 padding-top-15">Enter your keystore secret password</div><div class="padding-bottom-20"><div class="custom-google-label-style module max-width-280 margin-0-auto" data-input-blue-green-border="true"><label for="keystore-password">Secret password:</label><input type="password" maxlength="30" id="keystore-password" class="full-rounded keystore-password cached"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn min-width-250 margin-bottom-10 width-xs-100 max-width-400 fire-blockchain-transaction">SIGN TRANSACTION</a></div></div>');
                                                        }

                                                        $('.recipe-popup .execute-transaction .fire-blockchain-transaction').click(async function() {
                                                            var this_btn = $(this);
                                                            if (parseFloat(eth_fee) > current_user_eth_balance) {
                                                                //not enough ETH balance
                                                                basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. <a href="javascript:void(0);" class="text-decoration-underline blue-green-color please-refill">Please refill.</a></div>', 'boobox-alert', true);
                                                            } else {
                                                                if (!existingCachedKey && transaction_key == undefined) {
                                                                    basic.showAlert('You must first enter your private key or keystore file in order to sign the transaction.', 'boobox-alert', true);
                                                                    return false;
                                                                } else if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() == '') {
                                                                    basic.showAlert('Please enter the secret password for your keystore file.', 'boobox-alert', true);
                                                                    return false;
                                                                } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                                    basic.showAlert('Please check the checkbox below to continue with the transaction creation.', 'boobox-alert', true);
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
                                                                                basic.showAlert(decrypted_keystore_file_response.message, 'boobox-alert', true);
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
                                                                                    chainId: assurance_config.chain_id,
                                                                                    data: approval_function_abi,
                                                                                    to: assurance_config.dentacoin_token_address
                                                                                };

                                                                                const approval_transaction = new EthereumTx(approval_transaction_obj);
                                                                                //signing the transaction
                                                                                approval_transaction.sign(new Buffer(transaction_key, 'hex'));

                                                                                fireAssuranceContractCreationTransaction('0x' + approval_transaction.serialize().toString('hex'), nonce + 1);
                                                                            });
                                                                        } else {
                                                                            fireAssuranceContractCreationTransaction();
                                                                        }
                                                                    }, 2000);

                                                                    async function fireAssuranceContractCreationTransaction(signedUnsubmittedTransactionApproval, nonce) {
                                                                        if (signedUnsubmittedTransactionApproval == undefined) {
                                                                            signedUnsubmittedTransactionApproval = null;
                                                                        }
                                                                        if (nonce == undefined) {
                                                                            nonce = await dApp.web3_1_0.eth.getTransactionCount(global_state.account, 'pending');
                                                                        }

                                                                        var contract_creation_function_abi = await dApp.assurance_proxy_instance.methods.registerContract(projectData.utils.checksumAddress(response.contract_data.patient), projectData.utils.checksumAddress(response.contract_data.dentist), Math.round(response.contract_data.value_usd), monthly_premium_in_dcn, response.contract_data.date_start_contract + period_to_withdraw, response.contract_data.contract_ipfs_hash).encodeABI();

                                                                        var contract_creation_transaction_obj = {
                                                                            gasLimit: dApp.web3_1_0.utils.toHex(Math.round(gas_cost_for_contract_creation + (gas_cost_for_contract_creation * projectData.variables.bonusPercentagesToGasEstimations / 100))),
                                                                            gasPrice: dApp.web3_1_0.utils.toHex(on_page_load_gas_price),
                                                                            from: global_state.account,
                                                                            nonce: dApp.web3_1_0.utils.toHex(nonce),
                                                                            chainId: assurance_config.chain_id,
                                                                            data: contract_creation_function_abi,
                                                                            to: assurance_config.assurance_proxy_address
                                                                        };

                                                                        const contract_creation_transaction = new EthereumTx(contract_creation_transaction_obj);
                                                                        //signing the transaction
                                                                        contract_creation_transaction.sign(new Buffer(transaction_key, 'hex'));

                                                                        var transactionData = {
                                                                            slug: $('.init-contract-section').attr('data-contract'),
                                                                            to_status: 'awaiting-approval',
                                                                            patient_address: projectData.utils.checksumAddress(global_state.account),
                                                                            dentist_address: projectData.utils.checksumAddress(response.contract_data.dentist),
                                                                            signedUnsubmittedTransaction: '0x' + contract_creation_transaction.serialize().toString('hex')
                                                                        };

                                                                        if (signedUnsubmittedTransactionApproval != null) {
                                                                            transactionData.signedUnsubmittedTransactionApproval = signedUnsubmittedTransactionApproval;
                                                                        }

                                                                        submitTransactionToApi(transactionData, function() {
                                                                            changeContractViewToProcessing();

                                                                            onSuccessfulContractCreation();
                                                                        });
                                                                    }
                                                                }
                                                            }
                                                        });
                                                    } else {
                                                        basic.showAlert(response.error, 'boobox-alert', true);
                                                    }
                                                }
                                            });
                                        }
                                    }
                                });
                            }
                        }
                    }
                } else if ($('body').hasClass('dentist-contract-view')) {
                    if ($('.contract-header').hasClass('awaiting-payment')) {
                        trackForContractStatusChange($('.single-contract-view-section').attr('data-contract'), 'awaiting-payment');
                    } else if ($('.contract-header').hasClass('pending')) {
                        trackForContractStatusChange($('.single-contract-view-section').attr('data-contract'), 'pending');
                    } else if ($('.contract-header').hasClass('active')) {
                        trackForContractStatusChange($('.single-contract-view-section').attr('data-contract'), 'active');
                        trackForWalletSigning($('.single-contract-view-section').attr('data-contract'), 'active-withdraw');
                        trackForWalletCancel($('.single-contract-view-section').attr('data-contract'));
                    } else if ($('.contract-header').hasClass('awaiting-approval')) {
                        window.onbeforeunload = function(e) {
                            return '';
                        };

                        if ($('.scroll-to-begging-of-contact').length) {
                            $('.scroll-to-begging-of-contact').click(function() {
                                $('html, body').animate({scrollTop: $('#begging-of-contact').offset().top}, 500);
                            });
                        }

                        trackForContractStatusChange($('.single-contract-view-section').attr('data-contract'), 'awaiting-approval');
                        trackForWalletSigning($('.single-contract-view-section').attr('data-contract'), 'active');
                        trackForWalletCancel($('.single-contract-view-section').attr('data-contract'));
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

                            timer_label = 'Grace period for payment:';
                            $('.clock').addClass('red-background');
                            // $('.contract-header').html('ACTIVE - OVERDUE PAYMENT');
                        }  else {
                            // running the period when patient has to execute the first payment
                            next_payment_timestamp_unix = period_to_withdraw - time_passed_since_signed;
                            timer_label = 'Withdraw payment after:';
                        }

                        $('.timer-label').html(timer_label);
                        projectData.initiators.initFlipClockTimer(next_payment_timestamp_unix);

                        $('.first-payment').html(projectData.utils.dateObjToFormattedDate(new Date((parseInt($('.single-contract-view-section').attr('data-created-at')) + parseInt(await dApp.assurance_state_methods.getPeriodToWithdraw())) * 1000)));

                        if ($('.single-contract-view-section').hasClass('awaiting-approval')) {
                            //var ethgasstation_json = await $.getJSON('https://ethgasstation.info/json/ethgasAPI.json');
                            //const on_page_load_gwei = ethgasstation_json.safeLow;
                            var gasPriceObject = await projectData.requests.getGasPrice();
                            const on_page_load_gwei = gasPriceObject.result.SafeGasPrice;
                            //adding 10% just in case the transaction dont fail
                            const on_page_load_gas_price = on_page_load_gwei * 1000000000 + ((on_page_load_gwei * 1000000000) * projectData.variables.bonusPercentagesToGasEstimations / 100);

                            //for the estimation going to use our internal address which aldready did gave before his allowance in DentacoinToken contract. In order to receive the gas estimation we need to pass all the method conditions and requires
                            var gas_cost_for_contract_approval = await dApp.assurance_proxy_instance.methods.dentistApproveContract($('.single-contract-view-section').attr('data-patient')).estimateGas({from: global_state.account, gas: 100000});

                            var eth_fee = dApp.web3_1_0.utils.fromWei((gas_cost_for_contract_approval * on_page_load_gas_price).toString(), 'ether');

                            var contract_approval_function_abi = await dApp.assurance_proxy_instance.methods.dentistApproveContract($('.single-contract-view-section').attr('data-patient')).encodeABI();

                            $('.approve-contract-recipe').click(function() {
                                if ($('.single-contract-view-section').attr('data-processing-contract') == 'true') {
                                    showContractAttentionInProcess();
                                } else {
                                    if (metamask) {
                                        basic.showAlert('Using MetaMask is currently not supported in Dentacoin Assurance. Please switch off MetaMask extension and try again.');
                                    } else {
                                        //custom
                                        if (!$('#read-the-contract-details').is(':checked')) {
                                            basic.showAlert('Please check the checkbox above to continue with the contract approval.', 'boobox-alert', true);
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

                                        // if ($(this).attr('data-sent-eth-to-dentist') == 'true') {
                                            ajax_data.sent_eth_to_dentist = true;
                                            ajax_data.recipe_checkbox_text = 'To approve this contract and activate it on the blockchain, you need to pay the small fee above only once. We have sent you the needed amount. By clicking on the button below you confirm that from now on every month you will withdraw the monthly premium amount on the payment due date or later.';
                                        // }

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
                                                            basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. <a href="javascript:void(0);" class="text-decoration-underline blue-green-color please-refill">Please refill.</a></div>', 'boobox-alert', true);
                                                        } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                            basic.showAlert('Please check the checkbox below to continue with the QR code generation.', 'boobox-alert', true);
                                                        } else {
                                                            $('.onsite-transaction-signing').fadeOut(500);
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
                                                        $('.camp-for-keystore-password').html('<div class="lato-regular fs-30 text-center padding-bottom-20 padding-top-15">Enter your keystore secret password</div><div class="padding-bottom-20"><div class="custom-google-label-style module max-width-280 margin-0-auto" data-input-blue-green-border="true"><label for="keystore-password">Secret password:</label><input type="password" maxlength="30" id="keystore-password" class="full-rounded keystore-password cached"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn min-width-250 margin-bottom-10 width-xs-100 max-width-400 fire-blockchain-transaction">SIGN TRANSACTION</a></div></div>');
                                                    }

                                                    $('.recipe-popup .execute-transaction .fire-blockchain-transaction').click(async function() {
                                                        var this_btn = $(this);
                                                        var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));
                                                        if (parseFloat(eth_fee) > current_user_eth_balance) {
                                                            //not enough ETH balance
                                                            basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. <a href="javascript:void(0);" class="text-decoration-underline blue-green-color please-refill">Please refill.</a></div>', 'boobox-alert', true);
                                                        } else {
                                                            if (!existingCachedKey && transaction_key == undefined) {
                                                                basic.showAlert('You must first enter your private key or keystore file in order to sign the transaction.', 'boobox-alert', true);
                                                                return false;
                                                            } else if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() == '') {
                                                                basic.showAlert('Please enter the secret password for your keystore file.', 'boobox-alert', true);
                                                                return false;
                                                            } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                                basic.showAlert('Please check the checkbox below to continue with the transaction creation.', 'boobox-alert', true);
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
                                                                            basic.showAlert(decrypted_keystore_file_response.message, 'boobox-alert', true);
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
                                                                        chainId: assurance_config.chain_id,
                                                                        data: contract_approval_function_abi,
                                                                        to: assurance_config.assurance_proxy_address
                                                                    };

                                                                    const contract_approval_transaction = new EthereumTx(contract_approval_transaction_obj);
                                                                    //signing the transaction
                                                                    contract_approval_transaction.sign(new Buffer(transaction_key, 'hex'));

                                                                    submitTransactionToApi({
                                                                        slug: $('.init-contract-section').attr('data-contract'),
                                                                        to_status: 'active',
                                                                        patient_address: projectData.utils.checksumAddress($('.single-contract-view-section').attr('data-patient')),
                                                                        dentist_address: projectData.utils.checksumAddress(global_state.account),
                                                                        signedUnsubmittedTransaction: '0x' + contract_approval_transaction.serialize().toString('hex')
                                                                    }, function() {
                                                                        changeContractViewToProcessing();

                                                                        onSuccessfulContractApproval(response.contract_data.patient_name);
                                                                    });
                                                                }, 2000);
                                                            }
                                                        }
                                                    });
                                                } else if (response.error) {
                                                    basic.showAlert(response.error, 'boobox-alert', true);
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    } else if ($('.contract-header').hasClass('active')) {
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
                        
                        if (usdOverDcn) {
                            var monthly_premium_in_dcn = Math.round(projectData.utils.convertUsdToDcn(on_load_exiting_contract[4]));
                        } else {
                            var monthly_premium_in_dcn = parseInt(on_load_exiting_contract[5]);
                        }
                        
                        var contract_next_payment = parseInt(on_load_exiting_contract[0]);
                        var current_patient_dcn_balance = parseInt(await dApp.dentacoin_token_methods.balanceOf($('.single-contract-view-section').attr('data-patient')));


                        // check for pending patient records - check-up or teeth cleaning
                        var visibleRecord = false;
                        function confirmRecord(record, successCallback) {
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
                                                    successCallback(response);
                                                } else if (response.error) {
                                                    hideLoader();
                                                    basic.showAlert(response.message, 'boobox-alert', true);
                                                }
                                            }
                                        });
                                    }, 2000);
                                }
                            };
                            basic.showConfirm('Are you sure you want to confirm this visit?', 'confirmRecord', clickWarningObj, true, {
                                'onEscape' : function() {
                                    visibleRecord = false;
                                }
                            });
                        }

                        function declineRecord(record, successCallback) {
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
                                                    successCallback(response);
                                                } else if (response.error) {
                                                    hideLoader();
                                                    basic.showAlert(response.message, 'boobox-alert', true);
                                                }
                                            }
                                        });
                                    }, 2000);
                                }
                            };
                            basic.showConfirm('Sure you want to continue with declining your patient record?', 'declineRecord', clickWarningObj, true, {
                                'onEscape' : function() {
                                    visibleRecord = false;
                                }
                            });
                        }

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
                                            if (response.data) {
                                                Object.keys(response.data).forEach(function(key) {
                                                    if (!$('.records-history.module tr[data-id="'+key+'"]').length) {
                                                        var typeLabel;
                                                        var action;
                                                        if (response.data[key].type == 'teeth-cleaning') {
                                                            typeLabel = 'Teeth cleaning recorded';
                                                            action = 'Teeth cleaning recorded ('+response.currentTeethCleaningsCount+'/'+response.teeth_cleaning_per_year+')';
                                                        } else if (response.data[key].type == 'check-up') {
                                                            typeLabel = 'Check-up recorded';
                                                            action = 'Check-up recorded ('+response.currentCheckUpCount+'/'+response.check_ups_per_year+')';
                                                        }

                                                        $('.records-history.module table tbody').prepend('<tr data-id="'+key+'"> <td>'+projectData.utils.dateObjToFormattedDate(new Date(response.data[key].request_date_at))+'</td><td class="action-td" data-label="'+typeLabel+'">'+action+'</td><td class="details"><span class="lato-bold"><a href="javascript:void(0)" class="inline-block margin-left-5 red-white-btn fs-14 padding-top-5 padding-bottom-5 padding-left-10 padding-right-10 decline-record-from-records-history" data-record="'+response.data_record+'">Decline</a> | <a href="javascript:void(0)" class="inline-block margin-right-5 white-green-btn fs-14 padding-top-5 padding-bottom-5 padding-left-10 padding-right-10 confirm-record-from-records-history" data-record="'+response.data_record+'">CONFIRM</a></span></td></tr>');
                                                    }
                                                });
                                            }

                                            visibleRecord = true;
                                            basic.showDialog(response.html, 'pending-contract-record', null, true, {
                                                'onEscape' : function() {
                                                    visibleRecord = false;
                                                }
                                            });

                                            $('.pending-contract-record .confirm-record').click(function() {
                                                var record = $(this).attr('data-record');

                                                confirmRecord(record, function(response) {
                                                    visibleRecord = false;
                                                    basic.closeDialog();
                                                    hideLoader();

                                                    if (response.data && response.data.length) {
                                                        for (var i = 0, len = response.data.length; i < len; i+=1) {
                                                            $('.records-history.module tr[data-id="'+response.data[i]+'"] .details').html('<span class="lato-bold active-color">CONFIRMED</span>');
                                                        }
                                                    }
                                                });
                                            });

                                            $('.pending-contract-record .decline-record').click(function() {
                                                var record = $(this).attr('data-record');

                                                declineRecord(record, function(response) {
                                                    visibleRecord = false;
                                                    basic.closeDialog();
                                                    hideLoader();

                                                    if (response.data && response.data.length) {
                                                        for (var i = 0, len = response.data.length; i < len; i+=1) {
                                                            $('.records-history.module tr[data-id="'+response.data[i]+'"] .details').html('<span class="lato-bold cancelled-color">DECLINED</span>');
                                                            $('.records-history.module tr[data-id="'+response.data[i]+'"] .action-td').html($('.records-history.module tr[data-id="'+response.data[i]+'"] .action-td').attr('data-label'));
                                                        }
                                                    }
                                                });
                                            });
                                        }
                                    }
                                });
                            }
                        }, 5000);

                        $(document).on('click', '.confirm-record-from-records-history', function() {
                            var record = $(this).attr('data-record');
                            visibleRecord = true;

                            confirmRecord(record, function(response) {
                                visibleRecord = false;
                                basic.closeDialog();
                                hideLoader();

                                if (response.data && response.data.length) {
                                    for (var i = 0, len = response.data.length; i < len; i+=1) {
                                        $('.records-history.module tr[data-id="'+response.data[i]+'"] .details').html('<span class="lato-bold active-color">CONFIRMED</span>');
                                    }
                                }
                            });
                        });

                        $(document).on('click', '.decline-record-from-records-history', function() {
                            var record = $(this).attr('data-record');
                            visibleRecord = true;

                            declineRecord(record, function(response) {
                                visibleRecord = false;
                                basic.closeDialog();
                                hideLoader();

                                if (response.data && response.data.length) {
                                    for (var i = 0, len = response.data.length; i < len; i+=1) {
                                        $('.records-history.module tr[data-id="'+response.data[i]+'"] .details').html('<span class="lato-bold cancelled-color">DECLINED</span>');
                                        $('.records-history.module tr[data-id="'+response.data[i]+'"] .action-td').html($('.records-history.module tr[data-id="'+response.data[i]+'"] .action-td').attr('data-label'));
                                    }
                                }
                            });
                        });

                        if ($('.single-contract-view-section').attr('data-processing-contract') != 'true') {
                            if (contract_next_payment > now_timestamp) {
                                /*$('.camping-withdraw-time-left-section').html('<div class="row"><div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 padding-top-30 padding-bottom-30 clock-container text-center"><div class="row"><div class="col-xs-12 col-md-8 col-md-offset-2"><h2 class="fs-20 fs-xs-17 padding-bottom-20 padding-bottom-xs-10 lato-bold ">MAKE YOUR NEXT WITHDRAW IN</h2></div> </div><div class="clock"></div><div class="flip-clock-message"></div></div></div>');*/
                                $('.timer-label').html('MAKE YOUR NEXT WITHDRAW IN');
                                projectData.initiators.initFlipClockTimer(contract_next_payment - now_timestamp);
                            } /*else if (contract_next_payment < now_timestamp && now_timestamp - contract_next_payment > period_to_withdraw * 2 && current_patient_dcn_balance < (Math.floor((now_timestamp - contract_next_payment) / period_to_withdraw) + 1) * monthly_premium_in_dcn) {
                            var months_dentist_didnt_withdraw = Math.floor((now_timestamp - contract_next_payment) / period_to_withdraw) + 1;

                            basic.showAlert('You haven\'t withdraw from this patient for ' + months_dentist_didnt_withdraw + ' months in a row, but the patient currently have not enough DCN to cover all the months. Contact him and let him know to refill DCN inside his Wallet Address.', 'boobox-alert', true);
                        }*/ else if (contract_next_payment < now_timestamp && now_timestamp < contract_next_payment + dApp.grace_period && current_patient_dcn_balance < monthly_premium_in_dcn) {
                                //show red counter (grace period)
                                /*$('.camping-withdraw-time-left-section').html('<div class="row"><div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 padding-top-30 padding-bottom-30 clock-container text-center"><div class="row"><div class="col-xs-12 col-md-8 col-md-offset-2"><h2 class="fs-20 fs-xs-17 padding-bottom-20 padding-bottom-xs-10 lato-bold">If the patient doesn\'t fill in '+monthly_premium_in_dcn+' DCN inside his Wallet Address the contract will be canceled in:</h2></div> </div><div class="clock red-background"></div><div class="flip-clock-message"></div></div></div>');*/
                                $('.contract-header').html('ACTIVE - OVERDUE PAYMENT');
                                $('.timer-label').html('If the patient doesn\'t fill in '+monthly_premium_in_dcn+' DCN inside his Wallet Address the contract will be canceled in:');
                                projectData.initiators.initFlipClockTimer(contract_next_payment + dApp.grace_period - now_timestamp);
                            } else {
                                $('.contract-body .wrapper').remove();

                                var months_dentist_didnt_withdraw = Math.floor((now_timestamp - contract_next_payment) / period_to_withdraw) + 1;
                                var withdrawableDCN = months_dentist_didnt_withdraw * monthly_premium_in_dcn;

                                if (usdOverDcn) {
                                    var withdrawableUSD = months_dentist_didnt_withdraw * on_load_exiting_contract[4];
                                    var withdrawableDCN = Math.round(projectData.utils.convertUsdToDcn(withdrawableUSD));
                                } else {
                                    var withdrawableDCN = months_dentist_didnt_withdraw * monthly_premium_in_dcn;
                                    var withdrawableUSD = Math.round(projectData.utils.convertDcnToUsd(withdrawableDCN));
                                }

                                // ready to withdraw
                                $('.camping-for-popups').append('<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module"><div class="wrapper text-center pull-back-to-top"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img alt="Check inside shield" src="/assets/uploads/shield-check.svg" class="max-width-70"></figure><h2 class="lato-bold fs-22 padding-top-15 blue-green-color">TIME TO WITHDRAW</h2><div class="fs-20 padding-top-15">Your money is waiting for you.</div><div class="padding-bottom-20 fs-20">Withdraw the Dentacoin tokens collected by <span class="calibri-bold">'+$('.camping-for-popups').attr('data-patient-name')+'</span>.</div><div class="text-center"><a href="javascript:void(0)" class="dentist-withdraw white-blue-green-btn inline-block max-width-280 max-width-xs-400 margin-bottom-10 width-100"><svg class="inline-block-top margin-right-5" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 75.7 74.3" style="enable-background:new 0 0 75.7 74.3;max-width: 25px;" xml:space="preserve"><style type="text/css">.st0{fill:#FFFFFF;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds bottomLeftOrigin="true" height="74.3" width="75.7" x="12.2" y="37.8"></sliceSourceBounds></sfw></metadata><path class="st0" d="M29.7,32.2h-7.5l0,0c-0.1,0-0.2,0-0.3,0h-0.1c-0.1,0-0.1,0-0.2,0.1c-0.1,0-0.1,0-0.2,0.1c-0.1,0-0.1,0.1-0.2,0.1c-0.1,0-0.1,0.1-0.2,0.1l-0.1,0.1c-0.1,0-0.1,0.1-0.2,0.2l0,0L20.6,33c0,0.1-0.1,0.1-0.1,0.2s-0.1,0.1-0.1,0.2c0,0.1-0.1,0.1-0.1,0.2s0,0.1-0.1,0.2c0,0.1,0,0.1,0,0.2s0,0.1,0,0.2v0.1l0,0c0,0.1,0,0.2,0,0.2c0,0.1,0,0.1,0,0.2c0,0.1,0,0.1,0.1,0.2c0,0.1,0,0.1,0.1,0.2c0,0.1,0.1,0.1,0.1,0.2s0.1,0.1,0.1,0.2l0.1,0.1c0,0.1,0.1,0.1,0.1,0.2l0,0l15.4,14.5c0.4,0.4,0.9,0.5,1.4,0.5s1-0.2,1.4-0.5l15.4-14.5c0.8-0.8,0.8-2,0.1-2.8c-0.4-0.5-1-0.7-1.6-0.6h-0.1h-7.5v-2.9c0-1.1-0.9-2-2-2s-2,0.9-2,2v4.9c0,1.1,0.9,2,2,2H48l-10.4,9.8l-10.4-9.8h4.4c1.1,0,2-0.9,2-2v-4.9c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2L29.7,32.2L29.7,32.2z"/><path class="st0" d="M29.7,23.3c0,1.1,0.9,2,2,2c1.1,0,2-0.9,2-2v-3.2c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2V23.3z"/><path class="st0" d="M41.3,23.3c0,1.1,0.9,2,2,2s2-0.9,2-2v-3.2c0-1.1-0.9-2-2-2s-2,0.9-2,2V23.3z"/><path class="st0" d="M29.7,12.8c0,1.1,0.9,2,2,2c1.1,0,2-0.9,2-2v-1.4c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2V12.8z"/><path class="st0" d="M41.3,12.8c0,1.1,0.9,2,2,2s2-0.9,2-2v-1.4c0-1.1-0.9-2-2-2s-2,0.9-2,2V12.8z"/><path class="st0" d="M31.7,4.1c1.1,0,2-0.9,2-2V2c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2v0.1C29.7,3.2,30.6,4.1,31.7,4.1z"/><path class="st0" d="M43.3,4.1c1.1,0,2-0.9,2-2V2c0-1.1-0.9-2-2-2s-2,0.9-2,2v0.1C41.3,3.2,42.2,4.1,43.3,4.1z"/><path class="st0" d="M75.7,51.4V37.2c0-9.4-9.2-17.7-23.4-21.2c-1.1-0.3-2.2,0.4-2.4,1.5c-0.3,1.1,0.4,2.2,1.5,2.4c12.4,3,20.4,9.8,20.4,17.3c0,10.2-15.5,18.9-33.9,18.9S4,47.5,4,37.2c0-7.3,7.8-14.1,19.9-17.2c1.1-0.3,1.7-1.4,1.4-2.4c-0.3-1.1-1.4-1.7-2.4-1.4C9,19.8,0,28,0,37.2v14.2c0,12.8,16.6,22.9,37.9,22.9S75.7,64.2,75.7,51.4z M71.7,47.6v3.9c0,2.9-1.2,5.7-3.5,8.1V51C69.5,49.9,70.7,48.8,71.7,47.6z M11.2,53.6c1.4,0.8,2.9,1.6,4.6,2.3v9.7c-1.7-0.8-3.2-1.7-4.6-2.7V53.6zM19.8,57.4c1.8,0.6,3.6,1,5.5,1.4v10.1c-1.9-0.4-3.8-1-5.5-1.6V57.4z M29.3,59.5c2.1,0.3,4.3,0.5,6.5,0.5v10.2c-2.2-0.1-4.4-0.3-6.5-0.6V59.5z M39.8,60c2.2-0.1,4.3-0.2,6.3-0.5v10.2c-2,0.3-4.2,0.5-6.3,0.6V60z M50.1,58.8c1.9-0.4,3.8-0.9,5.5-1.4v9.9c-1.7,0.6-3.6,1.1-5.5,1.6C50.1,68.9,50.1,58.8,50.1,58.8z M59.7,56c1.6-0.7,3.2-1.4,4.6-2.3v9.4c-1.4,1-2.9,1.8-4.6,2.6V56z M3.9,51.4v-3.9c1,1.2,2.1,2.3,3.3,3.3v8.6C5.1,57,3.9,54.3,3.9,51.4z"/></svg> WITHDRAW NOW</a></div></div></div>');

                                $('.patient-dentist-data').addClass('position-relative-and-z-index');
                                $('.camping-for-popups').addClass('position-relative-and-z-index');

                                $('.camping-for-popups .wrapper').addClass('box-shadow-animation');
                                $('html, body').animate({scrollTop: $('.row.camping-for-popups .wrapper ').offset().top}, 500);

                                bindDentistWithdrawEvent(withdrawableDCN, withdrawableUSD);
                            }
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
                                                    basic.showAlert('Please enter your password.', 'boobox-alert', true);
                                                }else {
                                                    var decrypt_response = await decryptDataByKeystore(encrypted_pdf_content.success, existingCachedKeystore, $('.keystore-file-password-validation .keystore-password').val().trim());
                                                    if (decrypt_response.success) {
                                                        basic.closeDialog();
                                                        render_form.find('input[name="pdf_data"]').val(decrypt_response.success.decrypted);
                                                        render_form.submit();
                                                    } else if (decrypt_response.error) {
                                                        basic.showAlert(decrypt_response.message, 'boobox-alert', true);
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
                            basic.showAlert(encrypted_pdf_content.error, 'boobox-alert', true);
                        }
                    });
                }
            }
        }
    }
};

async function bindDentistWithdrawEvent(withdrawableDCN, withdrawableUSD) {
    //var ethgasstation_json = await $.getJSON('https://ethgasstation.info/json/ethgasAPI.json');
    //const on_page_load_gwei = ethgasstation_json.safeLow;
    var gasPriceObject = await projectData.requests.getGasPrice();
    const on_page_load_gwei = gasPriceObject.result.SafeGasPrice;
    //adding 10% just in case the transaction dont fail
    const on_page_load_gas_price = on_page_load_gwei * 1000000000 + ((on_page_load_gwei * 1000000000) * projectData.variables.bonusPercentagesToGasEstimations / 100);

    //for the estimation going to use our internal address which aldready did gave before his allowance in DentacoinToken contract. In order to receive the gas estimation we need to pass all the method conditions and requires
    var gas_cost_for_withdraw = await dApp.assurance_proxy_instance.methods.singleWithdraw($('.single-contract-view-section').attr('data-patient')).estimateGas({
        from: global_state.account,
        gas: 300000
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

            var transactionRecipePopupData = {
                /*to: assurance_config.assurance_proxy_address,*/
                cached_key: existingCachedKey,
                contract: $('.single-contract-view-section').attr('data-contract'),
                show_dcn_bar: false,
                recipe_title: 'WITHDRAW NOW',
                recipe_subtitle: '',
                recipe_checkbox_text: 'By clicking on the button below you will withdraw your DCN from your Patient.',
                btn_label: 'WITHDRAW NOW',
                type: 'qr-scan'
            };

            if (withdrawableDCN != undefined) {
                transactionRecipePopupData.withdrawableDCN = withdrawableDCN;
            }

            if (withdrawableUSD != undefined) {
                transactionRecipePopupData.withdrawableUSD = withdrawableUSD;
            }

            $.ajax({
                type: 'POST',
                url: '/get-recipe-popup',
                dataType: 'json',
                data: transactionRecipePopupData,
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
                                basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. <a href="javascript:void(0);" class="text-decoration-underline blue-green-color please-refill">Please refill.</a></div>', 'boobox-alert', true);
                            } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                basic.showAlert('Please check the checkbox below to continue with the QR code generation.', 'boobox-alert', true);
                            } else {
                                $('.onsite-transaction-signing').fadeOut(500);
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
                            $('.camp-for-keystore-password').html('<div class="lato-regular fs-30 text-center padding-bottom-20 padding-top-15">Enter your keystore secret password</div><div class="padding-bottom-20"><div class="custom-google-label-style module max-width-280 margin-0-auto" data-input-blue-green-border="true"><label for="keystore-password">Secret password:</label><input type="password" maxlength="30" id="keystore-password" class="full-rounded keystore-password cached"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn min-width-250 margin-bottom-10 width-xs-100 max-width-400 fire-blockchain-transaction">SIGN TRANSACTION</a></div></div>');
                        }

                        $('.recipe-popup .execute-transaction .fire-blockchain-transaction').click(async function() {
                            var this_btn = $(this);
                            var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));
                            if (parseFloat(eth_fee) > current_user_eth_balance) {
                                //not enough ETH balance
                                basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. <a href="javascript:void(0);" class="text-decoration-underline blue-green-color please-refill">Please refill.</a></div>', 'boobox-alert', true);
                            } else {
                                if (!existingCachedKey && transaction_key == undefined) {
                                    basic.showAlert('You must first enter your private key or keystore file in order to sign the transaction.', 'boobox-alert', true);
                                    return false;
                                } else if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() == '') {
                                    basic.showAlert('Please enter the secret password for your keystore file.', 'boobox-alert', true);
                                    return false;
                                } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                    basic.showAlert('Please check the checkbox below to continue with the transaction creation.', 'boobox-alert', true);
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
                                                basic.showAlert(decrypted_keystore_file_response.message, 'boobox-alert', true);
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
                                            chainId: assurance_config.chain_id,
                                            data: withdraw_function_abi,
                                            to: assurance_config.assurance_proxy_address
                                        };

                                        const withdraw_transaction = new EthereumTx(withdraw_transaction_obj);
                                        //signing the transaction
                                        withdraw_transaction.sign(new Buffer(transaction_key, 'hex'));

                                        submitTransactionToApi({
                                            slug: $('.single-contract-view-section').attr('data-contract'),
                                            to_status: 'active-withdraw',
                                            patient_address: projectData.utils.checksumAddress($('.single-contract-view-section').attr('data-patient')),
                                            dentist_address: projectData.utils.checksumAddress(global_state.account),
                                            signedUnsubmittedTransaction: '0x' + withdraw_transaction.serialize().toString('hex')
                                        }, function(transactionHash) {
                                            changeContractViewToProcessing();

                                            onSuccessfulContractWithdraw(transactionHash);
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

projectData.initiators.initDatepicker();

projectData.initiators.fixSelectsOnMac();

projectData.initiators.initPopoverTooltips();

projectData.initiators.initMobileMenu();

projectData.initiators.initDateTimePicker();


projectData.pagesData.onInit();

//LOGGED USER LOGIC
if ($('body').hasClass('logged-in')) {
    $(document).on('click', '.please-refill', function() {
        if ($('.external-api-crypto-provider').length) {
            if ($('.external-api-crypto-provider').hasClass('hide')) {
                $('.external-api-crypto-provider').removeClass('hide');
            }

            basic.closeDialog();

            $('.external-api-crypto-provider [data-currency="eth"]').click();

            $('html, body').animate({
                scrollTop: $('.ready-to-purchase-with-external-api .form-container').offset().top
            }, {
                duration: 500
            });
        }
    });

    $(document).on('click', '.continue-with-onsite-transaction-signing', function() {
        if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
            basic.showAlert('Please check the checkbox below to continue with the transaction creation.', 'boobox-alert', true);
        } else {
            $('.onsite-transaction-signing').fadeIn(500);
            $('.change-on-options-selected .hide-on-options-selected').hide();

            if ($('.camp-for-keystore-password #keystore-password').length) {
                $('.bootbox.recipe-popup').animate({ scrollTop: $(document).height() }, '500', function() {
                    $('.camp-for-keystore-password #keystore-password').closest('.custom-google-label-style').find('label').addClass('active-label');
                    $('.camp-for-keystore-password #keystore-password').focus();
                });
            }

            $('.change-on-options-selected').append('<div class="text-center go-back-to-signing-options padding-bottom-20"><a href="javascript:void(0);" class="fs-20 inline-block text-decoration-underline">Go back to signing options</a></div>')
        }
    });

    $(document).on('click', '.go-back-to-signing-options a', function() {
        $('.go-back-to-signing-options').remove();
        $('.change-on-options-selected .hide-on-options-selected').fadeIn(500);
        $('.onsite-transaction-signing').hide();
    });

    //showing the list for each service category
    if ($('.show-category-list a')) {
        $('.show-category-list a').click(function() {
            if ($(this).attr('data-hidden-list') == 'true') {
                $(this).attr('data-hidden-list', 'false').html($(this).attr('data-label-opened'));
            } else {
                $(this).attr('data-hidden-list', 'true').html($(this).attr('data-label-closed'));
            }
            $(this).closest('.show-category-list').find('ul').toggle(300);
        });
    }

    if ($('body').hasClass('create-contract')) {
        var signature_pad_inited = false;
        styleAvatarUploadButton();

        bindTrackerClickDentistCreateWallet();

        projectData.initiators.initTooltips();

        multipleUseWalletAddressesLogic();

        if ($('.single-row.proof-of-address').length) {
            bindVerifyAddressLogic();
        }

        var form_props_arr = ['professional-company-number', 'postal-address', 'country', 'phone', 'website', 'address', 'fname', 'lname', 'email', 'monthly-premium', 'check-ups-per-year', 'teeth-cleaning-per-year'];
        var create_contract_form = $('form#dentist-create-contract');
        create_contract_form.find('.terms-and-conditions-long-list').mCustomScrollbar({
            contentTouchScroll: false
        });

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
                $(this).val(Math.round($(this).val()));
            }

            $(this).closest('.single-row').find('.absolute-currency-label').fadeIn(300).css({'left' : 'calc(40% + ' + (15 + $(this).val().length * 10) + 'px)'});
        });

        $('.on-value-change-color').on('change', function() {
            if ($(this).val().trim() != '') {
                $(this).removeClass('light-gray-color');
            } else {
                $(this).addClass('light-gray-color');
            }
        });

        //validation for all fields for each step
        async function validateStepFields(step_fields, step) {
            step_fields.removeClass('with-error');
            $('.step.'+step+' .single-row').removeClass('row-with-error');
            $('.step.'+step+' .single-row > label span').remove();
            $('.step.'+step+' .error-handle').remove();
            $('.step.'+step+' .with-error').removeClass('with-error');

            var inner_error = false;

            if (step == 'three') {
                if ($('.step.three [name="general-dentistry[]"]:checked').val() == undefined) {
                    $('.step.three .checkboxes-right-container').removeClass('with-error');

                    if ($('.step.three [name="general-dentistry[]"]:checked').val() == undefined) {
                        $('.step.three .checkboxes-right-container').prev().find('span').remove();
                        customCreateContractErrorHandle($('.step.three .checkboxes-right-container'), 'Please select at least one service.');
                        inner_error = true;
                    }
                }

                if ($('.step.three select[name="check-ups-per-year"]').val().trim() == '') {
                    customCreateContractErrorHandle($('.step.three select[name="check-ups-per-year"]'), 'Please select number.');
                    inner_error = true;
                }

                if ($('.step.three select[name="teeth-cleaning-per-year"]').val().trim() == '') {
                    customCreateContractErrorHandle($('.step.three select[name="teeth-cleaning-per-year"]'), 'Please select number.');
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

                if ($('#hidden-image').length && $('#hidden-image').val() == '') {
                    inner_error = true;
                    $('.avatar.module').append('<div class="error-handle">Profile photo is required.</div>');
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
                    basic.showAlert('Please sign the contract sample. Use your mouse or touch screen to sign.', 'boobox-alert', true);
                    event.preventDefault();
                    form_errors = true;
                }else if (!$('.step.four input#terms').is(':checked')) {
                    basic.showAlert('Please accept the Terms and Conditions', 'boobox-alert', true);
                    event.preventDefault();
                    form_errors = true;
                }else if (!$('.step.four input#privacy-policy').is(':checked')) {
                    basic.showAlert('Please accept the Privacy Policy', 'boobox-alert', true);
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
if ($('body').hasClass('logged-in') && !$('.my-contracts-iframe').length) {
    /*var add_overflow_hidden_on_hidden_box_show = false;
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
    });*/

    var miniHubParams = {
        'element_id_to_bind' : 'header-avatar',
        'platform' : 'assurance',
        'log_out_link' : 'https://assurance.dentacoin.com/user-logout',
        'notifications_counter': true
    };

    if ($('body').hasClass('logged-patient')) {
        miniHubParams.type_hub = 'mini-hub-patients';
    } else if ($('body').hasClass('logged-dentist')) {
        miniHubParams.type_hub = 'mini-hub-dentists';
    }

    dcnHub.initMiniHub(miniHubParams);

    $(document).on('click', '.module.contract-tile', function() {
        showLoader();
    });

    $(document).on('click', '.renew-contract-btn', function(event) {
        event.preventDefault();
        fireGoogleAnalyticsEvent('Contract Dentist', 'Renew', 'Contract Renewal');

        window.open($(this).attr('href'));
    });

    if ($('.contract-single-page-nav').length) {
        if ($('.records-history.module').length) {
            $('.show-on-records-history').removeClass('hide');
            $('.show-on-records-history.scroll-init > a').click(function() {
                $('html, body').animate({
                    scrollTop: $('.records-history.module').offset().top
                }, {
                    duration: 500
                });
            });
        }
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
                currency_amount_for_one_usd = parseInt(dcn_for_one_usd);
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
                basic.showAlert('The minimum transaction limit is 10 USD.', 'boobox-alert', true);
            }else if (parseFloat($('section.ready-to-purchase-with-external-api #usd-value').val().trim()) > 6000)  {
                basic.showAlert('The maximum transaction limit is 6000 USD.', 'boobox-alert', true);
            }else if (parseFloat($('section.ready-to-purchase-with-external-api #crypto-amount').val().trim()) < currency_amount_for_one_usd * 10)  {
                basic.showAlert('The minimum transaction limit is 10 USD in '+currency.toUpperCase()+'.', 'boobox-alert', true);
            }else if (parseFloat($('section.ready-to-purchase-with-external-api #crypto-amount').val().trim()) > currency_amount_for_one_usd * 6000)  {
                basic.showAlert('The maximum transaction limit is 6000 USD in '+currency.toUpperCase()+'.', 'boobox-alert', true);
            }else if (!projectData.utils.innerAddressCheck($('section.ready-to-purchase-with-external-api input#dcn_address').val().trim())) {
                basic.showAlert('Please enter a valid wallet address. It should start with "0x" and be followed by 40 characters (numbers and letters).', 'boobox-alert', true);
            }else if (!basic.validateEmail($('section.ready-to-purchase-with-external-api input#email').val().trim()))  {
                basic.showAlert('Please enter a valid email.', 'boobox-alert', true);
            }else if (!$('section.ready-to-purchase-with-external-api #privacy-policy-agree').is(':checked')) {
                basic.showAlert('Please agree with our Privacy Policy.', 'boobox-alert', true);
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
            basic.showAlert('Please enter valid number of patients per day.', 'boobox-alert', true);
            return false;
        } else if (params_type == undefined) {
            basic.showAlert('Please select specialties.', 'boobox-alert', true);
            return false;
        } else if (country == undefined) {
            basic.showAlert('Please select country.', 'boobox-alert', true);
            return false;
        } else if (currency == undefined) {
            basic.showAlert('Please select currency.', 'boobox-alert', true);
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
        history.pushState({},'', '?cross-login=true');
        window.location.reload();
    });

    $(document).on('patientAuthSuccessResponse', async function (event) {
        history.pushState({},'', '?cross-login=true');
        window.location.reload();
    });
}

var croppie_instance;
function styleAvatarUploadButton()    {
    if ($('.avatar.module').length) {
        var inputs = document.querySelectorAll('.avatar.module [type="file"]');
        Array.prototype.forEach.call(inputs, function(input) {
            var this_file_btn_parent = $(input).parent();
            this_file_btn_parent.find('.btn-wrapper').append('<label for="custom-upload-avatar" role="button"><div class="inner"><i class="fa fa-plus" aria-hidden="true"></i><div class="inner-label">Add profile photo</div></div></label>');
            input.addEventListener('change', function(e) {
                var this_input = $(this);
                projectData.utils.readURL(this, 2, ['png', 'jpg', 'jpeg'], function(e, filename) {
                    if (filename != '' && filename != undefined) {
                        $('.avatar-name').show().find('span').html(filename.slice(0, 20) + '...');
                        $('.upload-label-btn').addClass('less-padding');
                    }

                    $('#cropper-container').addClass('width-and-height');
                    if (croppie_instance != undefined) {
                        croppie_instance.croppie('destroy');
                        $('#cropper-container').html('');
                    }

                    var croppieParams = {
                        enableOrientation: true,
                        enforceBoundary: false
                    };

                    if ($(window).width() < 768) {
                        croppieParams.viewport = {
                            width: 200,
                            height: 200
                        };
                        croppieParams.boundary = {width: 200, height: 200};
                    } else {
                        croppieParams.viewport = {
                            width: 180,
                            height: 180
                        };
                        croppieParams.boundary = {width: 180, height: 180};
                    }

                    croppie_instance = $('#cropper-container').croppie(croppieParams);

                    $('.destroy-croppie').unbind().click(function() {
                        croppie_instance.croppie('destroy');
                        $('.avatar.module #hidden-image').val('');
                        $('#cropper-container').html('');
                        $('#cropper-container').removeClass('width-and-height');
                        $('.avatar.module .btn-wrapper').show();
                        $('.avatar-name').hide();
                        $('.dentist .form-register .step.fourth #custom-upload-avatar').val('');
                    });

                    $('.avatar.module .btn-wrapper').hide();

                    croppie_instance.croppie('bind', {
                        url: e.target.result,
                        zoom: 1
                    });

                    /*croppie_instance.croppie('bind', 'url').then(function(){
                        croppie_instance.croppie('setZoom', 1);
                    });*/

                    $('#cropper-container').on('update.croppie', function(ev, cropData) {
                        croppie_instance.croppie('result', {
                            type: 'canvas',
                            size: {width: 300, height: 300}
                        }).then(function (src) {
                            $('#hidden-image').val(src);
                        });
                    });
                }, function() {
                    this_input.val('');

                    $('.avatar.module').append('<div class="error-handle">The file you selected is large. Max size: 2MB.</div>');
                }, function() {
                    this_input.val('');

                    $('.avatar.module').append('<div class="error-handle">Allowed file formats are only .png, .jpeg and .jpg.</div>');
                });
            });
            // Firefox bug fix
            input.addEventListener('focus', function(){ input.classList.add('has-focus'); });
            input.addEventListener('blur', function(){ input.classList.remove('has-focus'); });
        });
    }
}

//hide bootbox popup when its clicked around him (outside of him)
function hidePopupOnBackdropClick() {
    $(document).on('click', '.bootbox', function(event){
        var classname = event.target.className;

        classname = classname.replace(/ /g, '.');

        if ($(event.target).hasClass('pending-contract-record')) {
            $('.pending-contract-record').trigger('escape');
        } else if ($(event.target).hasClass('confirmRecord')) {
            $('.confirmRecord').trigger('escape');
        }if ($(event.target).hasClass('declineRecord')) {
            $('.declineRecord').trigger('escape');
        }

        if (classname && !$('.' + classname).parents('.modal-dialog').length) {
            $('.' + classname).modal('hide');
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

        if (!basic.validateUrl(this_form.find('input[name="website"]').val().trim())) {
            customErrorHandle(this_form.find('input[name="website"]').parent(), 'Please enter website URL starting with http:// or https://.');
            errors = true;
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

            if (this_btn.attr('data-processing-contract') == 'true') {
                showContractAttentionInProcess();
            } else {
                if (this_btn.attr('data-patient') != undefined && this_btn.attr('data-dentist') != undefined) {
                    //CHECK FOR CONTRACT ON THE BLOCKCHAIN
                    var exiting_contract = await dApp.assurance_state_methods.getPatient(this_btn.attr('data-patient'), this_btn.attr('data-dentist'));
                    if ((new Date(parseInt(exiting_contract[0]) * 1000)).getTime() > 0) {
                        if (metamask) {
                            basic.showAlert('Using MetaMask is currently not supported in Dentacoin Assurance. Please switch off MetaMask extension and try again.');
                        } else {
                            // check if dentists is contact canceling initiator and check if any DCN can be withdrawn, if yes then show warning
                            var contract_next_payment = parseInt(exiting_contract[0]);
                            var now_timestamp = Math.round((new Date()).getTime() / 1000);

                            var usdOverDcn = await dApp.assurance_state_methods.getUsdOverDcn();
                            if (usdOverDcn) {
                                var monthly_premium_in_dcn = Math.round(projectData.utils.convertUsdToDcn(exiting_contract[4]));
                            } else {
                                var monthly_premium_in_dcn = parseInt(exiting_contract[5]);
                            }
                            
                            var patient_dcn_balance = parseInt(await dApp.dentacoin_token_methods.balanceOf(this_btn.attr('data-patient')));

                            function proceedCancelling() {
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

                                            //var ethgasstation_json = await $.getJSON('https://ethgasstation.info/json/ethgasAPI.json');
                                            //const on_page_load_gwei = ethgasstation_json.safeLow;
                                            var gasPriceObject = await projectData.requests.getGasPrice();
                                            const on_page_load_gwei = gasPriceObject.result.SafeGasPrice;
                                            //adding 10% just in case the transaction dont fail
                                            const on_page_load_gas_price = on_page_load_gwei * 1000000000 + ((on_page_load_gwei * 1000000000) * projectData.variables.bonusPercentagesToGasEstimations / 100);

                                            //for the estimation going to use our internal address which aldready did gave before his allowance in DentacoinToken contract. In order to receive the gas estimation we need to pass all the method conditions and requires
                                            var gas_cost_for_contract_cancellation = await dApp.assurance_proxy_instance.methods.breakContract(projectData.utils.checksumAddress(response.contract_data.patient), projectData.utils.checksumAddress(response.contract_data.dentist)).estimateGas({
                                                from: global_state.account,
                                                gas: 300000
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
                                                $('.camp-for-keystore-password').html('<div class="lato-regular fs-30 text-center padding-bottom-20 padding-top-15">Enter your keystore secret password</div><div class="padding-bottom-20"><div class="custom-google-label-style module max-width-280 margin-0-auto" data-input-blue-green-border="true"><label for="keystore-password">Secret password:</label><input type="password" maxlength="30" id="keystore-password" class="full-rounded keystore-password cached"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn min-width-250 margin-bottom-10 width-xs-100 max-width-400 fire-blockchain-transaction">SIGN TRANSACTION</a></div></div>');
                                            }

                                            // proceed to dentacoin wallet scanning
                                            $('.generate-qr-code-for-wallet-scanning').click(async function() {
                                                var current_user_eth_balance = parseFloat(dApp.web3_1_0.utils.fromWei(await dApp.helper.getAddressETHBalance(global_state.account)));
                                                if (parseFloat(eth_fee) > current_user_eth_balance) {
                                                    //not enough ETH balance
                                                    basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. <a href="javascript:void(0);" class="text-decoration-underline blue-green-color please-refill">Please refill.</a></div>', 'boobox-alert', true);
                                                } else if ($('.recipe-popup #cancel-contract-other-reason').length && $('.recipe-popup #cancel-contract-other-reason').val().trim() == '') {
                                                    basic.showAlert('Please enter other reason.', 'boobox-alert', true);
                                                } else if ($('.recipe-popup #cancel-contract-reason').val() == null) {
                                                    basic.showAlert('Please select cancellation reason.', 'boobox-alert', true);
                                                } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                    basic.showAlert('Please check the checkbox below to continue with the QR code generation.', 'boobox-alert', true);
                                                } else {
                                                    $('.onsite-transaction-signing').fadeOut(500);
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
                                                    basic.showAlert('<div class="text-center fs-18">You don\'t have enough ETH balance to create and sign this transaction on the blockchain. <a href="javascript:void(0);" class="text-decoration-underline blue-green-color please-refill">Please refill.</a></div>', 'boobox-alert', true);
                                                } else {
                                                    if ($('.recipe-popup #cancel-contract-other-reason').length && $('.recipe-popup #cancel-contract-other-reason').val().trim() == '') {
                                                        basic.showAlert('Please enter other reason.', 'boobox-alert', true);
                                                    } else if ($('.recipe-popup #cancel-contract-reason').val() == null) {
                                                        basic.showAlert('Please select cancellation reason.', 'boobox-alert', true);
                                                    } /*else if ($('.recipe-popup #cancel-contract-comments').val().trim() == '') {
                                                basic.showAlert('Please enter comments.', 'boobox-alert', true);
                                            }*/ else if (!existingCachedKey && transaction_key == undefined) {
                                                        basic.showAlert('You must first enter your private key or keystore file in order to sign the transaction.', 'boobox-alert', true);
                                                        return false;
                                                    } else if (existingCachedKey && $('.camp-for-keystore-password input[type="password"]').val().trim() == '') {
                                                        basic.showAlert('Please enter the secret password for your keystore file.', 'boobox-alert', true);
                                                        return false;
                                                    } else if (!$('.recipe-popup input#understand-and-agree').is(':checked')) {
                                                        basic.showAlert('Please check the checkbox below to continue with the transaction creation.', 'boobox-alert', true);
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
                                                                    basic.showAlert(decrypted_keystore_file_response.message, 'boobox-alert', true);
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
                                                                chainId: assurance_config.chain_id,
                                                                data: contract_cancellation_function_abi,
                                                                to: assurance_config.assurance_proxy_address
                                                            };

                                                            const contract_cancellation_transaction = new EthereumTx(contract_cancellation_transaction_obj);
                                                            //signing the transaction
                                                            contract_cancellation_transaction.sign(new Buffer(transaction_key, 'hex'));

                                                            submitTransactionToApi({
                                                                slug: this_btn.attr('data-contract'),
                                                                to_status: 'cancelled',
                                                                patient_address: projectData.utils.checksumAddress(response.contract_data.patient),
                                                                dentist_address: projectData.utils.checksumAddress(response.contract_data.dentist),
                                                                type: this_btn.attr('data-type'),
                                                                reason: cancellation_ajax_data.reason,
                                                                comments: cancellation_ajax_data.comments,
                                                                signedUnsubmittedTransaction: '0x' + contract_cancellation_transaction.serialize().toString('hex')
                                                            }, function() {
                                                                changeContractViewToProcessing();

                                                                onSuccessfulContractCancel();
                                                            });
                                                        }, 2000);
                                                    }
                                                }
                                            });
                                        } else if (response.error) {
                                            basic.showAlert(response.error, 'boobox-alert', true);
                                        }
                                    }
                                });
                            }

                            if (contract_next_payment < now_timestamp && patient_dcn_balance >= monthly_premium_in_dcn && this_btn.attr('data-type') == 'dentist') {
                                basic.showDialog('<div class="wrapper text-center"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-20"><img class="max-width-50" src="/assets/images/attention.svg" alt="attention icon" class="max-width-70"></figure><div class="lato-bold fs-30" style="color: #ff8d8d;">WARNING!</div><div class="black-warning lato-bold fs-24 dark-color">YOU HAVE UNCOLLECTED PAYMENTS.</div><div class="additional-text padding-top-20 padding-bottom-30 fs-20">Withdraw before canceling this contract.<br> Otherwise, you will irreversibly lose your DCN.</div><div class="text-center"><a href="javascript:void(0)" class="proceed-cancelling red-white-btn inline-block max-width-200 margin-bottom-10 margin-left-5 margin-right-5 width-100">CANCEL NOW</a><a href="javascript:void(0)" class="dentist-withdraw white-blue-green-btn inline-block max-width-200 margin-bottom-10 margin-left-5 margin-right-5 width-100">WITHDRAW NOW</a></div></div>', '', null, true);

                                $('.proceed-cancelling').click(function() {
                                    proceedCancelling();
                                });

                                var period_to_withdraw = parseInt(await dApp.assurance_state_methods.getPeriodToWithdraw());
                                var months_dentist_didnt_withdraw = Math.floor((now_timestamp - contract_next_payment) / period_to_withdraw) + 1;

                                if (usdOverDcn) {
                                    var withdrawableUSD = months_dentist_didnt_withdraw * exiting_contract[4];
                                    var withdrawableDCN = Math.round(projectData.utils.convertUsdToDcn(withdrawableUSD));
                                } else {
                                    var withdrawableDCN = months_dentist_didnt_withdraw * monthly_premium_in_dcn;
                                    var withdrawableUSD = Math.round(projectData.utils.convertDcnToUsd(withdrawableDCN));
                                }

                                bindDentistWithdrawEvent(withdrawableDCN, withdrawableUSD);
                            } else {
                                proceedCancelling();
                            }
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
                                        basic.showAlert('Please enter other reason.', 'boobox-alert', true);
                                    } else if ($('.popup-cancel-contract #cancel-contract-reason').val() == null) {
                                        basic.showAlert('Please select cancellation reason.', 'boobox-alert', true);
                                    } /*else if ($('.popup-cancel-contract #cancel-contract-comments').val().trim() == '') {
                                    basic.showAlert('Please enter comments.', 'boobox-alert', true);
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
                                                    basic.showAlert(inner_response.error, 'boobox-alert', true);
                                                }
                                            }
                                        });
                                    }
                                });
                            } else if (response.error) {
                                basic.showAlert('Wrong contract.', 'boobox-alert', true);
                            }
                        }
                    });
                }
            }
        });
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
                                        $('.proof-of-address .on-change-result').html('<div class="max-width-500 margin-0-auto padding-top-5"><div class="fs-14 light-gray-color text-center padding-bottom-10 file-name">'+fileName+'</div><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-secret-key-password">Secret password:</label><input type="password" id="your-secret-key-password" maxlength="100" class="full-rounded"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn cache-key-btn">REMEMBER</a></div></div>');

                                        $('.bootbox.recipe-popup').animate({ scrollTop: $(document).height() }, '500', function() {
                                            $('.proof-of-address .on-change-result #your-secret-key-password').closest('.custom-google-label-style').find('label').addClass('active-label');
                                            $('.proof-of-address .on-change-result #your-secret-key-password').focus();
                                        });

                                        bindCacheKeyEvent(keystore_string);
                                    } else {
                                        var btn_name = 'VERIFY';
                                        if (button_label != null) {
                                            btn_name = button_label;
                                        }
                                        $('.proof-of-address .on-change-result').html('<div class="max-width-500 margin-0-auto padding-top-5"><div class="fs-14 light-gray-color text-center padding-bottom-10 file-name">'+fileName+'</div><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-secret-key-password">Secret password:</label><input type="password" id="your-secret-key-password" maxlength="100" class="full-rounded"/></div><div class="checkbox-container max-width-250 margin-0-auto"><div class="pretty p-svg p-curve on-white-background margin-bottom-0"><input type="checkbox" id="remember-my-keystore-file" checked/><div class="state p-success"><svg class="svg svg-icon" viewBox="0 0 20 20"><path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path></svg><label class="fs-14 calibri-bold" for="remember-my-keystore-file">Remember my keystore file <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Remembering your keystore file allows for easier and faster transactions. It is stored only in your browser and nobody else has access to it."></i></label></div></div></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn verify-address-btn">'+btn_name+'</a></div></div>');

                                        $('.bootbox.recipe-popup').animate({ scrollTop: $(document).height() }, '500', function() {
                                            $('.proof-of-address .on-change-result #your-secret-key-password').closest('.custom-google-label-style').find('label').addClass('active-label');
                                            $('.proof-of-address .on-change-result #your-secret-key-password').focus();
                                        });

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
                                                    basic.showAlert(decrypt_response.message, 'boobox-alert', true);
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
                                    basic.showAlert('Please upload valid keystore file which is related to the Wallet Address saved in your profile.', 'boobox-alert', true);
                                }
                            } else {
                                $('#upload-keystore-file').val('');
                                basic.showAlert('Please upload valid keystore file which is related to the Wallet Address saved in your profile.', 'boobox-alert', true);
                            }
                        } else {
                            basic.showAlert('You don\'t have any Wallet Address saved in our database.', 'boobox-alert', true);
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
        $('.proof-of-address .on-change-result').html('<div class="max-width-500 margin-0-auto padding-top-20"><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-private-key">Your Private Key:</label><input type="text" id="your-private-key" maxlength="64" class="full-rounded"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn verify-address-btn">VERIFY</a></div></div>');
        $('.proof-of-address [for="your-private-key"]').addClass('active-label');
        $('.proof-of-address #your-private-key').focus();

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
        if (keystore_file != null) {
            // get all multiple user addresses
            var currentUserAddressesResponse = await getUserAddresses($('.proof-of-address').attr('data-id'));
            if (currentUserAddressesResponse.success) {
                var keystoreFileAddress = projectData.utils.checksumAddress('0x' + JSON.parse(keystore_file).address);
                var currentUserAddresses = [];
                for(var i = 0, len = currentUserAddressesResponse.data.length; i< len; i+=1) {
                    currentUserAddresses.push(projectData.utils.checksumAddress(currentUserAddressesResponse.data[i].dcn_address));
                }

                //import with keystore
                if (currentUserAddresses.indexOf(keystoreFileAddress) != -1) {
                    basic.showAlert('Please enter valid keystore file for your Wallet Address.', 'boobox-alert', true);
                } else if ($('.proof-of-address #your-secret-key-password').val().trim() == '' || $('.proof-of-address #your-secret-key-password').val().trim().length > 100 || $('.proof-of-address #your-secret-key-password').val().trim().length < 6) {
                    basic.showAlert('Please enter valid secret key password with length between 6 and 100 symbols.', 'boobox-alert', true);
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
                                        basic.showAlert(inner_response.error, 'boobox-alert', true);
                                    }
                                }
                            });
                        } else if (import_response.error) {
                            hideLoader();
                            basic.showAlert(import_response.message, 'boobox-alert', true);
                        }
                    }, 1000);
                }
            } else {
                basic.showAlert('You don\'t have any Wallet Address saved in our database.', 'boobox-alert', true);
            }
        } else {
            //import with private key
            if ($('.proof-of-address #your-private-key').val().trim() == '' || $('.proof-of-address #your-private-key').val().trim().length > 64) {
                basic.showAlert('Please enter valid private key.', 'boobox-alert', true);
                hideLoader();
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
                            basic.showAlert(decrypted_pdf_response.message, 'boobox-alert', true);
                        }
                    } else {
                        var import_response = importPrivateKey($('.proof-of-address #your-private-key').val().trim());
                        //now with the address and the public key received from the nodejs api update the db
                        if (import_response.success) {
                            //checking if fake private key or just miss spell it
                            if (currentUserAddresses.indexOf(projectData.utils.checksumAddress(import_response.address)) != -1) {
                                basic.showAlert('Please enter private key related to the Wallet Address you have entered in Wallet Address field.', 'boobox-alert', true);
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
                                            basic.showAlert(inner_response.error, 'boobox-alert', true);
                                        }
                                    }
                                });
                            }
                        } else if (import_response.error) {
                            hideLoader();
                            basic.showAlert(import_response.message, 'boobox-alert', true);
                        }
                    }
                }, 1000);
            }
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
                    basic.showAlert('Please enter valid secret key password with length between 6 and 100 symbols.', 'boobox-alert', true);
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
                        basic.showAlert(decrypt_response.message, 'boobox-alert', true);
                        hideLoader();
                    }
                }
            } else {
                //import with private key
                if ($('.proof-of-address #your-private-key').val().trim() == '' || $('.proof-of-address #your-private-key').val().trim().length > 64) {
                    basic.showAlert('Please enter valid private key.', 'boobox-alert', true);
                    hideLoader();
                } else {
                    var import_response = importPrivateKey($('.proof-of-address #your-private-key').val().trim());
                    if (import_response.success) {
                        //checking if fake private key or just miss spell it
                        if (global_state.account != projectData.utils.checksumAddress(import_response.address)) {
                            basic.showAlert('Please enter private key related to the Wallet Address you have saved in your profile.', 'boobox-alert', true);
                            hideLoader();
                        } else {

                            $.event.trigger({
                                type: 'on-transaction-recipe-agree',
                                time: new Date(),
                                response_data: $('.proof-of-address #your-private-key').val().trim()
                            });
                        }
                    } else if (import_response.error) {
                        basic.showAlert(import_response.message, 'boobox-alert', true);
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

            var keystoreFileAddress = projectData.utils.checksumAddress('0x' + JSON.parse(keystore_file).address);
            if (currentUserAddresses.indexOf(keystoreFileAddress) != -1) {
                if ($('.proof-of-address #your-secret-key-password').val().trim() == '' || $('.proof-of-address #your-secret-key-password').val().trim().length > 100 || $('.proof-of-address #your-secret-key-password').val().trim().length < 6) {
                    basic.showAlert('Please enter valid secret key password with length between 6 and 100 symbols.', 'boobox-alert', true);
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
                                    basic.showAlert('Your wallet has been remembered successfully. If you want to delete your private key or keystore file you can do this from Manage Privacy section in your profile.', 'boobox-alert', true);
                                }
                            });
                        } else if (import_response.error) {
                            hideLoader();
                            basic.showAlert(import_response.message, 'boobox-alert', true);
                        }
                    }, 1000);
                }
            } else {
                basic.showAlert('Please enter valid keystore file for your Wallet Address.', 'boobox-alert', true);
            }
        } else {
            basic.showAlert('You don\'t have any Wallet Address saved in our database.', 'boobox-alert', true);
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
                    $('.proof-of-address .on-change-result').html('<div class="max-width-500 margin-0-auto padding-top-20"><div class="custom-google-label-style module" data-input-blue-green-border="true"><label for="your-private-key">Your Private Key:</label><input type="text" id="your-private-key" maxlength="64" class="full-rounded"/></div><div class="text-center padding-top-15"><a href="javascript:void(0)" class="white-blue-green-btn verify-address-btn">UNLOCK</a></div></div>');

                    $('.proof-of-address [for="your-private-key"]').addClass('active-label');
                    $('.proof-of-address #your-private-key').focus();

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
            $('.camping-for-validation').html('<div class="single-row proof-of-address padding-bottom-20"><div class="text-center calibri-bold fs-18 padding-top-20 padding-bottom-15">PLEASE VERIFY YOU OWN THIS ADDRESS</div><div class="fs-0 text-center"><div class="min-width-260 inline-block padding-left-30 padding-left-xs-0 width-xs-100 max-width-300"><a href="javascript:void(0)" class="blue-green-white-btn text-center enter-private-key display-block-important fs-18 line-height-18"><span>Enter your Private Key<div class="fs-16">(not recommended)</div></span></a></div><div class="padding-left-15 padding-right-15 padding-top-10 padding-bottom-10 calibri-bold fs-20 inline-block display-block-xs">or</div><div class="min-width-260 inline-block padding-right-30 padding-right-xs-0 width-xs-100 max-width-300"><div class="upload-file-container" data-id="upload-keystore-file" data-label="Upload your Keystore file"><input type="file" id="upload-keystore-file" class="custom-upload-file hide-input"/><div class="btn-wrapper"></div></div></div></div><div class="on-change-result"></div></div><div class="single-row proof-success no-transition padding-top-20 padding-bottom-20 fs-20 calibri-bold text-center">Successful address verification.</div>');
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

/*function showWarningTestingVersion() {
    if (basic.cookies.get('warning-test-version') != '1' && !$('.my-contracts-iframe').length) {
        basic.showDialog('<div class="container-fluid"><div class="row fs-0"><div class="col-xs-12 col-sm-6 col-md-5 col-md-offset-1 inline-block"><img src="/assets/images/warning-pop-up.png" class="hide-xs"></div><div class="col-xs-12 col-md-5 col-sm-6 text-center inline-block padding-top-20 padding-bottom-20"><div class="warning"><img class="max-width-50" src="/assets/images/attention.svg" alt="attention icon"></div><div class="lato-bold fs-30" style="color: #ff8d8d;">WARNING:</div><div class="black-warning lato-bold fs-30 dark-color">THIS IS A TEST WEBSITE VERSION.</div><div class="additional-text padding-top-20 padding-bottom-20 fs-20">Please do not make any transactions as your funds will be lost.We will notify you via email when the official version is launched.</div><div class="btn-container"><a href="javascript:void(0)" class="white-blue-green-btn min-width-220 understood">I UNDERSTAND</a></div></div></div></div>', 'warning-test-version', true);
        $('.warning-test-version .understood').click(function() {
            if (basic.cookies.get('strictly_necessary_policy') != '1') {
                basic.showAlert('Please accept the strictly necessary cookies.', 'boobox-alert', true);
            } else {
                basic.cookies.set('warning-test-version', 1);
                basic.closeDialog();
            }
        });

    }
}
showWarningTestingVersion();*/

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
    basic.showDialog('<div class="icon-title-row padding-top-35"><svg class="inline-block max-width-70 padding-right-20" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 72.5 92.8" style="enable-background:new 0 0 72.5 92.8;" xml:space="preserve"><style type="text/css">.shield-st0{fill:#126585;}.shield-st1{clip-path:url(#SVGID_2_);fill:#126585;}</style><metadata><sfw xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds bottomLeftOrigin="true" height="92.7" width="72.4" x="0.1" y="0"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><path class="shield-st0" d="M36,952.4c-0.2,0-0.3,0.1-0.5,0.1L1.6,965c-0.9,0.3-1.5,1.2-1.5,2.1v37.3c0,8.8,5,16.2,11.7,22.6s15.3,12.1,23.2,17.7c0.8,0.6,1.8,0.6,2.6,0c7.9-5.6,16.5-11.3,23.2-17.7c6.7-6.4,11.7-13.7,11.7-22.6v-37.3c0-0.9-0.6-1.8-1.5-2.1l-33.9-12.4C36.7,952.4,36.3,952.4,36,952.4z M36.3,957.1L68,968.7v35.7c0,7.1-4,13.3-10.3,19.3c-5.9,5.7-13.8,11-21.4,16.3c-7.6-5.4-15.5-10.7-21.4-16.3c-6.3-6-10.3-12.2-10.3-19.3v-35.7L36.3,957.1z"/></g><g><defs><rect id="SVGID_1_" x="12.2" y="18.2" width="48.2" height="47.5"/></defs><clipPath id="SVGID_2_"><use xlink:href="#SVGID_1_" style="overflow:visible;"/></clipPath><path class="shield-st1" d="M56.1,14.6c4.7,0,8.3,3.6,8.3,8.3v38.7c0,4.7-3.6,8.3-8.3,8.3H17.4c-4.7,0-8.3-3.6-8.3-8.3V22.8c0-4.7,3.6-8.3,8.3-8.3H56.1z M56.1,9H17.4C9.7,9,3.6,15.1,3.6,22.8v38.7c0,7.7,6.1,13.8,13.8,13.8h38.7c7.7,0,13.8-6.1,13.8-13.8V22.8C69.9,15.1,63.8,9,56.1,9z M34,31.1h-2.8v-5.5H34v2.8h5.5v2.8h-2.8v2.8H34V31.1z M39.5,64.3v-2.8h-2.8v2.8H39.5z M36.7,22.8v-2.8h-5.5v2.8H34v2.8h2.8V22.8z M58.8,20.1v16.6h-2.8v-2.8H45V20.1H58.8z M56.1,31.1v-8.3h-8.3v8.3H56.1z M17.4,36.6h-2.8v2.8h5.5v-2.8H17.4z M17.4,44.9h2.8v2.8h2.8v-8.3h-2.8v2.8h-5.5v5.5h2.8V44.9z M31.2,47.7v5.5H34v-5.5H31.2z M42.3,20.1h-2.8v8.3h2.8V20.1z M42.3,33.9v-2.8h-2.8v2.8H42.3z M50.5,28.4h2.8v-2.8h-2.8V28.4z M22.9,25.6h-2.8v2.8h2.8V25.6z M28.4,20.1v13.8H14.6V20.1H28.4z M25.7,22.8h-8.3v8.3h8.3V22.8z M50.5,36.6v2.8h-2.8v-2.8h-5.5v2.8h-2.8v-2.8h-8.3v-2.8h-2.8v2.8h-2.8v2.8h2.8v5.5h2.8v-2.8H34v5.5h2.8v-5.5H45v2.8h-5.5v2.8H45v2.8h2.8v-8.3h2.8v2.8h2.8v5.5h2.8v-2.8h2.8v-2.8h-2.8v-2.8h-2.8v-2.8h2.8v-2.8H50.5z M20.2,58.7h2.8V56h-2.8V58.7z M14.6,50.5h13.8v13.8H14.6V50.5z M17.4,61.5h8.3v-8.3h-8.3V61.5z M50.5,53.2v-2.8h-2.8v2.8H50.5z M50.5,56h-2.8v2.8H45V56h-2.8v-2.8H45v-2.8h-5.5v-2.8h-2.8v5.5H34V56h-2.8v8.3H34v-2.8h2.8v-2.8h5.5v5.5H45v-2.8h2.8v2.8h5.5v-2.8h2.8v-2.8h-5.5V56z M25.7,47.7h2.8v-2.8h-2.8V47.7z M53.3,53.2V56h2.8v2.8h2.8v-8.3h-2.8v2.8H53.3z M58.8,64.3v-2.8h-2.8v2.8H58.8z M58.8,42.2v-2.8h-2.8v2.8H58.8z"/></g></svg><h3 class="inline-block fs-26 fs-xs-20 lato-bold">Scan QR code in Your Dentacoin Wallet App</h3></div><div><ul><li><span>1.</span> Open Dentacoin Wallet app on your phone.<div class="download-app-buttons fs-0 padding-top-10 padding-bottom-20"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block text-right text-center-xs"> <a href="https://play.google.com/store/apps/details?id=wallet.dentacoin.com" target="_blank"> <img src="/assets/images/google-play-badge.svg" class="width-100 max-width-170" alt="Google Play button"> </a> </figure> <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block text-left text-center-xs padding-top-xs-20"> <a href="https://apps.apple.com/us/app/dentacoin-wallet/id1478732657" target="_blank"> <img src="/assets/images/app-store.svg" class="width-100 max-width-170" alt="App Store button"> </a> </figure></div></li><li><span>2.</span> Go to SPEND -> Manage DCN Assurance and click on the "SCAN TRANSACTION" button.</li><li><span>3.</span> Scan the QR below and confirm the action. </li></ul></div><figure itemscope="" itemtype="http://schema.org/ImageObject" id="popup-qrcode" class="padding-bottom-30"></figure>', 'qr-code-for-dentacoin-wallet-scan', true);

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
    var trackForContractStatusChangeTimer = setInterval(function() {
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
                        clearInterval(trackForContractStatusChangeTimer);

                        changeInStatusFound = true;
                        window.onbeforeunload = null;
                        window.location.reload();
                    }
                }
            });
        }
    }, 5000);
}

function trackForWalletSigning(slug, to_status) {
    var walletSigningFound = false;
    var trackForWalletSigningTimer = setInterval(function() {
        if (allowAutomaticScripts) {
            $.ajax({
                type: 'POST',
                url: '/check-contract-signing',
                dataType: 'json',
                data: {
                    slug: slug,
                    to_status: to_status
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: async function (response) {
                    if (response.success && !walletSigningFound) {
                        walletSigningFound = true;
                        clearInterval(trackForWalletSigningTimer);

                        if (to_status == 'awaiting-approval') {
                            changeContractViewToProcessing();

                            onSuccessfulContractCreation();
                        } else if (to_status == 'active') {
                            changeContractViewToProcessing();

                            onSuccessfulContractApproval(response.patient_name);
                        } else if (to_status == 'active-withdraw') {
                            changeContractViewToProcessing();

                            onSuccessfulContractWithdraw(response.transactionHash);
                        }
                    }
                }
            });
        }
    }, 2000);
}

function trackForWalletCancel(slug) {
    var walletCancelFound = false;
    var trackForWalletCancelTimer = setInterval(function() {
        if (allowAutomaticScripts) {
            $.ajax({
                type: 'POST',
                url: '/check-contract-signing',
                dataType: 'json',
                data: {
                    slug: slug,
                    to_status: 'cancelled'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: async function (response) {
                    if (response.success && !walletCancelFound) {
                        walletCancelFound = true;
                        clearInterval(trackForWalletCancelTimer);

                        changeContractViewToProcessing();
                        onSuccessfulContractCancel();
                    }
                }
            });
        }
    }, 2000);
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
                                basic.showAlert(response.message, 'boobox-alert', true);
                            } else if (response.error) {
                                basic.showAlert(response.message, 'boobox-alert', true);
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
                    basic.showAlert('Please enter name.', 'boobox-alert', true);
                } else if ($('.popup-save-to-address-book #wallet-address').val().trim() == '' || !projectData.utils.innerAddressCheck($('.popup-save-to-address-book #wallet-address').val().trim())){
                    basic.showAlert('Please enter valid Wallet Address.', 'boobox-alert', true);
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
                                basic.showAlert(response.message, 'boobox-alert', true);

                                $('.search-input').val(walletAddress).trigger('change');

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
                                basic.showAlert(response.message, 'boobox-alert', true);
                            }
                        }
                    });
                }
            });
        });
    }
}

function submitTransactionToApi(data, successCallback) {
    data.network = $('body').attr('data-network');
    $.ajax({
        type: 'POST',
        url: 'http://assurance.dentacoin.com/submit-assurance-transaction',
        dataType: 'json',
        data: data,
        success: function(response) {
            hideLoader();
            basic.closeDialog();
            if(response.success) {
                successCallback(response.transactionHash);
            } else {
                basic.showAlert('Something went wrong. Please try again later or write a message to admin@dentacoin.com with description of the problem.', '', true);
            }
        }
    });
}

function onSuccessfulContractCreation() {
    hideLoader();
    basic.closeDialog();

    if ($('.camping-for-popups').length) {
        $('.camping-for-popups').html('');
    }

    $('.ready-to-purchase-with-external-api').hide();
    $('.single-contract-tile .inline-block-bottom').addClass('inline-block').removeClass('inline-block-bottom');

    basic.showDialog('<div class="text-center padding-top-30"><svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg><div class="lato-bold fs-30">WELL DONE!</div><div class="padding-top-20 padding-bottom-15 fs-20">Your dentist has been notified. Once they approve it, your smart contract will be automatically activated.</div><div class="btn-container padding-bottom-40"><a href="javascript:void(0)" class="white-blue-green-btn min-width-200 close-popup">OK</a></div></div>', '', null, true);
}

function onSuccessfulContractApproval(patientName) {
    hideLoader();
    basic.closeDialog();

    if ($('.approve-button-container').length) {
        $('.approve-button-container').remove();
    }

    basic.showDialog('<div class="text-center padding-top-30"><svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg><div class="lato-bold fs-30">SUCCESSFULLY APPROVED</div><div class="padding-top-20 padding-bottom-15 fs-20">Your contract with '+patientName+' has been successfully approved and activated. You will be notified when the first monthly payment is available.</div><div class="btn-container padding-bottom-40"><a href="javascript:void(0)" class="white-blue-green-btn min-width-200 close-popup">OK</a></div></div>', '', null, true);
}

function onSuccessfulContractWithdraw(hash) {
    hideLoader();
    basic.closeDialog();

    if ($('.camping-for-popups').length) {
        $('.camping-for-popups').html('');
    }

    basic.showDialog('<div class="text-center padding-top-30"><svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg><div class="lato-bold fs-30">SUCCESSFULLY WITHDRAWN</div><div class="padding-top-20 padding-bottom-30 fs-20">You have successfully withdrawn your DCN from this contract. You will be notified via email when next withdraw is possible.</div><div class="btn-container padding-bottom-20 text-left fs-0"><div class="width-50 width-xs-100 text-center-xs padding-bottom-15 padding-bottom-xs-30 inline-block text-right padding-right-10 padding-left-10"><a href="http://etherscan.io/tx/'+hash+'" target="_blank" class="fs-18 text-decoration-underline">Check on Etherscan</a></div><div class="width-50 width-xs-100 text-center-xs padding-bottom-15 inline-block padding-right-10 padding-left-10"><a href="javascript:void(0);" class="white-blue-green-btn close-popup">OK, GOOD</a></div><div class="fs-14">* Etherscan is the public registry of all crypto transactions happening in the world. It is the most trustworthy source to verify if a transaction is completed.</div></div></div>', '', null, true);
}

function onSuccessfulContractCancel() {
    hideLoader();
    basic.closeDialog();

    if ($('.camping-for-popups').length) {
        $('.camping-for-popups').html('');
    }
}

function changeContractViewToProcessing() {
    var mobileHtml = '<div class="row"> <div class="attention-in-process fs-18 text-center padding-bottom-15 col-xs-12"> <a href="javascript:void(0);"> <div class="padding-bottom-10"><img src="/assets/images/processing-contract-loading.gif" class="width-100 max-width-80" alt="Processing contact loader"/></div>ATTENTION: ACTION IN PROCESS <img src="/assets/images/question-mark.svg" class="margin-left-5 width-100 max-width-20" alt="Question mark"/> </a> </div></div>';
    var desktopHtml = '<div class="attention-in-process fs-16 text-center padding-bottom-10 padding-left-10 padding-right-10"> <a href="javascript:void(0);"> <div class="padding-bottom-10"><img src="/assets/images/processing-contract-loading.gif" class="width-100 max-width-80" alt="Processing contact loader"/></div>ATTENTION: ACTION IN PROCESS <img src="/assets/images/question-mark.svg" class="margin-left-5 width-100 max-width-20" alt="Question mark"/> </a></div>';

    if ($('body').hasClass('logged-patient')) {
        $('.patient-contract-single-page-section').attr('data-processing-contract', 'true');
    } else if ($('body').hasClass('logged-dentist')) {
        $('.single-contract-view-section').attr('data-processing-contract', 'true');
    }

    // show the processing label
    if ($('body').hasClass('mobile')) {
        $('.single-contract-tile').prepend(mobileHtml);
    } else if ($('body').hasClass('not-mobile')) {
        $('.contract-body').prepend(desktopHtml);
    }

    // remove timers
    if ($('body').hasClass('mobile')) {
        if ($('.contract-footer').length) {
            $('.contract-footer').remove();
        }
    } else if ($('body').hasClass('not-mobile')) {
        if ($('.contract-body .wrapper').length) {
            $('.contract-body .wrapper').remove();
        }
        if ($('.contract-body .steps-navigation').length) {
            $('.contract-body .steps-navigation').remove();
        }
        $('.contract-body .steps-navigation').remove();
    }

    $('.cancel-contract-btn').attr('data-processing-contract', 'true');

    $('html, body').animate({scrollTop: 0}, 300);
}

function showContractAttentionInProcess() {
    basic.closeDialog();
    basic.showDialog('<div class="text-center padding-top-30"><svg class="width-100 max-width-100" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 96.4 96.4" style="enable-background:new 0 0 96.4 96.4;" xml:space="preserve"><style type="text/css">.processing-icon{fill:#126585;}</style><metadata><sfw xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds bottomLeftOrigin="true" height="96.4" width="96.4" x="1.8" y="26.8"></sliceSourceBounds></sfw></metadata><g><g><path class="processing-icon" d="M96.2,32.3c-0.3-0.3-0.8-0.3-1.2,0L91.4,36v-5.9C91.4,16.3,80.1,5,66.2,5H50.7c-0.3-1.1-1.4-2-2.7-2c-1.5,0-2.8,1.2-2.8,2.8c0,1.5,1.2,2.8,2.8,2.8c1.3,0,2.3-0.8,2.7-2h15.5c13,0,23.6,10.6,23.6,23.5V36l-3.6-3.7c-0.3-0.3-0.8-0.3-1.2,0c-0.3,0.3-0.3,0.8,0,1.2l5,5.1c0,0,0,0,0,0c0.1,0.2,0.4,0.2,0.6,0.2l0,0l0,0c0.1,0,0.1,0,0.2,0c0,0,0.1,0,0.1,0c0,0,0.1,0,0.1-0.1c0,0,0,0,0.1,0c0,0,0,0,0.1-0.1c0,0,0,0,0.1,0c0,0,0,0,0,0l5-5.1C96.5,33.2,96.5,32.7,96.2,32.3z"/><path class="processing-icon" d="M5.8,51c1.5,0,2.8-1.2,2.8-2.8c0-1.3-0.8-2.3-2-2.7V30.2c0-13,10.6-23.5,23.5-23.5h5.7l-3.7,3.6c-0.3,0.3-0.3,0.8,0,1.1c0.2,0.2,0.4,0.2,0.6,0.2c0.2,0,0.4-0.1,0.6-0.2l5.1-5c0,0,0,0,0,0c0,0,0.1-0.1,0.1-0.1c0,0,0,0,0,0c0.1-0.1,0.1-0.2,0.1-0.3c0-0.1,0-0.1,0-0.2c0-0.1,0-0.1,0-0.2c0-0.1-0.1-0.2-0.1-0.3c0,0,0,0,0,0c0,0,0-0.1-0.1-0.1c0,0,0,0,0,0l-5.1-5c-0.3-0.3-0.8-0.3-1.1,0c-0.3,0.3-0.3,0.8,0,1.1L35.9,5h-5.7C16.3,5,5,16.3,5,30.2v15.4c-1.1,0.3-2,1.4-2,2.7C3,49.7,4.3,51,5.8,51z"/><path class="processing-icon" d="M90.6,45.4c-1.5,0-2.8,1.2-2.8,2.8c0,1.3,0.8,2.3,2,2.7v15.4c0,13-10.6,23.5-23.6,23.5h-6l3.7-3.6c0.3-0.3,0.3-0.8,0-1.2s-0.8-0.3-1.2,0l-5.1,5c0,0,0,0,0,0c0,0,0,0.1-0.1,0.1c0,0,0,0,0,0c0,0,0,0,0,0c-0.1,0.1-0.1,0.3-0.1,0.4c0,0.2,0.1,0.4,0.2,0.6l5.1,5c0.2,0.2,0.4,0.2,0.6,0.2c0.2,0,0.4-0.1,0.6-0.2c0.3-0.3,0.3-0.8,0-1.2l-3.7-3.6h6c13.9,0,25.2-11.3,25.2-25.2V50.9c1.1-0.3,2-1.4,2-2.7C93.4,46.7,92.1,45.4,90.6,45.4z"/><path class="processing-icon" d="M48,87.8c-1.3,0-2.3,0.8-2.7,2H30.2c-13,0-23.5-10.6-23.5-23.6v-5.9l3.6,3.7c0.3,0.3,0.8,0.3,1.2,0c0.3-0.3,0.3-0.8,0-1.1l-5-5.1c0,0,0,0,0,0c-0.1-0.1-0.4-0.2-0.6-0.2c-0.2,0-0.3,0.1-0.5,0.1c0,0-0.1,0.1-0.1,0.1l-5,5.1c-0.3,0.3-0.3,0.8,0,1.1c0.2,0.2,0.4,0.2,0.6,0.2c0.2,0,0.4-0.1,0.6-0.2L5,60.4v5.9c0,13.9,11.3,25.2,25.2,25.2h15.2c0.3,1.1,1.4,2,2.7,2c1.5,0,2.8-1.2,2.8-2.8C50.8,89.1,49.6,87.8,48,87.8z"/></g><g><path class="processing-icon" d="M48.5,45.6c-0.2,0-0.4-0.1-0.5-0.2c-4-2.9-8.1-6.1-10.9-9.5c-0.2-0.2-0.2-0.6-0.1-0.9c0.1-0.3,0.4-0.5,0.7-0.5l0,0h21.6c0.3,0,0.6,0.2,0.7,0.5c0.1,0.3,0.1,0.6-0.1,0.9c-2.8,3.4-6.9,6.5-10.9,9.5C48.9,45.5,48.7,45.6,48.5,45.6z"/><path class="processing-icon" d="M61.3,70H35.7c-0.3,0-0.6-0.2-0.7-0.5s-0.1-0.6,0.1-0.9c1.6-2,4.2-3.1,7-4.3c2.1-0.9,4.2-1.8,5.9-3.1c0.2-0.1,0.4-0.2,0.7-0.1c0.1,0,0.2,0.1,0.3,0.2c1.8,1.3,3.9,2.2,5.9,3.1c2.8,1.2,5.4,2.4,7,4.3c0.2,0.2,0.2,0.6,0.1,0.9C61.9,69.9,61.6,70,61.3,70z"/><path class="processing-icon" d="M49.9,48.3c8.3-6.1,16.6-12.5,16.6-21.2c0-0.5-0.4-0.8-0.8-0.8c-0.4,0-0.8,0.4-0.8,0.8c0,8-8.2,14.2-16.3,20.2c-8.1-6-16.3-12.2-16.3-20.2c0-0.5-0.4-0.8-0.8-0.8c-0.5,0-0.8,0.4-0.8,0.8c0,8.7,8.3,15,16.6,21.2c-8.3,6.1-16.6,12.4-16.6,20.9c0,0.4,0.4,0.8,0.8,0.8c0.4,0,0.8-0.4,0.8-0.8c0-7.8,7.9-13.7,16.3-19.9c8.4,6.2,16.3,12.1,16.3,19.9c0,0.4,0.4,0.8,0.8,0.8c0.4,0,0.8-0.4,0.8-0.8C66.5,60.8,58.2,54.4,49.9,48.3z"/><g><path class="processing-icon" d="M69,22.1H27.3c-0.4,0-0.8,0.4-0.8,0.8v1.2c0,0.4,0.4,0.8,0.8,0.8H69c0.4,0,0.8-0.4,0.8-0.8V23C69.8,22.5,69.5,22.1,69,22.1z"/><path class="processing-icon" d="M69,71.5H27.3c-0.4,0-0.8,0.4-0.8,0.8v1.2c0,0.4,0.4,0.8,0.8,0.8H69c0.4,0,0.8-0.4,0.8-0.8v-1.2C69.8,71.8,69.5,71.5,69,71.5z"/></g><g><path class="processing-icon" d="M48.5,51.1c-0.4,0-0.8,0.4-0.8,0.8v0.4c0,0.5,0.4,0.8,0.8,0.8s0.8-0.4,0.8-0.8v-0.4C49.3,51.5,49,51.1,48.5,51.1z"/><path class="processing-icon" d="M48.5,54.3c-0.4,0-0.8,0.4-0.8,0.8v0.6c0,0.4,0.4,0.8,0.8,0.8s0.8-0.4,0.8-0.8v-0.6C49.3,54.7,49,54.3,48.5,54.3z"/><path class="processing-icon" d="M48.5,57.6c-0.4,0-0.8,0.4-0.8,0.8v0.4c0,0.4,0.4,0.8,0.8,0.8s0.8-0.4,0.8-0.8v-0.4C49.3,58,49,57.6,48.5,57.6z"/></g></g></g></svg><div class="lato-bold fs-30 fs-xs-24">ACTION IN PROCESS</div><div class="lato-bold fs-28 fs-xs-20">Please wait until completion</div><div class="padding-top-20 padding-bottom-15 fs-20 fs-xs-18">It seems that the blockchain network is still processing your last action. Once completed, your contract page will be updated.</div><div class="btn-container padding-bottom-40"><a href="javascript:void(0);" class="white-blue-green-btn min-width-200 close-popup">GOT IT</a></div></div>', '', null, true);
}

$(document).on('click', '.attention-in-process', function () {
    showContractAttentionInProcess();
});

function loadDeferImages() {
    for(var i = 0, len = jQuery('img[data-defer-src]').length; i < len; i+=1) {
        if(basic.isInViewport(jQuery('img[data-defer-src]').eq(i)) && jQuery('img[data-defer-src]').eq(i).attr('src') == undefined) {
            jQuery('img[data-defer-src]').eq(i).attr('src', jQuery('img[data-defer-src]').eq(i).attr('data-defer-src'));
        }
    }
}
loadDeferImages();

if (typeof(dcnCookie) != 'undefined') {
    dcnCookie.init({
        'google_app_id' : 'UA-108398439-4',
        'fb_app_id' : '2366034370318681'
    });
}