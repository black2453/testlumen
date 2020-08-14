<?php

class ProductTest extends TestCase
{
    public function testFirst()
    {
        $this->get('/show_version');

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );
    }

    public function testProductType()
    {
        $this->json('GET', '/api/types')
            ->seeJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'name'
                        ]
                    ]
                ]
            );
    }

    public function testProductList()
    {
        $this->json('GET', '/api/products')
            ->seeJsonStructure(
                [
                    'data' => [
                        'list' => [
                            '*' => [
                                'id',
                                'origin_id',
                                'pic',
                                'dtitle',
                                'original_price',
                                'xiaoliang',
                                'price',
                                'coupon_value'
                            ]
                        ]
                    ]
                ]
            );
    }

    public function testProductInfo()
    {
        $product = \App\Models\Product::query()->where('is_online',1)->get();

        foreach ($product as $product){
            $this->json('GET', '/api/product/' . $product->id)
                ->seeJsonStructure(
                    [
                        'data' => [
                            'product_detail' => [
                                'id',
                                'dtitle',
                                'pic',
                                'xiaoliang',
                                'original_price',
                                'coupon_value',
                                'price',
                                'reason',
                                'coupon_url'
                            ],
                            'product_image' => [
                                '*' => [
                                    'id',
                                    'product_id',
                                    'img',
                                    'width',
                                    'height'
                                ]
                            ],
                            'recommend_similarity' => [
                                '*' => [
                                    'id',
                                    'dtitle',
                                    'pic',
                                    'xiaoliang',
                                    'original_price',
                                    'coupon_value',
                                    'price',
                                    'reason',
                                    'coupon_url'
                                ]
                            ]
                        ]
                    ]
                );
        }
    }

}
