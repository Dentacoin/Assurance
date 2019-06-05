<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'admin' middleware group. Now create something great!
|
*/

Route::get('/refresh-captcha', 'Controller@refreshCaptcha')->name('refresh-captcha');

Route::group(['prefix' => 'assurance-admin-access', 'middleware' => 'HandleAdminSession'], function () {
    Route::get('/', 'Admin\MainController@getAdminAccess')->name('admin-access');

    Route::get('/logout', 'Admin\MainController@logout')->name('logout');

    Route::post('/authenticate-admin', 'Admin\MainController@authenticateAdmin')->name('authenticate-admin');

    Route::get('/guide', 'Admin\MainController@getGuideView')->name('guide');

    Route::group(['prefix' => 'media'], function()  {
        Route::get('/', 'Admin\MediaController@getView')->name('media');

        Route::post('/open', 'Admin\MediaController@openMedia');

        Route::post('/delete/{id}', 'Admin\MediaController@deleteMedia')->name('delete-media');

        /*Route::post('/upload', 'Admin\MediaController@uploadMedia')->name('upload-media');*/

        Route::post('/update-media-alts', 'Admin\MediaController@updateAlts')->name('update-media-alts');

        Route::post('/ajax-upload', 'Admin\MediaController@ajaxUpload')->name('ajax-upload');
    });

    Route::group(['prefix' => 'pages'], function()  {
        Route::get('/', 'Admin\PagesDataController@getView')->name('all-pages');

        Route::any('/edit/{id}', 'Admin\PagesDataController@editPage')->name('edit-page');
    });

    Route::group(['prefix' => 'menus'], function()  {
        Route::get('/', 'Admin\MenuController@getView')->name('all-menus');

        Route::any('/edit/{id}', 'Admin\MenuController@editMenu')->name('edit-menu');

        Route::any('/edit-menu-element/{id}', 'Admin\MenuController@addEditMenuElement')->name('edit-menu-element');

        Route::get('/delete-menu-element/{id}', 'Admin\MenuController@deleteMenuElement')->name('delete-menu-element');

        Route::any('/add-menu-element/', 'Admin\MenuController@addEditMenuElement')->name('add-menu-element');

        Route::any('/change-url-option', 'Admin\MenuController@changeUrlOption')->name('change-url-option');

        Route::post('/update-order', function(\Illuminate\Http\Request $request){
            (new \App\Http\Controllers\Admin\MainController())->updatePostsOrder('menu_elements', $request);
        });
    });

    Route::group(['prefix' => 'calculator-parameters'], function()  {
        Route::get('/', 'Admin\CalculatorParametersController@getView')->name('calculator-parameters');

        Route::any('/update', 'Admin\CalculatorParametersController@updateCalculatorParameters')->name('update-calculator-parameters');

        Route::any('/add/', 'Admin\CalculatorParametersController@addCalculatorParameter')->name('add-calculator-parameter');

        Route::get('/delete/{id}', 'Admin\CalculatorParametersController@deleteCalculatorParameter')->name('delete-calculator-parameter');
    });

    Route::group(['prefix' => 'support-guide'], function()  {
        Route::get('/', 'Admin\SupportGuideController@getView')->name('all-support-guides');

        Route::any('/add', 'Admin\SupportGuideController@addEditSupportGuide')->name('add-support-guide');

        Route::get('/delete/{id}', 'Admin\SupportGuideController@deleteSupportGuide')->name('delete-support-guide');

        Route::any('/edit/{id}', 'Admin\SupportGuideController@addEditSupportGuide')->name('edit-support-guide');

        Route::post('/update-order', function(\Illuminate\Http\Request $request){
            (new \App\Http\Controllers\Admin\MainController())->updatePostsOrder('support_guides', $request);
        });
    });
});