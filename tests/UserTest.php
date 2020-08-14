<?php
use Illuminate\Http\UploadedFile;
class UserTest extends TestCase
{
    public function testUserLogin()
    {
        $this->json('POST', '/api/login', ['account' => 'tttttt@gmail.com', 'password' => 'bb3434bc'])
            ->seeJsonStructure(
                [
                    'token'
                ]
            );
    }

    public function testUserInfo()
    {
        $token = \App\Models\User::query()->where('account', 'tttttt@gmail.com')->select('token')->first();

        $this->json(
            'POST',
            '/api/user/info',
            ['token' => $token]
        )
            ->seeJsonStructure(
                [
                    'data'
                    => [
                        'account',
                        'is_auth',
                        'token',
                        'last_login_time'
                    ]
                ]
            );
    }

    public function testUserProducts()
    {
        $token = \App\Models\User::query()->where('account', 'tttttt@gmail.com')->select('token')->first();

        $this->json(
            'GET',
            '/api/user/products',
            ['user_token' => $token]
        )
            ->seeJsonStructure(
                [
                    'data'
                    => [
                        'current_page',
                        'data',
                        'total'
                    ]
                ]
            );
    }

    public function testUserProduct()
    {
        $user = \App\Models\User::query()->where('account', 'tttttt@gmail.com')->get()->first();
        $product = \App\Models\Product::query()->where('user_id', $user->id)->get();

        foreach ($product as $product) {
            $this->json(
                'GET',
                '/api/user/product/' . $product->id,
                ['user_token' => $user->token]
            )
                ->seeJsonStructure(
                    [
                        'data',
                        'recommend_similarity'
                    ]
                );
        }
    }

    public function testUserRegister()
    {
        $userData = [
            'account' =>'aaa@bb.com',
            'password' =>'123456789'
        ];

        $this->json(
            'POST',
            '/api/register',
            $userData
        )
            ->seeJsonStructure(
                [
                    'msg',
                    'code'
                ]
            );

        \App\Models\User::query()->where('account','aaa@bb.com')->delete();
        \App\Models\User::query()->where('account','aaa@bb.com')->forceDelete();
    }

//    public function testUserCreateProduct()
//    {
//        $file = UploadedFile::fake()->image('avatar.jpg');
//
//        $user = \App\Models\User::query()->where('account', 'tttttt@gmail.com')->get()->first();
//        $productData = [
//            'user_token' =>$user->token,
//            'd_title'    =>'李子柒桂花坚果藕dwqdwq粉羹坚果纯藕粉350g',
//            'original_price' => '54.7',
//            'coupon_value' => '5',
//            'price' => '49.7',
//            'reason' =>'【买就送碗，限30分钟】dd高营养，进口坚果，大颗粒看得见。三分钟美味代餐，早餐、下午茶，想吃就吃。老人小孩也放心，不添加防腐剂。藕粉新定义，代餐新选择。',
//            'type_id' => '6',
//            'coupon_url' => 'cedcwedew',
//            'pic' =>$file
//        ];
//
//        $this->call(
//            'POST',
//            '/api/user/product',
//            $productData,
//            [],
//            $_FILES,
//            []
//        )
//            ->seeJsonStructure(
//                [
//                    'msg',
//                    'product_id'
//                ]
//            );
//    }
}
