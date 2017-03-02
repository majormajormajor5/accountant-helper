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
Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('test/test', function () {
    return view('test-layout');
});

Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, config('app.locales'))) {         # Проверяем, что у пользователя выбран доступный язык
        Session::put('locale', $locale);                    # И устанавливаем его в сессии под именем locale
    }

    return redirect()->back();                              # Редиректим его на ту же страницу
});

Route::get('home', 'HomeController@index');

Route::get('logout', function () {
    Auth::logout();

    return redirect('/');
});

Route::resource('organizations', 'Organizations\OrganizationsController');

Route::resource('buildings', 'BuildingsController');

Route::get('buildings/{id}/apartments', 'BuildingsController@apartments');
Route::get('apartments/building/{buildingId}/create', 'ApartmentsController@create');
Route::post('apartments/building/{buildingId}', 'ApartmentsController@store');
Route::post('apartments/{apartmentId}/update', 'ApartmentsController@update');
Route::delete('apartments/{apartmentId}', 'ApartmentsController@destroy');

Route::get('owners/apartment/{apartmentId}', 'OwnersController@byApartment');
Route::post('owners/{ownerId}/update', 'OwnersController@update');
Route::delete('owners/{ownerId}', 'OwnersController@destroy');
Route::post('owners', 'OwnersController@store');

//Months
Route::get('months/building/{buildingId}', 'MonthsController@byBuilding');
Route::patch('months/{monthsId}', 'MonthsController@update');

//Taxes
Route::get('taxes/month/{monthId}/edit', 'TaxesController@edit');