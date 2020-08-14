@section('title', '商品管理')

@extends('master')
@section('js')
    <script src="../js/laydate/laydate.js"></script>
    <script type="text/javascript">
        $(function () {
            laydate.render({
                elem: '#created_at_from'
                , type: 'datetime'
            });
            //时间选择器
            laydate.render({
                elem: '#created_at_to'
                , type: 'datetime'
            });
        });
        //抓行數
        $(".dropdown-menu a ").click(function () {
            document.getElementsByName('row_for_paginate')[0].value = $(this).text();
            $('#show_for_paginate').text($(this).text());
        });

        function changeonline(id, status) {
            if (status == '1') {
                var confirm_value = '确定要审核通过??';
            } else {
                var confirm_value = '确定要审核取消??';
            }

            if (confirm(confirm_value)) {

                var fd = new FormData();

                fd.append('id', id);
                fd.append('status', status);

                $.ajax({
                    type: "post",
                    url: "/admin/product?_method=PUT",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log(response)
                        var response_str = response.split('<CBK>');
                        if (response_str[0] == 'success') {
                            alert('更新成功');
                            parent.window.location.reload();
                        } else {
                            alert(response_str[1]);
                            return;
                        }
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            }
        }

        function changesoftdelete(id, status) {
            if (status == '1') {
                var confirm_value = '确定要删除??';
            } else {
                var confirm_value = '确定不删除??';
            }

            if (confirm(confirm_value)) {

                var fd = new FormData();

                fd.append('id', id);
                fd.append('delete_status', status);

                $.ajax({
                    type: "post",
                    url: "/admin/product?_method=PUT",
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log(response)
                        var response_str = response.split('<CBK>');
                        if (response_str[0] == 'success') {
                            alert('删除成功');
                            parent.window.location.reload();
                        } else {
                            alert(response_str[1]);
                            return;
                        }
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            }
        }


        $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('商品明细')
            // modal.find('.modal-body input').val("<iframe src='/ewminfo/'"+recipient+"' width=\"100%\" height=\"300\" ></iframe>")
            modal.find('.modal-body iframe ').attr("src", '/admin/product/' + recipient)
        })
    </script>
@endsection
@section('main')
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">商品管理</h1>
    <p class="mb-4">
    <div class="form-group right">
        <form class="form" method="get" action="/admin/product" style="font-size:12px;">
            <div class="input-group mb-3 col-md-9" style="margin-top: 20px">
                <select name="query_type" class="custom-select custom-select-sm form-control form-control-sm"
                        style="width: 11%;margin-right: 20px;height: 40px;">
                    <option value="">请选择查询类型</option>
                    <option value="id" {{($searchConditions['query_type']=='id'?"selected":"")}} >商品ID</option>
                    <option value="dtitle" {{($searchConditions['query_type']=='dtitle'?"selected":"")}} >商品标题</option>
                </select>
                <select name="online_status" class="custom-select custom-select-sm form-control form-control-sm"
                        style="width: 11%;height: 40px;">
                    <option value="">请选择审核类型</option>
                    <option value="0" {{($searchConditions['online_status']=='0'?"selected":"")}} >未审核</option>
                    <option value="1" {{($searchConditions['online_status']=='1'?"selected":"")}} >已审核</option>
                </select>
                <input type="text" id="created_at_from" name="created_at_from" class="input-group-text bg-white"
                       placeholder="输入起始资料建立时间" style="height: 40px;margin-left: 20px"
                       value='{{$searchConditions['created_at_from']}}'>

                <input type="text" id="created_at_to" name="created_at_to" class="input-group-text bg-white"
                       placeholder="输入結束资料建立时间" style="height: 40px;margin-left: 20px"
                       value='{{$searchConditions['created_at_to']}}'>

                <input type="hidden" id="row_for_paginate" name="row_for_paginate"
                       value='{{$searchConditions['row_for_paginate']}}'>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        style="margin-left: 20px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span id="show_for_paginate" name="show_for_paginate"
                          class="caret">{{$searchConditions['row_for_paginate']==''?"行數":$searchConditions['row_for_paginate']}}</span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">10</a>
                    <a class="dropdown-item" href="#">30</a>
                    <a class="dropdown-item" href="#">50</a>
                    <a class="dropdown-item" href="#">100</a>
                </div>
            </div>
            <div class="input-group" style="width: 30%;margin-top: 20px;margin-left: 10px">
                <input type="text" name="keyword" class="form-control bg-light border-0 small bg-white"
                       placeholder="输入搜索内容"
                       aria-label="Search" aria-describedby="basic-addon2" value="{{$searchConditions['keyword']}}">
                <div style="margin-left: 40px">
                    <button class="btn btn-primary" type="submit">
                        搜索
                    </button>
                </div>
            </div>
        </form>
    </div>
    </p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap" id="dataTable" width="100%" cellspacing="0"
                       style="font-size:12px"
                       ;>
                    <thead>
                    <tr>
                        <th>商品id</th>
                        <th>商品标题</th>
                        <th>商品类别</th>
                        <th>是否审核</th>
                        <th>原价</th>
                        <th>礼券面额</th>
                        <th>售价</th>
                        <th>累计销量</th>
                        <th>新增时间</th>
                        <th>修改时间</th>
                        <th>动作</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>商品id</th>
                        <th>商品标题</th>
                        <th>商品类别</th>
                        <th>是否审核</th>
                        <th>原价</th>
                        <th>礼券面额</th>
                        <th>售价</th>
                        <th>累计销量</th>
                        <th>新增时间</th>
                        <th>修改时间</th>
                        <th>动作</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach ($productData as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->dtitle}}</td>
                            <td>@if($item->type_id=='0')
                                    未分类
                                @else
                                    {{$item->type_name}}
                                @endif
                            </td>
                            <td>
                                @if($item->is_online=='0')
                                    <p class="text-danger">未审核</p>
                                @else
                                    <p class="text-primary">已审核</p>
                                @endif
                            </td>
                            <td>{{$item->original_price}}</td>
                            <td>{{$item->coupon_value}}</td>
                            <td>{{$item->price}}</td>
                            <td>{{round($item->xiaoliang)}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->updated_at}}</td>
                            <td>
                                <button type="button" class="btn btn-info" data-toggle="modal"
                                        data-target="#exampleModal" data-whatever="{{$item->id}}">
                                    明细
                                </button>
                                @if($item->is_online=='0')
                                    <button type="button" class="btn btn-primary"
                                            onclick="changeonline({{$item->id}},1)">
                                        通过审核
                                    </button>
                                @else
                                    <button type="button" class="btn btn-warning"
                                            onclick="changeonline({{$item->id}},0)">
                                        取消审核
                                    </button>
                                @endif
                                <button type="button" class="btn btn-danger"
                                        onclick="changesoftdelete({{$item->id}},1)">
                                    刪除
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    {{ $productData->appends([
                            'query_type' => $searchConditions['query_type'],
                            'keyword' => $searchConditions['keyword'],
                            'online_status' => $searchConditions['online_status'],
                            'created_at_from' => $searchConditions['created_at_from'],
                            'created_at_to' => $searchConditions['created_at_to'],
                            'row_for_paginate' =>$searchConditions['row_for_paginate']
                            ])->links() }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- 明細內容 -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body " style="height:600px">
                    <iframe src="" frameborder="0" class="embed-responsive-item" allowfullscreen
                            style="width: 100%;height: 100%"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!--明細內容結束-->
@endsection

