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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware'=>'serializer:array'
], function ($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        //短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');
        //注册
        $api->post('users', 'UserController@store')->name('api.users.store');
        //图片验证码
        $api->post('captchas', 'CaptchasController@store')->name('api.captchas.store');
        //第三方登录
        $api->post('socials/{social_type}/authorizations', 'AuthorizationController@socialStore')->name('api.social.authorizations.store');
        //登录
        $api->post('authorizations', 'AuthorizationController@store')->name('api.authorization.store');
        // 刷新token
        $api->put('authorizations/current', 'AuthorizationController@update')
            ->name('api.authorizations.update');
        // 删除token
        $api->delete('authorizations/current', 'AuthorizationController@destroy')
            ->name('api.authorizations.destroy');

        //需要token验证的接口
        $api->group(['middleware'=>'api.auth'],function ($api){
            //当前登录用户信息
           $api->get('user','UserController@me')->name('api.user.show');
            //编辑用户信息
            $api->patch('user','UserController@update')->name('api.user.update');
            //图片资源
            $api->post('images','ImagesController@store')->name('api.images.store');
        });
    });
});