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

    return view('site.index');
});

Route::get('/cardapio', function () {
    return view('site.cardapio');
});
  Route::get('/cardapio_qrcode', function () {
      return view('site.cardapio_316');
  });

Route::get('/cardapio_neroyne', function () {
    return view('site.cardapio_neroyne_tx');
});



Route::get('changeLacale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'pt', 'es', 'ru', 'ch','kr'])) {
        App::setLocale($locale);

        \Session::put('locale', $locale);
            \Session::put('locale_first_time', $locale);
    }

    return redirect()->back();
});
Route::post('/form-help', 'Shop\HelpController@email');