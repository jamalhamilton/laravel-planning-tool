<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	if (Auth::check()) {
		return redirect()->route('home');
	}else{
    	return view('auth.login');
	}
});

//Auth::routes();

// Credential routing
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');


// Page routing
/*
Route::get('/planning', [
	'middleware' => 'auth',
	'uses' => 'PlanningController@index'
])->name('home');
*/

Route::get('/planning', 'PlanningController@index')->name('home');
Route::get('/planning/overview', 'PlanningController@overview');
Route::get('/planning/params', 'PlanningController@params');
Route::post('/planning/params/update', 'PlanningController@updateParams')->name('updateparams');
Route::post('/planning/params/delete', 'PlanningController@deleteParams')->name('deleteparams');
Route::post('/planning/params/delete_groupitem', 'PlanningController@deleteGroupItem')->name('deletegroupitem');

Route::post('/planning/savecomments', 'PlanningController@SaveComments')->name('savecomments');
Route::post('/planning/campaignExport', 'PlanningController@campaignExport')->name('campaignExport');
Route::get('/download/pdf/{file}', 'PlanningController@download')->name('pdfdownload');

Route::post('/planning/add','PlanningController@add')->name('addcampaign');
Route::post('/planning/edit','PlanningController@edit')->name('editcampaign');
/*Route::post('/planning/service','PlanningController@service')->name('addservicegroup');*/
Route::post('/planning/get','PlanningController@getCampaign')->name('getcampaign');
Route::post('/planning/channel/get','PlanningController@getChannel')->name('getchannel');
Route::post('/planning/option/update','PlanningController@changeOption')->name('changeoption');
Route::post('/planning/channel/update','PlanningController@channelBtnClick')->name('channelbtnClick');
Route::get('/planning/list','PlanningController@serverProcessing')->name('serverprocessing');

Route::get('/customer', 'CustomerController@index');
Route::get('/customer/overview', 'CustomerController@overview')->name('customeroverview');
Route::post('/customer/contact/add','CustomerController@addContact')->name('addcontact');
Route::post('/customer/contact/edit','CustomerController@editContact')->name('editcontact');
Route::post('/customer/cost/update','CustomerController@updateCost')->name('updatecost');

Route::get('/media', 'MediaController@index');
Route::any('/media/fill', 'MediaController@fill');
Route::post('/media/completed', 'MediaController@completed');
Route::post('/media/autocomplete', 'MediaController@autocomplete');
Route::post('/media/category/list','MediaController@getCategories');
Route::post('/media/note/insert','MediaController@insertNote');
Route::post('/media/note/delete','MediaController@deleteNote');
Route::post('/media/ctnote/insert','MediaController@insertCtNote');
Route::post('/media/ctnote/delete','MediaController@deleteCtNote');
Route::post('/media/line/delete','MediaController@deleteLine');
Route::post('/media/line/insert','MediaController@insertLine');
Route::post('/media/reorder','MediaController@saveMediaOrder');
Route::post('/media/col/edit','MediaController@editCol');
Route::post('/media/category/add','MediaController@addCategory');
Route::post('/media/category/edit','MediaController@editCategory');
Route::post('/media/category/delete','MediaController@deleteCategory');
Route::post('/media/category/duplicate','MediaController@duplicateCategory');

Route::get('/advertising', 'AdvertisingController@index');
Route::post('/advertising/edit', 'AdvertisingController@edit');

Route::get('/users', 'UserController@index');
Route::get('/users/list', 'UserController@tableDisplay');
Route::post('/users/add', 'UserController@addUser');
Route::get('/users/delete', 'UserController@deleteUser');
Route::get('/users/show', 'UserController@displayInModal');
Route::post('/users/edit', 'UserController@editUser');

// API routing

//Route::get('/api/v1/planning/list', 'Api\PlanningApiController@list');
//Route::get('/api/v1/planning/create');
//Route::get('/api/v1/planning/update');
//Route::get('/api/v1/planning/delete');

Route::get('/api/v1/clients', 'Api\ApiController@clients');
Route::get('/api/v1/users', 'Api\ApiController@users');
Route::get('/api/v1/campaigns', 'Api\ApiController@campaigns');
Route::get('/api/v1/params', 'Api\ApiController@params');
Route::get('/api/v1/medias', 'Api\ApiController@medias');

Route::get('generatePdf','PdfController@generatePdf');
