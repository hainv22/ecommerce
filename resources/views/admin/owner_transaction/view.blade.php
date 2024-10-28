@extends('layout.master_admin')
@section('content')
    <section class="content-header">
        <h1>
            Detai Owner-Transaction
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.owner-china-transactions.index') }}">Owner Transaction</a></li>
            <li class="active">Edit</li>

        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            @csrf
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Thông Tin Khách Hàng</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <form action="" method="post">
                            @csrf
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <th style="width: 30%">Thuộc Tính</th>
                                    <th>Giá Trị</th>
                                </tr>
                                <tr>
                                    <td>Tên Chủ</td>
                                    <td><span >{{ $transaction->owner->oc_name }}</span> <br>
                                        <a href="{{ route('admin.owner-china.detail',$transaction->owner->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i>View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ngày đặt hàng</td>
                                    <td>
                                        <span >{{ date("d-m-Y", strtotime($transaction->ot_order_date )) }}</span>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                            <input type="date" name="ot_order_date" class="form-control" value="{{ old('ot_order_date') }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>content</td>
                                    <td>
                                        <div class="form-group {{ $errors->first('ot_note') ? 'has-error' : '' }}">
                                            <textarea class="form-control" value="" name="ot_note" rows="6" placeholder="Enter ...">{{ $transaction->ot_note}}</textarea>
                                            @if ($errors->first('ot_note'))
                                                <span class="text-danger">{{ $errors->first('ot_note') }}</span>
                                            @endif
                                        </div>
                                        @if(\Auth::user()->role == \App\Models\User::ADMIN)
                                        <div class="clear-both text-right">
                                            <button type="submit" class="btn btn-danger {{$transaction->tst_lock == 1 ? 'js_click_lock' : ''}}" >Cập nhật thông tin</button>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Thông Tin Thêm Về Đơn Hàng</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <th style="width: 40%">Thuộc Tính</th>
                                <th>Giá Trị</th>
                            </tr>
                            <tr>
                                <td>Trạng Thái</td>
                                <td>
                                    <?php
                                    $check = true;
                                    foreach ($transaction->detail as $key => $value) {
                                        if($value->otd_status == 1) {
                                            $check = false;
                                            break;
                                        }
                                    }
                                    ?>
                                    @if($check)
                                        <span class="label label-success">
                                                Đã về đến kho hết
                                            </span>
                                    @else
                                        <span class="label label-danger">
                                                Có mẫu chưa về
                                            </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Tiền Hàng</td>
                                <td>
                                    Tổng: <span class="js_tst_money">{{number_format($transaction->ot_total_money,0,',','.') }}</span> NDT
                                </td>
                            </tr>
                            <tr>
                                <td>Ngày tạo đơn</td>
                                <td><span >{{ $transaction->created_at }}</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header">
                        <div class="clear-both text-center">
                            <a href="#" class="btn btn-primary add_item_bao">+ Thêm số bao</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <form role="form" id="frm_action" name="frm_action" method="post" action="" enctype="multipart/form-data">
                            @csrf
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-bordered tbl_add_bao ">
                                    <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th >Số cân nặng</th>
                                        <th >Ghi Chú</th>
                                        <th>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i=0;
                                    @endphp
                                    @if(!empty($transaction->ownerBaos))
                                        @foreach ($transaction->ownerBaos as $item)
                                            <tr>
                                                <td>{{ ++$i }} <input type="hidden" value="{{$item->id}}" name="id_bao[]"></td>
                                                <td class="cls_td ">
                                                    <input type="number" name="b_weight[]" class="form-control" value="{{$item->b_weight}}" required="">
                                                </td>
                                                <td class="cls_td ">
                                                    <textarea class="form-control" value="" name="b_note[]" rows="3" placeholder="Enter ...">{{$item->b_note}}</textarea>
                                                </td>
                                                <td align="center" class="cls_td">
                                                    <a href="#" class="btn_action btn_del" onclick="{{$transaction->tst_lock == 1 ? 'js_click_lock' : "deleteItem(this);return false;"}}"><i class="fa fa-trash-o fa_user fa_del"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            @if(\Auth::user()->role == \App\Models\User::ADMIN)
                            <div class="clear-both text-center">
                                <button type="submit" class="btn btn-danger {{$transaction->tst_lock == 1 ? 'js_click_lock' : ''}}" onclick="{{$transaction->tst_lock == 1 ? '' : "return confirm('Bạn muốn cập nhật?')"}}">Cập nhật số lượng bao</button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Chi Tiết Về Đơn Hàng</h3>
                    </div>
                    <div class="clear-both text-left">
                        <a href="#" class="btn btn-primary  {{$transaction->tst_lock == 1 ? 'js_click_lock' : 'add_item_order'}}">+ Thêm sản phẩm</a>
                    </div>
                    <!-- /.box-header -->
                    <form role="form" id="frm_action" name="frm_action" method="post" action="" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body no-padding table-responsive">
                            <table class="table table-condensed tbl_add_orderext">
                                <tbody>
                                <tr>
                                    <th style="width: 75px;">STT</th>
                                    <th style="width: 75px;">ID</th>
                                    <th>Name</th>
                                    <th>Avatar</th>
                                    <th>Price</th>
                                    <th>Số lượng ***</th>
                                    <th>Total</th>
                                    <th>Ghi Chú ****</th>
                                    <th >Action</th>
                                </tr>
                                @php
                                    $i=0;
                                @endphp
                                @foreach ($order as $item)
                                    <tr>
                                        <td>{{ ++$i }} <input type="hidden" value="{{$item->id}}" name="product_ids[]"></td>
                                        <td>
                                            <span style="color: red; font-size: 15px">{{ $item->product->id }}</span>
                                            <input type="hidden" name="txt_id_product[]" value="{{ $item->product->id }}" >
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.product.update',$item->product->id) }}">{{ $item->product->pro_name ?? "[N\A]" }}</a><br>
                                            (số lượng còn: {{$item->product->pro_number ?? 0}})
                                        </td>
                                        <td class="cls_td " >
                                            <img src="{{ pare_url_file($item->product->pro_avatar) }}" alt="" width="120px" height="100px">
                                        </td>
                                        <td>{{ number_format($item->otd_price,2,',','.') }} NDT</td>
                                        <td class="cls_td col-sm-2">
                                            <input type="number" name="txt_quantity_product[]" class="form-control txt_quantity" value="{{ $item->otd_qty }}" min="1" placeholder="SL" required>
                                        </td>
                                        <td>{{ number_format($item->otd_price * $item->otd_qty,0,',','.') }} NDT</td>
                                        <td>
                                            <textarea class="form-control" value="" name="otd_note[]" rows="3" placeholder="Enter ..." >{{$item->otd_note}}</textarea>
                                        </td>
                                            <td align="center" class="cls_td">
                                                <input @if(\Auth::user()->role != \App\Models\User::ADMIN) disabled @endif type="checkbox" name="otd_success_date[]" data-url="{{route('admin.owner-china-transactions.update.success.date', $item->id)}}" id="update_success_date_otd" class="form-check-input" {{ $item->otd_status == 1 ? '' : 'checked' }} >
                                                @if($item->otd_status == 1)
                                                    Chưa về
                                                @else
                                                    Đã giao
                                                @endif
                                                <a href="#" class="btn_action btn_del {{$transaction->tst_lock == 1 ? 'js_click_lock' : ''}}" onclick="{{$transaction->tst_lock == 1 ? 'js_click_lock' : "deleteItem(this);return false;"}}"><i class="fa fa-trash-o fa_user fa_del"></i></a>
                                            </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(\Auth::user()->role == \App\Models\User::ADMIN)
                            <div class="clear-both text-right">
                                <button type="submit" class="btn btn-danger {{$transaction->tst_lock == 1 ? 'js_click_lock' : ''}}" onclick=" {{$transaction->tst_lock == 1 ? '' : "return confirm('Bạn muốn cập nhật?')"}} ">Cập nhật sản phẩm</button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Thông Tin Thêm Về Đơn Hàng</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="box-body no-padding table-responsive">
                            <table class="table table-condensed tbl_add_orderext">
                                <tbody>
                                <tr>
                                    <th style="width: 75px;">STT</th>
                                    <th>Nội dung lịch sử</th>
                                    <th>Ngày Tạo lịch sử</th>
                                </tr>
                                @php
                                    $i=0;
                                @endphp
                                @if(!empty($transaction->changeMoneyOwnerHistories))
                                    @foreach ($transaction->changeMoneyOwnerHistories as $item)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>
                                                +/- so tien: {{ $item->	cmh_money }} <br>
                                                so tien sau khi +/-: {{ $item->cmh_money_after }} <br>
                                                cmh_content: {{ $item->cmh_content }} <br>
                                            </td>
                                            <td>{{ date("d/m/Y H:i:s", strtotime($item->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="box-footer" style="text-align: center;">
                    <a href="{{ route('admin.owner-china-transactions.index') }}" class="btn btn-info"><i class="fa fa-undo"></i> Trở Lại</a>
                </div>
            </div>
        </div>
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
    </section>
    <div id="myModal" class="modal fade col-md-12" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                    <input type="text" name="table_search" class="form-control pull-right ajax-search-product" placeholder="name hoặc giá" data-url="{{ route('admin.product.index') }}">
                </div>
                <div class="modal-body" id="data_modal">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">id sản phẩm</th>
                                <th scope="col">name</th>
                                <th scope="col">image</th>
                                <th scope="col">price</th>
                                <th scope="col">action</th>
                            </tr>
                            </thead>
                            <tbody id="body_list_products">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.product.create') }}" target="_blank" class="btn btn-danger"><i class="fa fa-save"></i> Create</a>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="close_modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="decrementPrice" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Khách trả thêm tiền hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="pro_price">Số tiền hàng trả: </label><span id="js_money_pay_format" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="number" name="js_money_pay" id="js_money_pay" class="form-control txt_quantity" value="" min="1" placeholder="money" required="">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="js_save_money_pay" data-url-update-money="{{route('admin.transaction.update.money', $transaction->id)}}">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="decrementPriceTransport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Khách trả tiền vận chuyển</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="pro_price">Số tiền vận chuyển trả: </label><span id="js_transport_pay_format" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="number" name="js_transport_pay" id="js_transport_pay" class="form-control txt_quantity" value="" min="1" placeholder="money" required="">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="js_save_transport_pay" data-url-update-money-transport="{{route('admin.transaction.update.money.transport', $transaction->id)}}">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css"  />
    <script src="https://code.jquery.com/jquery-3.2.1.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js" ></script>
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $('.js-action-confirm').click(function(event){
                event.preventDefault();
                let URL=$(this).attr('href');
                $.confirm({
                    title: ' Bạn Muốn Chuyển trạng thái chứ ?',
                    content: 'Đã chuyển là không quay lại được đâu !',
                    type: 'red',
                    buttons: {
                        ok: {
                            text: "ok!",
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function(){
                                window.location.href=URL;
                            }
                        },
                        cancel: function(){
                            console.log('the user clicked cancel');
                        }
                    }
                });
            })
        });

        $(document).ready(function(){
            $(document).on('click','#js-notification',function(e){
                e.preventDefault();
                // $('.total-message').html(0);
                // console.log(1);
            });
        });

    </script>
    <script>
        $(function(){
            let $data_this;
            let $data;
            $(document).on('click','#get_products',function(e){
                $("#myModal").modal("show");
                $("#myModal .modal-title").html("Quy định và chính sách khi tạo đơn hàng");
                $("#myModal .modal-dialog").addClass("modal-lg");
                let $this = $(this);
                let URL = $this.attr('data-url-products');
                if(URL){
                    $.ajax({
                        url:URL,
                        type:"GET",
                        data:{
                            transaction_get_products: 1
                        },
                        success:function(results){
                            $('#body_list_products').html(results.data);
                            $data_this = $this;
                        },
                        error:function(error){
                            console.log(error.messages);
                        }
                    });
                }
                return false;
            });

            $(document).on('click','#update_success_date_otd',function(e){
                let $this = $(this);
                let URL = $this.attr('data-url');
                let value = $this.is(":checked");
                console.log(value);
                if(URL){
                    $.ajax({
                        url:URL,
                        type:"GET",
                        data:{
                            value: value
                        },
                        success:function(results){
                            $data = $this;
                            if(results.data == 'success') {
                                $this.parent().html(results.success)
                                toastr.success('Cập nhật thành công');
                            }
                        },
                        error:function(error){
                            console.log(error.messages);
                        }
                    });
                }
            });


            $(document).on('click','#click_get_id_product',function(e){
                let $this = $(this);
                let ID = $this.attr('data-id-product');
                let SRC = $this.attr('data-url-image');
                let PRICE = $this.attr('data-price');
                let NAME = $this.attr('data-name');
                $data_this.val(ID)
                $data_this.next().val(ID)
                $data_this.css("font-size", "15px")
                $data_this.css("color", "red")
                $data_this.parent().next().next().children().attr("src", SRC);
                $data_this.parent().next().children().text(NAME);
                $data_this.parent().next().next().next().children().text(PRICE + ' đ');
                $('#myModal').modal('toggle');
            });

            $(".add_item_order").on("click", function() {
                var row_infor = '<tr><td></td><td><input type="button" value="CLICK" id="get_products" data-url-products="{{route('admin.owner-china-transactions.get.products')}}"><input type="hidden" name="txt_id_product[]" value="" ></td><td></td><td class="cls_td " ><img src="{{ pare_url_file(null) }}" alt="" width="120px" height="100px"></td><td></td><td class="cls_td col-sm-2"><input type="number" name="txt_quantity_product[]" class="form-control txt_quantity" value="" min="1" placeholder="SL" required></td><td></td><td><textarea class="form-control" value="" name="otd_note[]" rows="3" placeholder="Enter ..." ></textarea></td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
                $(".tbl_add_orderext tbody").append(row_infor);
                return false;
            });

            $(".add_item_bao").on("click", function() {
                var row_infor = '<tr> <td></td><td class="cls_td"><input type="number" name="b_weight[]" class="form-control" value="{{$item->b_weight}}" required="" /></td><td class="cls_td"><textarea class="form-control" value="" name="b_note[]" rows="3" placeholder="Enter ...">{{$item->b_note}}</textarea></td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
                $(".tbl_add_bao tbody").append(row_infor);
                return false;
            });

            $(document).on('click','#decrementPrice_js',function(e){
                $("#decrementPrice").modal("show");
            });
            $(document).on('click','#js_save_money_pay',function(e){
                e.preventDefault();
                let $this = $(this);
                let value = $('#js_money_pay').val();
                if(value == '' || value < 0) {
                    toastr.error('Giá trị phải lớn hơn 1');
                    return false;
                }
                let URL = $this.attr('data-url-update-money');
                if(URL){
                    $.ajax({
                        url:URL,
                        type:"GET",
                        data:{
                            value: value
                        },
                        success:function(results){
                            console.log(results)
                            if(results.code == 200) {
                                location.reload();
                                toastr.success(results.message);
                            } else {
                                toastr.error(results.message);
                            }
                            $('#decrementPrice').modal('hide');
                            // $('#body_list_products').html(results.data);
                            // $data_this = $this;
                        },
                        error:function(error){
                            console.log(error.messages);
                        }
                    });
                }
            });

            $('.js_click_lock').click(function(event){
                event.preventDefault();
                $.confirm({
                    title: 'Đơn hàng đang bị khóa',
                    content: 'Hãy mở khóa đơn hàng để thao tác!',
                    type: 'red',
                    buttons: {
                        ok: {
                            text: "ok!",
                            btnClass: 'btn-primary',
                            keys: ['enter']
                        }
                    }
                });
            })

        });

        function deleteItem(thistag){
            if(confirm("Bạn chắc chắn muốn xóa item này?")==true){
                $(thistag).parent().parent().remove();
            }
        }

        $(document).ready(function(){
            $('body').on('click','.pagination a',function(e){
                e.preventDefault();
                $('li').removeClass('active');
                $(this).parent('li').addClass('active');
                var URL = $(this).attr('href');
                getPosts(URL);
            });
            function getPosts(URL){
                $.ajax({
                    url:URL,
                    type:"GET",
                    data:{
                        transaction_get_products: 1
                    },
                    success:function(results){
                        $('#body_list_products').html(results.data);
                    },
                    error:function(error){
                        console.log(error.messages);
                    }
                });
            }

            $(document).on('keyup','.ajax-search-product',function(e){
                e.preventDefault();
                var URL = $(this).attr('data-url');
                var res = $(this).val();
                $.ajax({
                    url:URL,
                    type:"GET",
                    data:{name:res,transaction_get_products: 1},
                    success:function(results){
                        $('#body_list_products').html(results.data);
                    }
                });
            });
        });
    </script>
@endsection
