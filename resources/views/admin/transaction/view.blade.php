@extends('layout.master_admin')
@section('content')
<section class="content-header">
    <h1>
      View Detai Transaction
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('admin.transaction.index') }}">Transaction</a></li>
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
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%">Thuộc Tính</th>
                                        <th>Giá Trị</th>
                                    </tr>
                                    <tr>
                                        <td>Tên KH</td>
                                        <td><span >{{ $transaction->user->name }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Email KH</td>
                                        <td><span >{{ $transaction->user->email }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Phone KH</td>
                                        <td><span >{{ $transaction->user->phone }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Địa Chỉ KH</td>
                                        <td><span >{{ $transaction->user->address }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
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
                                        <td><span class="badge bg-light-blue">{{ $transaction->getStatus($transaction->tst_status)['name'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Tiền Hàng</td>
                                        <td>
                                            Tổng: {{number_format($transaction->tst_total_money,0,',','.') }} đ
                                            <br>
                                            Đã trả: {{$transaction->tst_total_paid}} đ
                                            <br/>
                                            Còn nợ: {{number_format($transaction->tst_total_money - $transaction->tst_total_paid, 0,',','.')}} đ
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Tiền Vận Chuyển</td>
                                        <td>
                                            Tổng: {{number_format($total_transport, 0,',','.')}} đ
                                            <br>
                                            Đã trả: {{ number_format($transaction->total_transport_paid,0,',','.') }} đ
                                            <br/>
                                            Còn nợ: {{number_format($total_transport - $transaction->total_transport_paid, 0,',','.')}}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Tiền Khách còn nợ </td>
                                        <td>
                                            (Tiền hàng nợ + Tiền vận chuyển nợ:)
                                            <br>
                                            <span class="label label-danger" style="font-size: 15px">
                                                {{number_format(($transaction->tst_total_money - $transaction->tst_total_paid) + ($total_transport - $transaction->total_transport_paid), 0,',','.')}} đ
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ngày Mua Đơn Hàng</td>
                                        <td><span >{{ $transaction->created_at }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header">
                            <h3 class="box-title">Chi Tiết Về Đơn Hàng</h3>
                        </div>
                        <div class="clear-both text-left">
                            <a href="#" class="btn btn-primary add_item_order">+ Thêm sản phẩm</a>
                        </div>
                        <!-- /.box-header -->
                        <form role="form" id="frm_action" name="frm_action" method="post" action="{{route('admin.transaction.update', $transaction->id)}}" enctype="multipart/form-data">
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
                                                <td>{{ $item->product->pro_name ?? "[N\A]" }}</td>
                                                <td class="cls_td " >
                                                    <img src="{{ pare_url_file($item->product->pro_avatar) }}" alt="" width="120px" height="100px">
                                                </td>
                                                <td>{{ number_format($item->od_price,0,',','.') }} đ</td>
                                                <td class="cls_td col-sm-2">
                                                    <input type="number" name="txt_quantity_product[]" class="form-control txt_quantity" value="{{ $item->od_qty }}" min="1" placeholder="SL" required>
                                                </td>
                                                <td>{{ number_format($item->od_price * $item->od_qty,0,',','.') }} đ</td>
                                                <td align="center" class="cls_td">
                                                    <a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="clear-both text-right">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn muốn cập nhật?')">Cập nhật sản phẩm</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header">
                            <div class="clear-both text-center">
                                <a href="#" class="btn btn-primary add_item_bao">+ Thêm số bao</a>
                            </div>
                        </div>
                        <div class="box-body">
                        <form role="form" id="frm_action" name="frm_action" method="post" action="{{route('admin.transaction.update', $transaction->id)}}" enctype="multipart/form-data">
                            @csrf
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-bordered tbl_add_bao ">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th >Tên bao ***</th>
                                            <th >Số cân nặng</th>
                                            <th >Ghi Chú ****************</th>
                                            <th >Giá tiền</th>
                                            <th >Tình trạng</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i=0;
                                    @endphp
                                    @if(!empty($transaction->baos))
                                        @foreach ($transaction->baos as $item)
                                            <tr>
                                                <td>{{ ++$i }} <input type="hidden" value="{{$item->id}}" name="id_bao[]"></td>
                                                <td class="cls_td ">
                                                    <textarea class="form-control" value="" name="b_name[]" rows="3" placeholder="Enter ..." required="">{{$item->b_name}}</textarea></td>
                                                <td class="cls_td ">
                                                    <input type="number" name="b_weight[]" class="form-control" value="{{$item->b_weight}}" required=""></td>
                                                <td class="cls_td ">
                                                    <textarea class="form-control" value="" name="b_note[]" rows="3" placeholder="Enter ...">{{$item->b_note}}</textarea>
                                                </td>
                                                <td class="cls_td ">
                                                    <span>
                                                        @if(empty($item->b_success_date))
                                                            chưa có giá chính thức
                                                        @else
                                                            số cân * giá tiền/1kg <br/>
                                                            {{$item->b_weight}} * {{$item->b_fee}} =
                                                            <span style="color: red">{{number_format($item->b_weight * $item->b_fee,0,',','.') }} đ</span>
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="cls_td ">
                                                    <input type="checkbox" name="b_success_date[]" data-url="{{route('admin.transaction.update.success.date', $item->id)}}" id="update_success_date" class="form-check-input" {{ empty($item->b_success_date) == true ? '' : 'checked' }} >
                                                    @if(empty($item->b_success_date) == true)
                                                        Chưa giao
                                                    @else
                                                        Đã giao
                                                    @endif
                                                </td>
                                                <td align="center" class="cls_td">
                                                    <a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="clear-both text-center">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn muốn cập nhật?')">Cập nhật số lượng bao</button>
                            </div>
                        </form>
                        </div>
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
                                    @if(!empty($transaction->transaction_histories))
                                        @foreach ($transaction->transaction_histories as $item)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $item->th_content }}</td>
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
                        <a href="{{ route('admin.transaction.index') }}" class="btn btn-danger"><i class="fa fa-undo"></i> Trở Lại</a>
                        {{-- <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Submit</button> --}}
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
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="close_modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
  <!-- /.content -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css"  />
<script src="https://code.jquery.com/jquery-3.2.1.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js" ></script>
@endsection

@section('script')
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

            $(document).on('click','#update_success_date',function(e){
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
                                $data.parent().prev().html(results.price_bao)
                                $this.parent().html(results.success_date)
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
                var row_infor = '<tr><td></td><td><input type="button" value="CLICK" id="get_products" data-url-products="{{route('admin.product.index')}}"><input type="hidden" name="txt_id_product[]"></td><td><span></span></td><td class="cls_td " ><img src="{{ pare_url_file(null) }}" alt="" width="120px" height="100px"></td><td><span></span></td><td class="cls_td col-sm-2"><input type="number" name="txt_quantity_product[]" class="form-control txt_quantity" value="" min="1" placeholder="SL" required></td><td></td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
                $(".tbl_add_orderext tbody").append(row_infor);
                return false;
            });

            $(".add_item_bao").on("click", function() {
                var row_infor = '<tr> <td></td><td class="cls_td "><textarea class="form-control" value="" name="b_name[]" rows="3" placeholder="Enter ..." required=""></textarea></td><td class="cls_td "><input type="number" name="b_weight[]" class="form-control" value="" required=""></td><td class="cls_td "><textarea class="form-control" value="" name="b_note[]" rows="3" placeholder="Enter ..."></textarea></td><td class="cls_td "><span></span></td><td class="cls_td "><input type="checkbox" name="b_success_date[]" class="form-check-input" >Chưa giao</td><td class="cls_td "><input type="checkbox" name="b_status[]" class="form-check-input">chưa thanh toán</td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
                $(".tbl_add_bao tbody").append(row_infor);
                return false;
            });

        });
        function deleteItem(thistag){
            if(confirm("Bạn chắc chắn muốn xóa item này?")==true){
                $(thistag).parent().parent().remove();
            }
        }
    </script>
@endsection
