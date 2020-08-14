<?php

namespace App\Http\Controllers;

use App\Service\ProductService;
use App\Service\UserService;
use Curl\Curl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Lumen\Routing\Controller as BaseController;

class ProductController extends BaseController
{
    /**
     * 透過api採集資料
     * @param ProductService $productService
     * @throws \ErrorException
     */
    public function collectData(ProductService $productService)
    {
        set_time_limit(0);
        //商品數量似乎會多到10w 9909*10=99090
        //先以pageSize=100和pageId=1~150處理
        for ($pageId = 1; $pageId <= 50; $pageId++) {
            $curl = new Curl();
            $result = $curl->get(
                'http://cmsjapi.ffquan.cn/api/category/index/lingquan-live-new?pageId=' . $pageId . '&pageSize=100&entityId=4&type=1&version=v1&tuserId=371594&isWechat=0'
            );
            echo $pageId . '<br>';

            foreach ($result->data->list as $json) {
                $product = [];
                foreach ($json as $key => $value) {
                    //  商品資訊
                    if (!($key == 'beforeTitleLables' || $key == 'underPriceLabels' || $key == 'market_group' || $key == '_id' || $key == 'id' || $key == 'createTime' || $key == 'goodsId' || $key == 'huodongType' || $key == 'hzQuanOver' || $key == 'quanId' || $key == 'quanJine' || $key == 'salesNum' || $key == 'startTime' || $key == 'yuanjia' || $key == 'jiage' || $key == 'fashionTag' || $key == 'basePriceText' || $key == 'basePrice' || $key == 'baseSaleNumText' || $key == 'thirtySellNun'|| $key == 'labelOne'|| $key == 'labelTwo'|| $key == 'beforeTitleLables'|| $key == 'beforePriceLabelType'|| $key == 'quanMLink'|| $key == 'smallLabel'|| $key == 'normalLabel'|| $key == 'marketId' )) {
                        $product[$key] = $value;
                    }
                    if ($key == 'id') {
                        $product['origin_id'] = $value;
                    }

                    if ($key == 'createTime') {
                        $product['create_time'] = $value;
                    }

                    if ($key == 'goodsId') {
                        $product['goods_id'] = $value;
                    }

                    if ($key == 'huodongType') {
                        $product['huodong_type'] = $value;
                    }

                    if ($key == 'hzQuanOver') {
                        $product['hz_quan_over'] = $value;
                    }

                    if ($key == 'quanId') {
                        $product['quan_id'] = $value;
                    }

                    if ($key == 'quanJine') {
                        $product['coupon_value'] = $value;
                    }

                    if ($key == 'salesNum') {
                        $product['sales_num'] = $value;
                    }

                    if ($key == 'startTime') {
                        $product['start_time'] = $value;
                    }

                    if ($key == 'yuanjia') {
                        $product['original_price'] = $value;
                    }

                    if ($key == 'jiage') {
                        $product['price'] = $value;
                    }

                    if ($key == 'fashionTag') {
                        $product['fashion_tag'] = $value;
                    }

                    if ($key == 'basePriceText') {
                        $product['base_price_text'] = $value;
                    }

                    if ($key == 'basePrice') {
                        $product['base_price'] = $value;
                    }

                    if ($key == 'baseSaleNumText') {
                        $product['base_sale_num_text'] = $value;
                    }

                    if ($key == 'labelOne') {
                        $product['label_one'] = $value;
                    }

                    if ($key == 'labelTwo') {
                        $product['label_two'] = $value;
                    }

                    if ($key == 'marketId') {
                        $product['market_id'] = $value;
                    }

                    if ($key == 'beforePriceLabelType') {
                        $product['before_price_label_type'] = $value;
                    }

                    if ($key == 'quanMLink') {
                        $product['quan_link'] = $value;
                    }

                    if ($key == 'thirtySellNun') {
                        $product['thirty_sell_nun'] = $value;
                    }

                    $product['reason'] = '';
                    $product['type_id'] = rand(1,16);

                    //  店家icon資訊(標題前面的文字)
                    if ($key == 'beforeTitleLables') {
                        foreach ($value as $iconData) {
                            $shopIcon = [];
                            foreach ($iconData as $key2 => $value) {
                                $shopIcon[$key2] = $value;
                            }
                            switch ($shopIcon['img']) {
                                case "https://img.alicdn.com/imgextra/i1/2053469401/O1CN01o1MI292JJhz37UySP_!!2053469401.png":
                                    $product['before_title_lables'] = '1';
                                    break;
                                case "https://img.alicdn.com/imgextra/i3/2053469401/O1CN01D0yc0w2JJhywhfGCZ_!!2053469401.png":
                                    $product['before_title_lables'] = '2';
                                    break;
                                case "https://img.alicdn.com/imgextra/i1/2053469401/O1CN01Vo0nPr2JJhyxyS6Mn_!!2053469401.png":
                                    $product['before_title_lables'] = '3';
                                    break;
                                default:
                                    $product['before_title_lables'] = '0';
                            }
                        }
                    }
                    // 電商術語文字
                    if ($key == 'underPriceLabels') {
                        $underPriceLabelsString = '';
                        foreach ($value as $iconData) {
                            $salesDescription = [];
                            foreach ($iconData as $key2 => $value) {
                                $salesDescription[$key2] = $value;
                            }

                            switch ($salesDescription['val']) {
                                case "旗舰店":
                                    $underPriceLabelsString = $underPriceLabelsString . '1,';
                                    break;
                                case "爆款":
                                    $underPriceLabelsString = $underPriceLabelsString . '2,';
                                    break;
                                case "新品":
                                    $underPriceLabelsString = $underPriceLabelsString . '3,';
                                    break;
                                case "拍下半价":
                                    $underPriceLabelsString = $underPriceLabelsString . '4,';
                                    break;
                                default:
                                    $underPriceLabelsString = ',';
                            }
                        }
                        $underPriceLabelsString = substr($underPriceLabelsString, 0, -1);
                        $product['under_price_labels'] = $underPriceLabelsString;
                    }
                    // 未知
                    if ($key == 'market_group') {
                        if ($value != '') {
                            if (count($value) == 1) {
                                foreach ($value as $values) {
                                    $product[$key] = $values;
                                }
                            } else {
                                $product[$key] = '';
                            }
                        }
                    }
                }

//                dd($product);
                $productService->addProduct($product);
            }
        }
    }

