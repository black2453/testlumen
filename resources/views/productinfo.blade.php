<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body style="width: 100%">
<form>
    <div class="table-responsive">
        <div class="container">
            <div class="row">
                <div class="col">
                    商品标题
                </div>
                <div class="col">
                    {{$product['dtitle']}}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    商品图片
                </div>
                <div class="col">
                    <img class="fn_cover" src="{{$product['pic']}}" style="width: 200px;height: 200px" >
                    </img>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    新增时间
                </div>
                <div class="col">
                    {{$product['create_time']}}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    累计销量
                </div>
                <div class="col">
                    {{round($product['xiaoliang'])}}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    原價
                </div>
                <div class="col">
                    {{$product['original_price']}}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    優惠券面額
                </div>
                <div class="col">
                    {{round($product['coupon_value'])}}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    售價
                </div>
                <div class="col">
                    {{$product['price']}}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    推薦理由
                </div>
                <div class="col">
                    {{$product['reason']}}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    網址
                </div>
                <div class="col">
                    {{$product['coupon_url']}}
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Bootstrap core JavaScript-->
<script src="../../vendor/jquery/jquery.min.js"></script>
<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../../js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../../vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->

<script type="text/javascript">
</script>
</body>
</html>
