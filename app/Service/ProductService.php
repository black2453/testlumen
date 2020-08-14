<?php

namespace App\Service;


use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * 寫入商品資料
     * @param $product
     * @return mixed
     */
    public function addProduct($product)
    {
//        dd($product);
        if (Product::query()->where('origin_id', $product['origin_id'])->doesntExist()) {
            $product = Product::create($product);
            $productId = $product->id;
        } else {
            $productId = Product::query()->where('origin_id', $product['origin_id'])->value('id');
        }

        return $productId;
    }

    /**
     * 寫入商品詳細圖片資料
     * @param $productImage
     * @return mixed
     */
    public function addProductImage($productImage)
    {
        if (ProductImage::query()->where('img', $productImage['img'])->where(
            'product_id',
            $productImage['product_id']
        )->doesntExist()) {
            $productImageData = ProductImage::create($productImage);
            $productImageId = $productImageData->id;
        } else {
            $productImageId = ProductImage::query()->where('img', $productImage['img'])->where(
                'product_id',
                $productImage['product_id']
            )->value('id');
        }

        return $productImageId;
    }

    /**
     * 商品查詢
     * @param $page
     * @param $limit
     * @param null $condition
     * @return array
     */
    public function getProductList($page, $limit, $condition = null)
    {
        $shopIcon[0] = [
            'img' => 'https://img.alicdn.com/imgextra/i1/2053469401/O1CN01o1MI292JJhz37UySP_!!2053469401.png',
            'width' => '48',
            'height' => '26'
        ];
        $shopIcon[1] = [
            'img' => 'https://img.alicdn.com/imgextra/i3/2053469401/O1CN01D0yc0w2JJhywhfGCZ_!!2053469401.png',
            'width' => '82',
            'height' => '26'
        ];
        $shopIcon[2] = [
            'img' => 'https://img.alicdn.com/imgextra/i1/2053469401/O1CN01Vo0nPr2JJhyxyS6Mn_!!2053469401.png',
            'width' => '60',
            'height' => '26'
        ];
        $salesDescruption[0] = [
            'type' => 'primary',
            'val' => '旗舰店'
        ];
        $salesDescruption[1] = [
            'type' => 'normal',
            'val' => '爆款'
        ];
        $salesDescruption[2] = [
            'type' => 'normal',
            'val' => '新品'
        ];
        $salesDescruption[3] = [
            'type' => 'other',
            'val' => '拍下半价'
        ];

        if (isset($condition)) {
            $query = Product::query()->where('is_online', 1)
                ->where(
                    function ($query) use ($condition) {
                        if ($condition['type']) {
                            $query->where('type_id', $condition['type']);
                        }

                        if ($condition['title']) {
                            $query->where('dtitle', 'like', '%' . $condition['title'] . '%');
                        }

                        if ($condition['price_maximum']) {
                            $query->where('price', '<=', $condition['price_maximum']);
                        }

                        if ($condition['price_minimum']) {
                            $query->where('price', '>=', $condition['price_minimum']);
                        }
                    }
                );

            if ($condition['order_by_column']) {
                if ($condition['order_by_direction']) {
                    $query = $query->orderBy($condition['order_by_column'], $condition['order_by_direction']);
                } else {
                    $query = $query->orderByDesc($condition['order_by_column']);
                }
            }

            $query = $query->offset(
                ($page - 1) * $limit
            )->limit($limit)->get();
        } else {
            $query = Product::query()->where('is_online', 1)->orderByDesc('id')->offset(
                ($page - 1) * $limit
            )->limit($limit)->get();
        }

        foreach ($query as $product) {
            if ($product->before_title_lables != 0) {
                $beforeTitleLablesStringArray = explode(',', $product->before_title_lables);

                $beforeTitleLablesArray = [];

                foreach ($beforeTitleLablesStringArray as $key => $value) {
                    $beforeTitleLablesArray[$key] = $shopIcon[$value - 1];
                }

                $product->before_title_lables = $beforeTitleLablesArray;
            }

            if ($product->under_price_labels != 0) {
                $underPriceLabelsStringArray = explode(',', $product->under_price_labels);

                $underPriceLabelsArray = [];

                foreach ($underPriceLabelsStringArray as $key => $value) {
                    $underPriceLabelsArray[$key] = $salesDescruption[$value - 1];
                }

                $product->under_price_labels = $underPriceLabelsArray;
            } else {
                $product->under_price_labels = [];
            }
        }

        return $query->toArray();
    }

    /**
     * 商品明細
     * @param $productId
     * @return array
     */
    public function getProductInfo($productId)
    {
        return Product::query()->leftJoin('type', 'product.type_id', 'type.id')->select(
            'product.id',
            'product.dtitle',
            'product.pic',
            'product.create_time',
            'product.xiaoliang',
            'product.original_price',
            'product.coupon_value',
            'product.price',
            'product.reason',
            'product.coupon_url'
        )->where('product.id', $productId)->first()->toarray();
    }

    /**
     * 商品詳細圖片資料
     * @param $productId
     * @return array
     */
    public function getProductImage($productId)
    {
        return ProductImage::query()->where('product_id', $productId)->get()->toArray();
    }

    /**
     * 確認商品是否存在
     * @param $productId
     * @return bool
     */
    public function checkProduct($productId)
    {
        if (Product::query()->where('id', $productId)->where('is_online', 1)->doesntExist()) {
            return false;
        }

        return true;
    }

    public function addType($type)
    {
        if (Type::query()->where('origin_api_cid', $type['origin_api_cid'])->doesntExist()) {
            $type = Type::create($type);
            $typeId = $type->id;
        } else {
            $typeId = Type::query()->where('origin_api_cid', $type['origin_api_cid'])->value('id');
        }

        return $typeId;
    }

    public function checkUserProductExist($method, $userToken, $product)
    {
        $userService = App::make(UserService::class);
        $userId = $userService->getUserIdWithToken($userToken);

        if ($method == 'POST') {
            if (Product::query()->where('user_id', $userId)->where('dtitle', $product['title'])->where(
                'pic',
                $product['pic']
            )->exists()) {
                return false;
            }
            return true;
        } elseif ($method == 'PUT') {
            if (isset($product['pic'])) {
                if (Product::query()->where('user_id', $userId)->where('dtitle', $product['title'])->where(
                    'pic',
                    $product['pic']
                )->where(
                    'id',
                    '<>',
                    $product['id']
                )->exists()) {
                    return false;
                }
            } else {
                if (Product::query()->where('user_id', $userId)->where('dtitle', $product['title'])->where(
                    'id',
                    '<>',
                    $product['id']
                )->exists()) {
                    return false;
                }
            }

            return true;
        } elseif ($method == 'DELETE' || $method == 'GET') {
            if (Product::query()->where('user_id', $userId)->where('id', $product['id'])->exists()) {
                return false;
            }
            return true;
        }
    }

    public function addUserProduct($product)
    {
        $userService = App::make(UserService::class);
        $userId = $userService->getUserIdWithToken($product['user_token']);

        //  是否上架(is_online)先設為1
        $productId = Product::insertGetId(
            [
                'user_id' => $userId,
                'dtitle' => $product['title'],
                'pic' => $product['pic'],
                'create_time' => Carbon::now()->toDateTimeString(),
                'original_price' => $product['original_price'],
                'coupon_value' => $product['coupon_value'],
                'price' => $product['price'],
                'reason' => $product['reason'],
                'type_id' => $product['type_id'],
                'coupon_url' => $product['coupon_url'],
                'xiaoliang' => rand(10000, 1000000),
                'goods_id' => '00000000',
                'origin_id' => '00000000',
                'quan_id' => '',
                'sales_num' => 0,
                'title' => '',
                'yongjin' => 0,
                'fashion_tag' => '',
                'base_price_text' => '',
                'base_price' => 0,
                'base_sale_num_text' => '',
                'thirty_sell_nun' => 0,
                'promotion' => '',
                'comments' => '',
                'renqi' => 0,
                'category_id' => '',
                'quan_link' => '',
                'comment' => 0,
                'quan_num' => 0,
                'is_delete' => 0,
                'is_online' => 0,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]
        );

        return $productId;
    }

    public function getImgPath($productId)
    {
        $fileArray = Product::query()->where('id', $productId)->value('pic');

        $fileArray = explode('/', $fileArray);

        return $fileArray[count($fileArray) - 2];
    }

    public function updateUserProduct($product)
    {
        $userService = App::make(UserService::class);
        $userId = $userService->getUserIdWithToken($product['user_token']);

        if (isset($product['pic'])) {
            Product::where('user_id', $userId)->where('id', $product['id'])->update(
                [
                    'dtitle' => $product['title'],
                    'pic' => $product['pic'],
                    'original_price' => $product['original_price'],
                    'coupon_value' => $product['coupon_value'],
                    'price' => $product['price'],
                    'reason' => $product['reason'],
                    'type_id' => $product['type_id'],
                    'coupon_url' => $product['coupon_url']
                ]
            );
        } else {
            Product::where('user_id', $userId)->where('id', $product['id'])->update(
                [
                    'dtitle' => $product['title'],
                    'original_price' => $product['original_price'],
                    'coupon_value' => $product['coupon_value'],
                    'price' => $product['price'],
                    'reason' => $product['reason'],
                    'type_id' => $product['type_id'],
                    'coupon_url' => $product['coupon_url']
                ]
            );
        }
    }

    public function deleteUserProduct($userToken, $productId)
    {
        $userService = App::make(UserService::class);
        $userId = $userService->getUserIdWithToken($userToken);

        Product::query()->where('user_id', $userId)->where('id', $productId)->delete();
    }

    public function getUserProductInfo($userToken, $productId)
    {
        $userService = App::make(UserService::class);
        $userId = $userService->getUserIdWithToken($userToken);

        return Product::query()->leftJoin('type', 'product.type_id', 'type.id')->select(
            'product.id',
            'product.dtitle',
            'product.pic',
            'product.create_time',
            'product.xiaoliang',
            'product.original_price',
            'product.coupon_value',
            'product.price',
            'product.reason',
            'product.type_id',
            'product.coupon_url',
            'type.name'
        )->where('product.id', $productId)->where('user_id', $userId)->first();
    }

    public function getUserProductList($userToken, $typeId, $limit)
    {
        $userService = App::make(UserService::class);
        $userId = $userService->getUserIdWithToken($userToken);

        if (isset($typeId)) {
            return Product::query()->where('user_id', $userId)->where('type_id', $typeId)->orderByDesc('id')->paginate(
                $limit
            );
        } else {
            return Product::query()->where('user_id', $userId)->orderByDesc('id')->paginate($limit);
        }
    }

    public function checkProductTypeExist($typeId)
    {
        if (Type::query()->where('id', $typeId)->exists()) {
            return true;
        }
        return false;
    }

    public function getProductTypeList()
    {
        return Type::query()->select('id', 'name')->get()->toArray();
    }

    public function getProductPaginate(
        $queryType,
        $keyword,
        $onlineStatus,
        $createdAtFrom,
        $createdAtTo,
        $page,
        $rowForPaginate
    ) {
        $map = array();

        //查询类型以及关键字
        switch ($queryType) {
            case 'id':
                $map['product.id'] = $keyword;
                break;
            case 'dtitle':
                $map['dtitle'] = $keyword;
                break;
            default:
                break;
        }

        //查询上架状态
        switch ($onlineStatus) {
            case '0':
            case '1':
                $map['is_online'] = $onlineStatus;
                break;
            default:
                break;
        }


        $productObject = Product::where(
            function ($query) use ($map, $createdAtFrom, $createdAtTo) {
                if (isset($map['product.id'])) {
                    $query->where($map);
                }

                if (isset($map['dtitle'])) {
                    $query->where('dtitle', 'like', '%' . $map['dtitle'] . '%');
                }

                if ($createdAtFrom != '') {
                    $query->where('created_at', '>=', $createdAtFrom);
                }

                if ($createdAtTo != '') {
                    $query->where('created_at', '<=', $createdAtTo);
                }
            }
        );

        return $productObject->leftJoin('type', 'product.type_id', 'type.id')->select(
            'product.*',
            'type.name as type_name'
        )->orderByDesc('product.id')
            ->paginate($rowForPaginate)->setPath('/admin/product');
    }

    public function checkProductExist($productId)
    {
        if (Product::query()->where('id', $productId)->exists()) {
            return true;
        }
        return false;
    }

    public function updateProduct($productId, $product)
    {
//        dd($product['is_online']);

        if (isset($product['is_online'])) {
            Product::where('id', $productId)->update(
                [
                    'is_online' => $product['is_online']
                ]
            );
        }

        if (isset($product['delete_status'])) {
            if ($product['delete_status'] == '0') {
                Product::query()->where('id', $productId)->restore();
            } else {
                Product::query()->where('id', $productId)->delete();
            }
        }
    }

    public function getProductTotalCount($condition)
    {
        if (isset($condition)) {
            $query = Product::query()->where('is_online', 1)
                ->where(
                    function ($query) use ($condition) {
                        if ($condition['type']) {
                            $query->where('type_id', $condition['type']);
                        }

                        if ($condition['title']) {
                            $query->where('dtitle', 'like', '%' . $condition['title'] . '%');
                        }

                        if ($condition['order_by_column']) {
                            if ($condition['order_by_direction']) {
                                $query->orderBy($condition['order_by_column'], $condition['order_by_direction']);
                            } else {
                                $query->orderByDesc($condition['order_by_column']);
                            }
                        }

                        if ($condition['price_maximum']) {
                            $query->where('price', '<=', $condition['price_maximum']);
                        }

                        if ($condition['price_minimum']) {
                            $query->where('price', '>=', $condition['price_minimum']);
                        }
                    }
                )->count();
        } else {
            $query = Product::query()->where('is_online', 1)->orderByDesc('id')->count();
        }

        return $query;
    }

    public function updateProductType()
    {
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%2020%')->update(['type_id' => '1']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%驱蚊防虫%')->update(['type_id' => '1']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%口罩%')->update(['type_id' => '1']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%三只松鼠%')->update(['type_id' => '2']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%欧莱雅%')->update(['type_id' => '2']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%晨光%')->update(['type_id' => '2']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%营养早餐%')->update(['type_id' => '3']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%方便速食%')->update(['type_id' => '3']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%休闲食品%')->update(['type_id' => '3']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%补水保湿%')->update(['type_id' => '4']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%平价面膜%')->update(['type_id' => '4']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%魅惑红唇%')->update(['type_id' => '4']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%男士护肤%')->update(['type_id' => '4']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%美妆工具%')->update(['type_id' => '4']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%防晒隔离%')->update(['type_id' => '4']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%连衣裙%')->update(['type_id' => '5']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%薄款卫衣%')->update(['type_id' => '5']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%潮流外套%')->update(['type_id' => '5']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%春夏T恤%')->update(['type_id' => '5']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%休闲套装%')->update(['type_id' => '5']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%雪纺%')->update(['type_id' => '5']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%牛仔裤%')->update(['type_id' => '5']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%半身裙%')->update(['type_id' => '5']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%时髦阔腿裤%')->update(['type_id' => '5']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%哈伦裤%')->update(['type_id' => '5']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%休闲裤%')->update(['type_id' => '5']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%蓝牙耳机%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%充电宝%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%手机壳%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%数据线%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%手机贴膜%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%手机支架%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%耳麦%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%电脑外设%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%音箱%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%键盘鼠标%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%大家电%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%厨房家电%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%电子学习器%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%榨汁机%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%清洁电器%')->update(['type_id' => '6']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%剃须刀%')->update(['type_id' => '6']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%收纳神器%')->update(['type_id' => '7']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%洗衣液%')->update(['type_id' => '7']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%凉鞋%')->update(['type_id' => '8']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%休闲鞋%')->update(['type_id' => '8']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%宽松休闲%')->update(['type_id' => '9']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%短裤%')->update(['type_id' => '9']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%聚拢文胸%')->update(['type_id' => '10']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%薄款文胸%')->update(['type_id' => '10']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%无痕文胸%')->update(['type_id' => '10']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%文具%')->update(['type_id' => '11']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%精美耳饰%')->update(['type_id' => '12']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%简约手链%')->update(['type_id' => '12']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%鸭舌帽%')->update(['type_id' => '12']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%手提包%')->update(['type_id' => '13']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%斜跨包%')->update(['type_id' => '13']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%健身装备%')->update(['type_id' => '14']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%球类用品%')->update(['type_id' => '14']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%钓鱼%')->update(['type_id' => '14']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%瑜伽%')->update(['type_id' => '14']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%四件套%')->update(['type_id' => '15']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%床上用品%')->update(['type_id' => '15']);

        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%拉拉裤%')->update(['type_id' => '16']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%袜子%')->update(['type_id' => '16']);
        Product::query()->where('type_id', '0')->where('dtitle', 'like', '%女童%')->update(['type_id' => '16']);

        Product::query()->where('type_id', '0')->update(['type_id' => '1']);
    }
}