    /**
     * 採集商品详情 的圖片
     * @param ProductService $productService
     * @throws \ErrorException
     */
    public function collectProductImage(ProductService $productService)
    {
        set_time_limit(0);
        $limit = 100;

        for ($page = 1; $page <= 50; $page++) {
            $productData = $productService->getProductList($page, $limit);
            echo 'page:' . $page . '<br>';
            foreach ($productData as $product) {
                $curl = new Curl();
                $result = $curl->get(
                    'http://cmsjapi.ffquan.cn/api/goods/get-goods-detail-img?goodsId=' . $product['goods_id']
                );

                $resultArray = (array)$result;

                if (!isset($resultArray['data'])) {
                    echo 'testaaaaaaaaa';
                    continue;
                }
                $productImageArray = json_decode($result->data);

                foreach ($productImageArray as $productImageArray) {
                    $productImage = [];
                    foreach ($productImageArray as $key => $value) {
                        $productImage[$key] = $value;
                    }
                    $productImage['product_id'] = $product['id'];

                    $productService->addProductImage($productImage);
                }
            }
        }
    }

    public function collectProductType(ProductService $productService)
    {
        $curl = new Curl();
        $result = $curl->get(
            'http://shengqian.com/index.php?r=class/category&type=1'
        );

        $result = json_decode($result);
        foreach ($result->data->data as $typeDataBefore) {
            $type = [];
            $type['name'] = $typeDataBefore->name;
            $type['origin_api_cid'] = $typeDataBefore->cid;
            $productService->addType($type);
        }
    }

    public function updateProductType(ProductService $productService)
    {
        $productService->updateProductType();
    }

