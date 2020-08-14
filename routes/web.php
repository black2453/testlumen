<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get(
    '/show_version',
    function () use ($router) {
        return $router->app->version();
    }
);

$router->options('{any:.*}', ['middleware' => 'cors']);
// api爬蟲，抓資料
//$router->get('/collect_type', 'ProductController@collectProductType');
//$router->get('/collect', 'ProductController@collectData');
//$router->get('/collect_image', 'ProductController@collectProductImage');
//$router->get('/update_product_type', 'ProductController@updateProductType');
//$router->get('/send', 'UserController@send');

// 商品列表
$router->get('/api/products', 'ProductController@getProductList');
// 商品明細
$router->get('/api/product/{id}', 'ProductController@getProductInfo');
// 用戶註冊
$router->post('/api/register', 'UserController@registerUser');
// 用戶登入
$router->post('/api/login', 'UserController@login');
// 抓商品類別
$router->get('/api/types', 'ProductController@getProductTypeList');


Route::group(
    ['prefix' => '/api/user/'],
    function ($router) {
        // 用戶新增商品
        $router->post('product', 'ProductController@addUserProduct');
        // 用戶修改商品內容
        $router->put('product/{id}', 'ProductController@editUserProduct');
        // 用戶刪除商品
        $router->delete('product/{id}', 'ProductController@deleteUserProduct');
        // 用戶商品明細
        $router->get('product/{id}', 'ProductController@getUserProductInfo');
//        $router->post('product/{id}', 'ProductController@getUserProductInfo');
        // 用戶商品列表
        $router->get('products', 'ProductController@getUserProductList');
//        $router->post('products', 'ProductController@getUserProductList');
        // 用戶資訊
        $router->post('info','UserController@getUserInfo');
    }
);

Route::group(
    ['prefix' => '/admin/'],
    function ($router) {
        // 重導回入口
        $router->get(
            '/login',
            function () {
                return view('login');
            }
        );
        // 後台入口
        $router->get(
            '/',
            function () {
                return view('login');
            }
        );
        // 管理者登入
        $router->post('doLogin', 'AdminController@doLogin');
        // 管理者登出
        $router->get('doLogout','AdminController@doLogout');
        // 審核
        $router->put('product','ProductController@editProduct');
        // 永久刪除
        $router->delete('product/{id}','ProductController@delProduct');
        // 明細
        $router->get('product/{id}','ProductController@getProductInfoBackend');
        // 主頁(先導引到商品頁)
        $router->get('index', 'ProductController@index');
        // 商品處理主頁
        $router->get('product','ProductController@index');

    }
);


$router->get(
    '/',
    function () use ($router) {
        return $router->app->version();
    }
);
