<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/

Route::get('/refresh-captcha', 'Controller@refreshCaptcha')->name('refresh-captcha');

Route::group(['prefix' => '/', 'middleware' => 'frontEndMiddleware'], function () {
    Route::get('sitemap.xml', 'Controller@getSitemap')->name('sitemap');

    //======================================= PAGES ========================================
    Route::get('/', 'HomeController@getView')->name('home');

    Route::get('/support-guide', 'SupportGuideController@getView')->name('support-guide');

    Route::get('/assurance-demo', 'AssuranceDemoController@getView')->name('assurance-demo');

    Route::get('/contract-proposal/{slug}', 'PatientController@getContractProposal')->name('contract-proposal');

    Route::get('/forgotten-password', 'UserController@getForgottenPasswordView')->name('forgotten-password');

    Route::get('/password-recover', 'UserController@getRecoverPassword')->name('password-recover');

    Route::get('/my-contracts', 'UserController@getMyContractsView')->middleware('HandleUserSession')->name('my-contracts');

    //======================================= AJAX ========================================
    Route::post('/save-public-key', 'WalletInstructionsController@savePublicKey')->name('save-public-key');

    Route::post('/password-recover-submit', 'UserController@changePasswordSubmit')->name('password-recover-submit');

    Route::post('/get-calculator-html', 'HomeController@getCalculatorHtml')->name('get-calculator-html');

    Route::post('/get-calculator-result', 'HomeController@getCalculatorResult')->name('get-calculator-result');

    Route::post('/get-all-clinics', 'Controller@getAllClinicsResponse')->name('get-all-clinics');

    Route::post('/check-email', 'UserController@checkEmail')->name('check-email');

    Route::post('/check-captcha', 'UserController@checkCaptcha')->name('check-captcha');

    Route::post('/forgotten-password-submit', 'UserController@forgottenPasswordSubmit')->name('forgotten-password-submit');

    //======================================= /AJAX ========================================

    Route::group(['prefix' => 'patient', 'middleware' => 'HandlePatientSession'], function () {
        Route::get('/', 'PatientController@getPatientAccess')->name('patient-access');

        Route::post('/authenticate', 'PatientController@authenticate')->name('authenticate-patient');

        Route::post('/get-invite-dentists-popup', 'PatientController@getInviteDentistsPopup')->name('get-invite-dentists-popup');

        Route::post('/get-contact-clinic-popup', 'PatientController@getContactClinicPopup')->name('get-contact-clinic-popup');

        Route::post('/submit-contact-clinic', 'PatientController@submitContactClinic')->name('submit-contact-clinic');

        Route::get('/invite-dentists', 'PatientController@getInviteDentistsView')->name('invite-dentists');

        Route::post('/submit-invite-dentists', 'PatientController@inviteDentists')->middleware('HandleUserSession')->name('submit-invite-dentists');

        Route::post('/update-and-sign-contract', 'PatientController@updateAndSignContract')->middleware('HandleUserSession')->name('update-and-sign-contract');

        Route::post('/get-reconsider-monthly-premium', 'PatientController@getReconsiderMonthlyPremium')->middleware('HandleUserSession')->name('get-reconsider-monthly-premium');

        Route::post('/submit-reconsider-monthly-premium', 'PatientController@submitReconsiderMonthlyPremium')->middleware('HandleUserSession')->name('submit-reconsider-monthly-premium');

        Route::get('/contract/{slug}', 'PatientController@getPatientContractView')->name('patient-contract-view');

        Route::post('/on-blockchain-contract-creation', 'PatientController@onBlockchainContractCreation')->name('on-blockchain-contract-creation');

        Route::post('/create-check-up', 'PatientController@createCheckUp')->name('create-check-up');
    });

    Route::group(['prefix' => 'dentist', 'middleware' => 'HandleDentistSession'], function () {
        //Route::get('/', 'PatientController@getPatientAccess')->name('patient-access');

        Route::get('/create-contract', 'DentistController@getCreateContractView')->name('create-contract');

        Route::post('/store-and-submit-temporally-contract', 'DentistController@storeAndSubmitTemporallyContract')->middleware('HandleUserSession')->name('store-and-submit-temporally-contract');

        Route::get('/contract/{slug}', 'DentistController@getDentistContractView')->name('dentist-contract-view');

        Route::post('/on-blockchain-contract-approval', 'DentistController@onBlockchainContractApproval')->name('on-blockchain-contract-approval');

        Route::post('/notify-patient-for-successful-withdraw', 'DentistController@notifyPatientForSuccessfulWithdraw')->name('notify-patient-for-successful-withdraw');

        Route::post('/approve-check-up', 'DentistController@approveCheckUp')->name('approve-check-up');
    });

    Route::get('/my-contracts-iframe', 'UserController@getMyContractsTemplate')->middleware('HandleUserSession')->name('my-contracts-iframe');

    Route::post('/filter-my-contracts', 'UserController@filterMyContracts')->middleware('HandleUserSession')->name('filter-my-contracts');

    Route::post('/get-popup-cancel-contract', 'UserController@getPopupCancelContract')->middleware('HandleUserSession')->name('get-popup-cancel-contract');

    Route::post('/get-address-validation-or-remember-me', 'UserController@getAddressValidationOrRememberMe')->middleware('HandleUserSession')->name('get-address-validation-or-remember-me');

    Route::post('/get-keystore-file-password-validation', 'UserController@getKeystoreFilePasswordValidation')->middleware('HandleUserSession')->name('get-keystore-file-password-validation');

    Route::post('/update-account', 'UserController@updateAccount')->middleware('HandleUserSession')->name('update-account');

    Route::post('/decrypt-contract', 'UserController@readAndDecryptIPFSZip')->middleware('HandleUserSession')->name('decrypt-contract');

    Route::post('/render-pdf', 'UserController@renderPdf')->middleware('HandleUserSession')->name('render-pdf');

    Route::post('/update-contract-status', 'UserController@updateContractStatus')->middleware('HandleUserSession')->name('update-contract-status');

    Route::post('/update-public-keys', 'UserController@updatePublicKeys')->middleware('HandleUserSession')->name('update-public-keys');

    Route::post('/check-public-key', 'UserController@checkPublicKey')->middleware('HandleUserSession')->name('check-public-key');

    Route::get('/user-logout', 'UserController@userLogout')->name('user-logout');

    Route::get('/get-current-user-data', 'UserController@getCurrentUserData')->middleware('HandleUserSession')->name('get-current-user-data');

    Route::post('/get-recipe-popup', 'UserController@getRecipePopup')->middleware('HandleUserSession')->name('get-recipe-popup');

    Route::post('/dentist-register', 'DentistController@register')->name('dentist-register');

    Route::post('/dentist-login', 'DentistController@login')->name('dentist-login');

    Route::get('/ipfs-hashes', 'Controller@getIpfsHashes')->name('ipfs-hashes');

    Route::post('/enrich-profile', 'DentistController@enrichProfile')->name('enrich-profile');

    Route::post('/invite-your-clinic', 'DentistController@inviteYourClinic')->name('invite-your-clinic');

    Route::post('/check-dentist-account', 'DentistController@checkDentistAccount')->name('check-dentist-account');

    Route::get('/custom-cookie', 'UserController@manageCustomCookie')->name('custom-cookie');

    Route::get('/info/{slug}', 'Controller@handleApiEndpoints')->name('api-endpoints');
});