    /**
     * 商品查詢
     * @param Request $request
     * @param ProductService $productService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductList(Request $request, ProductService $productService)
    {
        $page = $request->page;
        $limit = $request->count;

        $conditon['type'] = $request->type;
        $conditon['title'] = $request->title;
        $conditon['order_by_column'] = $request->order_by_column;
        $conditon['order_by_direction'] = $request->order_by_direction;
        $conditon['price_maximum'] = $request->price_maximum;
        $conditon['price_minimum'] = $request->price_minimum;

        if (isset($conditon['order_by_direction'])) {
            if (!($conditon['order_by_direction'] == 'asc' || $conditon['order_by_direction'] == 'desc')) {
                return response()->json(['error' => '查詢排序錯誤'], 400);
            }
        }

        if (isset($conditon['order_by_column'])) {
            if (!($conditon['order_by_column'] == 'price' || $conditon['order_by_column'] == 'xiaoliang' || $conditon['order_by_column'] == 'created_at')) {
                return response()->json(['error' => '查詢排序錯誤'], 400);
            }
        }

        if (isset($conditon['price_maximum']) && isset($conditon['price_minimum'])) {
            if ($conditon['price_maximum'] < $conditon['price_minimum']) {
                $changeValue = $conditon['price_maximum'];
                $conditon['price_maximum'] = $conditon['price_minimum'];
                $conditon['price_minimum'] = $changeValue;
            }
        }

        if ($limit == '') {
            $limit = 10;
        }

        if ($page == '') {
            $page = 1;
        }

        $data = ['list' => $productService->getProductList($page, $limit, $conditon)];

        $count = $productService->getProductTotalCount($conditon);

        return response()->json(['code' => 0, 'msg' => '成功', 'data' => $data, 'count' => $count]);
    }

    /**
     * 商品明細
     * @param Request $request
     * @param ProductService $productService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductInfo(Request $request, ProductService $productService)
    {
        $id = $request->id;

        if (!$productService->checkProduct($id)) {
            return response()->json(['error' => '商品不存在'], 400);
        }

        //  商品資料
        $infoData['product_detail'] = $productService->getProductInfo($id);
        //  商品資料->推薦理由 = 评价
        if ($infoData['product_detail']['reason'] == '') {
            $infoData['product_detail']['reason'] = '健康美味手撕素肉，非转基因黄豆，精心制作，似肉非肉，饱含植物蛋白，再也不怕胖，怎么吃都不长肉！';
        }

        //  轉址領券預留
        if ($infoData['product_detail']['coupon_url'] == '') {
            $infoData['product_detail']['coupon_url'] = 'https://uland.taobao.com/coupon/edetail?e=55dT6%2FnfkF0GQASttHIRqZUrSrqqg%2BZtK2%2BJI7%2F4RPAL00DtaFDxCHncls6RlLHSCJ9WhzVfSykubg%2BU2SFpMEhMrwC97%2FSy7yPcEb%2Fg6hawksixcOKrLieO62LyI2hNtPnORs8IuoFubrOiJDB1lEp%2BKl%2Fq3XcYOh4LHcmKitzZeF9VVoymoWi8mFlZo79XjsOX5qBHpd4%3D&traceId=0b0afe9d15940955740186670e&union_lens=lensId:TAPI@1594095574@0b57b5a6_0d82_17327801c12_67ba@01&xId=6oSma3ROSneY6u9milnda6Gd0588PoC8HMYVuycXhzyt0naVAGh92ndFGAXryWnf3DxPbicf1HF7PrfeUUKdOxDkJblBYkj7qU64QGkSBE0c&activityId=5a5f0f98591842048c358cdceffc6f14';
        }

        //  商品详情
        $infoData['product_image'] = $productService->getProductImage($id);
        //  店铺信息 => 先不要

        //  可能喜欢=相似推荐 = 今日热销
        $page = mt_rand(1, 50);
        $limit = 40;
        $infoData['recommend_similarity'] = $productService->getProductList($page, $limit);

        return response()->json(['code' => 0, 'msg' => '成功', 'data' => $infoData]);
    }

    /**
     * 用戶新增商品
     * @param Request $request
     * @param ProductService $productService
     * @param UserService $userService
     * @return \Illuminate\Http\JsonResponse
     */
    public function addUserProduct(Request $request, ProductService $productService, UserService $userService)
    {
        $limit = 2 * 1024 * 1024;
        $domain = 'http://xin221.com:4001/user/';

        if (isset($_FILES['pic']['size'])) {
            if ($_FILES['pic']['error'] == '0') {
                if ($_FILES['pic']['size'] > $limit) {
                    return response()->json(['error' => '图片不可大于2M'], 400);
                }

                if (!($_FILES['pic']['type'] == 'image/jpeg' || $_FILES['pic']['type'] == 'image/png')) {
                    return response()->json(['error' => '必须是图片档'], 400);
                }

                $filePath = Str::random(30);
                mkdir(public_path('user') . '/' . $filePath);

                file_put_contents(
                    public_path('user') . '/' . $filePath . '/' . $_FILES['pic']['name'],
                    file_get_contents($_FILES['pic']['tmp_name'])
                );

                $pic = $domain . $filePath . '/' . $_FILES['pic']['name'];
            } else {
                return response()->json(['error' => '图片不可大于2M'], 400);
            }
        } else {
            return response()->json(['error' => '图片不存在'], 400);
        }

        $userToken = $request->user_token;
        $dTitle = $request->d_title;
        $originalPrice = $request->original_price;
        $couponValue = $request->coupon_value;
        $price = $request->price;
        $reason = $request->reason;
        $typeId = $request->type_id;
        $couponUrl = $request->coupon_url;

        $product['title'] = $dTitle;

        $product['pic'] = $pic;
        $product['original_price'] = $originalPrice;
        $product['coupon_value'] = $couponValue;
        $product['price'] = $price;
        $product['reason'] = $reason;
        $product['type_id'] = $typeId;
        $product['user_token'] = $userToken;
        $product['coupon_url'] = $couponUrl;


        // 有傳用戶id時判斷是否存在，沒傳統一當作是api抓來的
        if (!$userToken) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        if (!$userService->checkUserTokenExist($userToken)) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        if (!$dTitle) {
            return response()->json(['error' => '商品标题不可为空'], 400);
        }

        if (!$pic) {
            return response()->json(['error' => '商品图片不可为空'], 400);
        }

        if ($price + $couponValue != $originalPrice) {
            return response()->json(['error' => '价格异常，请再行检查'], 400);
        }

        if (!$reason) {
            return response()->json(['error' => '推荐理由不可为空'], 400);
        }

        if (!$typeId) {
            return response()->json(['error' => '商品类别不可为空'], 400);
        }

        if (!$productService->checkProductTypeExist($typeId)) {
            return response()->json(['error' => '商品类别不存在'], 400);
        }

        if (!$couponUrl) {
            return response()->json(['error' => '優惠券網址不可为空'], 400);
        }

        $productId = $productService->addUserProduct($product);
//      銷量random出一個數字即可

        if (!$productId) {
            return response()->json(['error' => '商品新增失敗'], 400);
        }

        return response()->json(['code' => 0, 'msg' => '商品新增成功', 'product_id' => $productId]);
    }

