<?php


Route::get('/panel/{any}', function () {
    return view('app');
})->where('any', '.*');  // для 404

Route::get('/todo','TodoController@index');
Route::post('/todo/add','TodoController@store');
Route::put('/todo/update/{id}','TodoController@update');
Route::delete('/todo/delete/{id}','TodoController@destroy');

Route::get('/events','EventController@index');
Route::post('/event/add','EventController@store');
Route::put('/event/update/{id}','EventController@update');
Route::delete('/event/delete/{id}','EventController@destroy');

Route::get('/links','LinkController@index');
Route::post('/link/add','LinkController@store');
Route::put('/link/update/{id}','LinkController@update');
Route::delete('/link/delete/{id}','LinkController@destroy');
Route::get('/links/get-by-tag','LinkController@getByTag');
Route::get('/links/get-by-cat','LinkController@getByCat');
Route::get('/links/get-links-like','LinkController@getLinksLike');

Route::get('/terms','TermController@index');
Route::post('/term/add','TermController@store');
Route::put('/term/update/{id}','TermController@update');
Route::put('/term/update-order','TermController@updateOrder');
Route::delete('/term/delete/{id}','TermController@destroy');

Route::get('/categories','CategoryController@index');
Route::get('/category/get-by-id/{id}','CategoryController@getById');
Route::post('/category/add','CategoryController@store');
Route::put('/category/update/{id}','CategoryController@update');
Route::delete('/category/delete/{id}','CategoryController@destroy');

Route::post('/tag/add','TagController@store');
Route::delete('/tag/delete/{id}','TagController@destroy');
Route::get('/tag/get-recommended-color','TagController@getRecomendedColor'); 

Route::get('/payments/years','PaymentController@getYears');
Route::get('/payments/months-data','PaymentController@getMonthsData');
Route::get('/payments/by-year','PaymentController@getPaymentsByYear');
Route::post('/payment/add','PaymentController@store');
Route::put('/payment/update/{id}','PaymentController@update');
Route::delete('/payment/delete/{id}','PaymentController@destroy');


