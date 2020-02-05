<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', function (Request $request) {
        return $request->user()->load('workspace');
    });

    Route::patch('/notifications/{id}', function (Request $request, string $id) {
        $request->user()->notifications()->find($id)->update(['read_at' => now()]);

        return response(null, 204);
    });

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});

Route::group(['middleware' => 'api'], function () {
    Route::fallback(function() {
        return response()->json(['message' => 'Not Found!'], 404);
    })->name('notFound');

    Route::resource('products', 'ProductController');

    Route::patch('ideas/{idea}/vote', 'IdeaController@vote');
    Route::resource('ideas', 'IdeaController');

    Route::resource('ideas/{idea_id}/comments', 'CommentController');

    Route::get('categories', function () {
        $categories = \App\Category::all();
        return response()->json($categories, 200);
    });
});