    /**
     * 會員修改商品
     * @param Request $request
     * @param UserService $userService
     * @param ProductService $productService
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUserProduct(Request $request, UserService $userService, ProductService $productService)
    {
        $userToken = $request->input('user_token');
        $product['title'] = $request->input('d_title');

        $product['id'] = $request->id;
        $product['original_price'] = $request->input('original_price');
        $product['coupon_value'] = $request->input('coupon_value');
        $product['price'] = $request->input('price');
        $product['reason'] = $request->input('reason');
        $product['user_token'] = $userToken;
        $product['type_id'] = $request->input('type_id');
        $product['coupon_url'] = $request->input('coupon_url');
        $limit = 2 * 1024 * 1024;
        $domain = 'http://xin221.com:4001/user/';

        // 有傳用戶id時判斷是否存在，沒傳統一當作是api抓來的
        if (!$userToken) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        if (!$userService->checkUserTokenExist($userToken)) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        if (!$product['title']) {
            return response()->json(['error' => '商品标题不可为空'], 400);
        }

        if ($product['price'] + $product['coupon_value'] != $product['original_price']) {
            return response()->json(['error' => '价格异常，请再行检查'], 400);
        }

        if (!$product['reason']) {
            return response()->json(['error' => '推荐理由不可为空'], 400);
        }

        if (!$product['type_id']) {
            return response()->json(['error' => '商品类别不可为空'], 400);
        }

        if (!$productService->checkProductTypeExist($product['type_id'])) {
            return response()->json(['error' => '商品类别不存在'], 400);
        }

        if (isset($_FILES['pic']['size'])) {
            if ($_FILES['pic']['error'] == '0') {
                if ($_FILES['pic']['size'] > $limit) {
                    return response()->json(['error' => '圖片不可大於2M'], 400);
                }

                if (!($_FILES['pic']['type'] == 'image/jpeg' || $_FILES['pic']['type'] == 'image/png')) {
                    return response()->json(['error' => '必须是图片档'], 400);
                }

                $filePath = $productService->getImgPath($product['id']);

                file_put_contents(
                    public_path('user') . '/' . $filePath . '/' . $_FILES['pic']['name'],
                    file_get_contents($_FILES['pic']['tmp_name'])
                );

                $product['pic'] = $domain . $filePath . '/' . $_FILES['pic']['name'];
            } else {
                return response()->json(['error' => '圖片不可大於2M'], 400);
            }
        }

        if (!$product['coupon_url']) {
            return response()->json(['error' => '優惠券網址不可为空'], 400);
        }

        $productService->updateUserProduct($product);

        return response()->json(['code' => 0, 'msg' => '商品更新成功', 'product_id' => $product['id']]);
    }

    public function deleteUserProduct(Request $request, UserService $userService, ProductService $productService)
    {
        $productId = $request->id;
        $userToken = $request->user_token;
        $product['id'] = $productId;

        if (!$userToken) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        if (!$userService->checkUserTokenExist($userToken)) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        $productService->deleteUserProduct($userToken, $productId);

        return response()->json(['code' => 0, 'msg' => '商品删除成功']);
    }

    public function getUserProductInfo(Request $request, UserService $userService, ProductService $productService)
    {
        $productId = $request->id;
        $userToken = $request->user_token;
        $product['id'] = $productId;

        if (!$userToken) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        if (!$userService->checkUserTokenExist($userToken)) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        //  可能喜欢=相似推荐 = 今日热销
        $page = mt_rand(1, 50);
        $limit = 40;

        return response()->json(
            [
                'code' => 0,
                'msg' => '成功',
                'data' => $productService->getUserProductInfo($userToken, $productId),
                'recommend_similarity' => $productService->getProductList($page, $limit)
            ]
        );
    }

    public function getUserProductList(Request $request, UserService $userService, ProductService $productService)
    {
        $userToken = $request->user_token;
        $typeId = $request->type_id;
        $limit = $request->limit;

        if ($limit == '') {
            $limit = 15;
        }

        if (!$userToken) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        if (!$userService->checkUserTokenExist($userToken)) {
            return response()->json(['error' => '登入逾时，请重新登入'], 403);
        }

        return response()->json(
            ['code' => 0, 'msg' => '成功', 'data' => $productService->getUserProductList($userToken, $typeId, $limit)]
        );
    }

    public function getProductTypeList(ProductService $productService)
    {
        return response()->json(
            ['code' => 0, 'msg' => '成功', 'data' => $productService->getProductTypeList()]
        );
    }

    public function index(Request $request, ProductService $productService)
    {
        $queryType = $request->input('query_type');
        $keyword = $request->input('keyword');
        $onlineStatus = $request->input('online_status');
        $createdAtFrom = $request->input('created_at_from');
        $createdAtTo = $request->input('created_at_to');

        $page = $request->input('page') ?: 1;
        $rowForPaginate = $request->input('row_for_paginate') ?: 30;

        /**
         * 時間條件預帶
         */
        if ($createdAtFrom == '') {
//            $createdAtFrom = Carbon::today();
        }
        if ($createdAtTo == '') {
//            $createdAtTo = Carbon::tomorrow();
        }

