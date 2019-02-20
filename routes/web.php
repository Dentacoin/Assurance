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

    Route::get('/wallet-instructions', 'WalletInstructionsController@getView')->name('wallet-instructions');

    Route::get('/test', function() {
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

    Route::post('/get-login-signin', 'Controller@getLoginSigninHtml')->name('get-login-signin');

    Route::post('/get-all-clinics', 'Controller@getAllClinicsResponse')->name('get-all-clinics');
    //======================================= /AJAX ========================================

    Route::group(['prefix' => 'patient', 'middleware' => 'HandlePatientSession'], function () {
        Route::get('/', 'PatientController@getPatientAccess')->name('patient-access');

        Route::post('/authenticate', 'PatientController@authenticate')->name('authenticate-patient');

        Route::post('/get-invite-dentists-popup', 'PatientController@getInviteDentistsPopup')->name('get-invite-dentists-popup');

        Route::get('/invite-dentists', 'PatientController@getInviteDentistsView')->name('invite-dentists');

        Route::post('/submit-invite-dentists', 'PatientController@inviteDentists')->middleware('HandleUserSession')->name('submit-invite-dentists');

        Route::post('/update-and-sign-contract', 'PatientController@updateAndSignContract')->middleware('HandleUserSession')->name('update-and-sign-contract');

        Route::post('/get-reconsider-monthly-premium', 'PatientController@getReconsiderMonthlyPremium')->middleware('HandleUserSession')->name('get-reconsider-monthly-premium');

        Route::post('/submit-reconsider-monthly-premium', 'PatientController@submitReconsiderMonthlyPremium')->middleware('HandleUserSession')->name('submit-reconsider-monthly-premium');

        Route::get('/congratulations/{slug}', 'PatientController@getCongratulationsView')->name('congratulations');

        Route::get('/contract/{slug}', 'PatientController@getPatientContractView')->name('patient-contract-view');
    });

    Route::group(['prefix' => 'dentist', 'middleware' => 'HandleDentistSession'], function () {
        //Route::get('/', 'PatientController@getPatientAccess')->name('patient-access');

        Route::get('/create-contract', 'DentistController@getCreateContractView')->name('create-contract');

        Route::post('/store-and-submit-temporally-contract', 'DentistController@storeAndSubmitTemporallyContract')->middleware('HandleUserSession')->name('store-and-submit-temporally-contract');
    });

    Route::get('/my-profile', 'UserController@getMyProfileView')->middleware('HandleUserSession')->name('my-profile');

    Route::get('/edit-account', 'UserController@getEditAccountView')->middleware('HandleUserSession')->name('edit-account');

    Route::get('/manage-privacy', 'UserController@getManagePrivacyView')->middleware('HandleUserSession')->name('manage-privacy');

    Route::get('/my-contracts', 'UserController@getMyContractsView')->middleware('HandleUserSession')->name('my-contracts');

    Route::post('/update-account', 'UserController@updateAccount')->middleware('HandleUserSession')->name('update-account');

    Route::post('/update-contract-status', 'UserController@updateContractStatus')->middleware('HandleUserSession')->name('update-contract-status');

    Route::post('/update-public-keys', 'UserController@updatePublicKeys')->middleware('HandleUserSession')->name('update-public-keys');

    Route::post('/check-public-key', 'UserController@checkPublicKey')->middleware('HandleUserSession')->name('check-public-key');

    Route::post('/add-dcn-address', 'UserController@addDcnAddress')->middleware('HandleUserSession')->name('add-dcn-address');

    Route::get('/user-logout', 'UserController@userLogout')->name('user-logout');

    Route::get('/forgotten-password', 'UserController@getForgottenPasswordView')->name('forgotten-password');

    Route::post('/forgotten-password-submit', 'UserController@forgottenPasswordSubmit')->name('forgotten-password-submit');

    Route::post('/dentist-register', 'DentistController@register')->name('dentist-register');

    Route::post('/dentist-login', 'DentistController@login')->name('dentist-login');

    Route::get('/ipfs-hashes', 'Controller@getIpfsHashes')->name('ipfs-hashes');
});