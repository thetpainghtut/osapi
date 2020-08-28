<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

  Route::apiresource('brands','Api\BrandController');

  Route::apiresource('subcategories','Api\SubcategoryController');

  Route::apiresource('items','Api\ItemController');

  Route::post('register','Api\AuthController@register')->name('register');

  Route::get('filter_item','Api\ItemController@filter')->name('filter_item');

  Route::get('itemByBrand','Api\ItemController@byBrand')->name('itemByBrand');

  Route::get('itemBySubcategory','Api\ItemController@bySubcategory')->name('itemBySubcategory');

});