        $productData = $productService->getProductPaginate(
            $queryType,
            $keyword,
            $onlineStatus,
            $createdAtFrom,
            $createdAtTo,
            $page,
            $rowForPaginate
        );

        /**
         * 查詢條件回拋view
         */
        $searchConditions['query_type'] = $queryType;
        $searchConditions['keyword'] = $keyword;
        $searchConditions['online_status'] = $onlineStatus;
        $searchConditions['created_at_from'] = $createdAtFrom;
        $searchConditions['created_at_to'] = $createdAtTo;
        $searchConditions['row_for_paginate'] = $rowForPaginate;

        return view('product', compact('productData', 'searchConditions'));
    }

    public function editProduct(Request $request, ProductService $productService)
    {
        $productId = $request->input('id');
        $status = $request->input('status');
        $deleteStatus = $request->input('delete_status');

        if (!$productService->checkProductExist($productId)) {
            return 'fail<CBK>商品不存在';
        }

        if (isset($status)) {
            $product['is_online'] = $status;
            $productService->updateProduct($productId, $product);
        }

        if (isset($deleteStatus)) {
            $product['delete_status'] = $deleteStatus;
            $productService->updateProduct($productId, $product);
        }

        return 'success';
    }

    public function delProduct(Request $request, ProductService $productService)
    {
        $productId = $request->id;

        if (!$productService->checkProductExist($productId)) {
            return 'fail<CBK>商品不存在';
        }

        $productService->forceDeleteProduct($productId);

        return 'success';
    }

    public function getProductInfoBackend(Request $request, ProductService $productService)
    {
        $productId = $request->id;
        //傳來的id為空回應403
        if ($productId == '') {
            return abort(404);
        }

        $product = $productService->getProductInfo($productId);

        return view('productinfo', compact('product'));
    }
}
