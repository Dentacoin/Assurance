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

    Route::get('/video-demo', 'AssuranceDemoController@getView')->name('video-demo');

    Route::get('/contract-proposal/{slug}', 'PatientController@getContractProposal')->name('contract-proposal');

    /*Route::get('/my-contracts', 'UserController@getMyContractsView')->middleware('HandleUserSession')->name('my-contracts');*/

    //======================================= AJAX ========================================
    Route::post('/save-public-key', 'WalletInstructionsController@savePublicKey')->name('save-public-key');

    Route::post('/password-recover-submit', 'UserController@changePasswordSubmit')->name('password-recover-submit');

    Route::post('/get-calculator-html', 'HomeController@getCalculatorHtml')->name('get-calculator-html');

    Route::post('/get-calculator-result', 'HomeController@getCalculatorResult')->name('get-calculator-result');

    Route::post('/get-all-clinics', 'Controller@getAllClinicsResponse')->name('get-all-clinics');

    Route::post('/check-captcha', 'UserController@checkCaptcha')->name('check-captcha');

    Route::post('/forgotten-password-submit', 'UserController@forgottenPasswordSubmit')->name('forgotten-password-submit');

    Route::post('/cancel-contracts', 'UserController@cancelContracts')->name('cancel-contracts');

    Route::post('/check-contract-status', 'UserController@checkContractStatus')->middleware('HandleUserSession')->name('check-contract-status');

    Route::post('/check-contract-signing', 'UserController@checkContractSigning')->middleware('HandleUserSession')->name('check-contract-signing');

    //======================================= /AJAX ========================================

    Route::group(['prefix' => 'patient', 'middleware' => 'HandlePatientSession'], function () {
        Route::get('/', 'PatientController@getPatientAccess')->name('patient-access');

        Route::post('/get-invite-dentists-popup', 'PatientController@getInviteDentistsPopup')->name('get-invite-dentists-popup');

        Route::post('/get-contact-clinic-popup', 'PatientController@getContactClinicPopup')->name('get-contact-clinic-popup');

        Route::post('/submit-contact-clinic', 'PatientController@submitContactClinic')->name('submit-contact-clinic');

        Route::post('/submit-invite-dentists', 'PatientController@inviteDentists')->middleware('HandleUserSession')->name('submit-invite-dentists');

        Route::post('/update-and-sign-contract', 'PatientController@updateAndSignContract')->middleware('HandleUserSession')->name('update-and-sign-contract');

        Route::post('/get-reconsider-monthly-premium', 'PatientController@getReconsiderMonthlyPremium')->middleware('HandleUserSession')->name('get-reconsider-monthly-premium');

        Route::post('/submit-reconsider-monthly-premium', 'PatientController@submitReconsiderMonthlyPremium')->middleware('HandleUserSession')->name('submit-reconsider-monthly-premium');

        Route::get('/contract/{slug}', 'PatientController@getPatientContractView')->name('patient-contract-view');

        Route::post('/record-check-up-or-teeth-cleaning', 'PatientController@recordCheckUpOrTeethCleaning')->name('record-check-up-or-teeth-cleaning');

        Route::post('/check-for-incoming-pending-contracts', 'PatientController@checkForIncomingPendingContracts')->name('check-for-incoming-pending-contracts');

        Route::post('/check-contracts-count', 'PatientController@checkContractsCount')->name('check-contracts-count');
    });

    Route::group(['prefix' => 'dentist', 'middleware' => 'HandleDentistSession'], function () {
        Route::get('/create-contract', 'DentistController@getCreateContractView')->name('create-contract');

        Route::post('/store-and-submit-temporally-contract', 'DentistController@storeAndSubmitTemporallyContract')->middleware('HandleUserSession')->name('store-and-submit-temporally-contract');

        Route::get('/contract/{slug}', 'DentistController@getDentistContractView')->name('dentist-contract-view');

        Route::post('/check-for-pending-contract-records', 'DentistController@checkForPendingContractRecords')->name('check-for-pending-contract-records');

        Route::post('/take-action-for-pending-contract-records', 'DentistController@takeActionForPendingContractRecords')->name('take-action-for-pending-contract-records');
    });

    Route::post('/save-transaction', 'UserController@saveTransaction')->middleware('HandleUserSession')->name('save-transaction');

    Route::post('/save-address', 'UserController@saveAddress')->middleware('HandleUserSession')->name('save-address');

    Route::post('/delete-address', 'UserController@deleteAddress')->middleware('HandleUserSession')->name('delete-address');

    Route::post('/get-contract-data', 'UserController@getContractData')->middleware('HandleUserSession')->name('get-contract-data');

    Route::post('/get-scanning-data', 'UserController@getScanningData')->name('get-scanning-data');

    Route::post('/mark-contract-as-processing', 'UserController@markContractAsProcessing')->name('mark-contract-as-processing');

    Route::post('/request-contract-status-change', 'UserController@requestContractStatusChange')->name('request-contract-status-change');

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

    Route::post('/check-email-and-return-data', 'UserController@checkEmailAndReturnData')->middleware('HandleUserSession')->name('check-email-and-return-data');

    Route::get('/user-logout', 'UserController@userLogout')->name('user-logout');

    Route::post('/get-recipe-popup', 'UserController@getRecipePopup')->middleware('HandleUserSession')->name('get-recipe-popup');

    Route::post('/authenticate-user', 'UserController@authenticateUser')->name('authenticate-user');

    Route::get('/ipfs-hashes', 'Controller@getIpfsHashes')->name('ipfs-hashes');

    Route::post('/invite-your-clinic', 'DentistController@inviteYourClinic')->name('invite-your-clinic');

    Route::get('/custom-cookie', 'UserController@manageCustomCookie')->name('custom-cookie');

    Route::any('/info/{slug}', 'UserController@handleApiEndpoints')->name('api-endpoints');
});