<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
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

/** @var Router $router */
$router->group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function (Router $router) {
    $router->post('login', [
        'as' => 'api.auth.login',
        'uses' => 'AuthController@login',
    ]);
    $router->post('register', [
        'as' => 'api.auth.register',
        'uses' => 'AuthController@register',
    ]);
    $router->post('logout', [
        'as' => 'api.auth.logout',
        'uses' => 'AuthController@logout',
    ]);
    $router->get('user-profile', [
        'as' => 'api.auth.user_profile',
        'uses' => 'AuthController@userProfile',
    ]);
    $router->post('change-pass', [
        'as' => 'api.auth.change_pass',
        'uses' => 'AuthController@changePassWord',
    ]);
});
