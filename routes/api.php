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

// authenticated routes
Route::group(['middleware' => 'auth:api'], function () {
    // logout
    Route::post('logout', 'Auth\LoginController@logout');

    // get user space?
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

// guest routes
Route::group(['middleware' => 'guest:api'], function () {
    // login
    Route::post('login', 'Auth\LoginController@login');

    // register
    Route::post('register', 'Auth\RegisterController@register');

    // send password reset link
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

    // reset password
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});

// guest /api/ routes
Route::group(['middleware' => 'api'], function () {
    // ?
    Route::fallback(function() {
        return response()->json(['message' => 'Not Found!'], 404);
    })->name('notFound');

    // products resource controller
    Route::resource('products', 'ProductController');

    // vote on idea
    Route::patch('ideas/{idea}/vote', 'IdeaController@vote');

    // ideas resource controller
    Route::resource('ideas', 'IdeaController');

    // get idea comments
    Route::resource('ideas/{idea_id}/comments', 'CommentController');

    // list categories
    Route::get('categories', function () {
        $categories = \App\Category::all();
        return response()->json($categories, 200);
    });
});
