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

    //======================================= PAGES ========================================
    Route::get('/', 'HomeController@getView')->name('home');

    Route::get('/support-guide', 'SupportGuideController@getView')->name('support-guide');

    Route::get('/contract-proposal/{slug}', 'PatientController@getContractProposal')->name('contract-proposal');

    Route::post('/password-recover', 'UserController@getRecoverPassword')->name('password-recover');

    Route::post('/password-recover-submit', 'UserController@changePasswordSubmit')->name('password-recover-submit');

    Route::get('/wallet-instructions', 'WalletInstructionsController@getView')->name('wallet-instructions');

    Route::get('/test', function() {
        var_dump((new \App\Http\Controllers\APIRequestsController())->getGDPRDownloadLink());
        die();
        //var_dump((new \App\Http\Controllers\PatientController())->renderTestPdf());
        //var_dump((new \App\Http\Controllers\Controller())->fillCountriesFromCsv());
        //var_dump((new \App\Http\Controllers\Controller())->testZipCreation());
        //var_dump((new \App\Http\Controllers\APIRequestsController())->getAllEnums());
        //var_dump((new \App\Http\Controllers\APIRequestsController())->getPatientsByEmail('miroslav.nedelchev@dentacoin.com'));
        die();
    })->name('test');

    Route::get('/test123', 'Controller@testingTest')->name('test123');

    //======================================= TEMPORALLY FOR DAPP TESTING ========================================
    Route::get('/dentist-test', 'HomeController@getDentistView')->name('dentist-test');
    Route::get('/patient-test', 'HomeController@getPatientView')->name('patient-test');
    //======================================= /TEMPORALLY FOR DAPP TESTING ========================================

    //======================================= AJAX ========================================
    Route::post('/get-calculator-html', 'HomeController@getCalculatorHtml')->name('get-calculator-html');

    Route::post('/get-calculator-result', 'HomeController@getCalculatorResult')->name('get-calculator-result');

    Route::post('/get-login-signin', 'UserController@getLoginSigninHtml')->name('get-login-signin');

    Route::post('/get-all-clinics', 'Controller@getAllClinicsResponse')->name('get-all-clinics');

    Route::post('/check-email', 'UserController@checkEmail')->name('check-email');

    Route::post('/check-captcha', 'UserController@checkCaptcha')->name('check-captcha');

    Route::post('/download-gdpr-data', 'UserController@downloadGDPRData')->name('download-gdpr-data');
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

        Route::get('/congratulations/{slug}', 'PatientController@getCongratulationsView')->name('congratulations');

        Route::get('/contract/{slug}', 'PatientController@getPatientContractView')->name('patient-contract-view');

        Route::post('/on-blockchain-contract-creation', 'PatientController@onBlockchainContractCreation')->name('on-blockchain-contract-creation');
    });

    Route::group(['prefix' => 'dentist', 'middleware' => 'HandleDentistSession'], function () {
        //Route::get('/', 'PatientController@getPatientAccess')->name('patient-access');

        Route::get('/create-contract', 'DentistController@getCreateContractView')->name('create-contract');

        Route::post('/store-and-submit-temporally-contract', 'DentistController@storeAndSubmitTemporallyContract')->middleware('HandleUserSession')->name('store-and-submit-temporally-contract');

        Route::get('/contract/{slug}', 'DentistController@getDentistContractView')->name('dentist-contract-view');

        Route::post('/on-blockchain-contract-approval', 'DentistController@onBlockchainContractApproval')->name('on-blockchain-contract-approval');
    });

    Route::get('/my-profile', 'UserController@getMyProfileView')->middleware('HandleUserSession')->name('my-profile');

    Route::get('/edit-account', 'UserController@getEditAccountView')->middleware('HandleUserSession')->name('edit-account');

    Route::get('/manage-privacy', 'UserController@getManagePrivacyView')->middleware('HandleUserSession')->name('manage-privacy');

    Route::get('/my-contracts', 'UserController@getMyContractsView')->middleware('HandleUserSession')->name('my-contracts');

    Route::post('/filter-my-contracts', 'UserController@filterMyContracts')->middleware('HandleUserSession')->name('filter-my-contracts');

    Route::post('/get-popup-cancel-contract', 'UserController@getPopupCancelContract')->middleware('HandleUserSession')->name('get-popup-cancel-contract');

    Route::post('/validate-civic-kyc', 'UserController@validateCivicKyc')->middleware('HandleUserSession')->name('validate-civic-kyc');

    Route::post('/delete-my-profile', 'UserController@deleteMyProfile')->middleware('HandleUserSession')->name('delete-my-profile');

    Route::post('/get-address-validation-or-remember-me', 'UserController@getAddressValidationOrRememberMe')->middleware('HandleUserSession')->name('get-address-validation-or-remember-me');

    Route::post('/get-keystore-file-password-validation', 'UserController@getKeystoreFilePasswordValidation')->middleware('HandleUserSession')->name('get-keystore-file-password-validation');

    Route::post('/update-account', 'UserController@updateAccount')->middleware('HandleUserSession')->name('update-account');

    Route::post('/decrypt-contract', 'UserController@readAndDecryptIPFSZip')->middleware('HandleUserSession')->name('decrypt-contract');

    Route::post('/render-pdf', 'UserController@renderPdf')->middleware('HandleUserSession')->name('render-pdf');

    Route::post('/update-contract-status', 'UserController@updateContractStatus')->middleware('HandleUserSession')->name('update-contract-status');

    Route::post('/update-public-keys', 'UserController@updatePublicKeys')->middleware('HandleUserSession')->name('update-public-keys');

    Route::post('/check-public-key', 'UserController@checkPublicKey')->middleware('HandleUserSession')->name('check-public-key');

    Route::post('/add-dcn-address', 'UserController@addDcnAddress')->middleware('HandleUserSession')->name('add-dcn-address');

    Route::get('/user-logout', 'UserController@userLogout')->name('user-logout');

    Route::get('/get-current-user-data', 'UserController@getCurrentUserData')->middleware('HandleUserSession')->name('get-current-user-data');

    Route::post('/get-recipe-popup', 'UserController@getRecipePopup')->middleware('HandleUserSession')->name('get-recipe-popup');

    Route::post('/withdraw', 'UserController@withdraw')->middleware('HandleUserSession')->name('withdraw');

    Route::get('/forgotten-password', 'UserController@getForgottenPasswordView')->name('forgotten-password');

    Route::post('/forgotten-password-submit', 'UserController@forgottenPasswordSubmit')->name('forgotten-password-submit');

    Route::post('/dentist-register', 'DentistController@register')->name('dentist-register');

    Route::post('/dentist-login', 'DentistController@login')->name('dentist-login');

    Route::get('/ipfs-hashes', 'Controller@getIpfsHashes')->name('ipfs-hashes');
});