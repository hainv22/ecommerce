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
                        <form action="" method="post">
                            @csrf
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
                                    <tr>
                                        <td>Ngày đặt hàng</td>
                                        <td>
                                            <span >{{ date("d-m-Y", strtotime($transaction->tst_order_date )) }}</span>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                <input type="date" name="tst_order_date" class="form-control" value="{{ old('tst_order_date') }}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ngày dự kiến nhận</td>
                                        <td>
                                            <span >{{ date("d-m-Y", strtotime($transaction->tst_expected_date )) }}</span>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                <input type="date" name="tst_expected_date" class="form-control" value="{{ old('tst_expected_date') }}">
                                            </div>
                                        </td>
                                        <td>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>content</td>
                                        <td>
                                            <div class="form-group {{ $errors->first('tst_note') ? 'has-error' : '' }}">
                                                <textarea class="form-control" value="" name="tst_note" rows="6" placeholder="Enter ...">{{ $transaction->tst_note}}</textarea>
                                                @if ($errors->first('tst_note'))
                                                    <span class="text-danger">{{ $errors->first('tst_note') }}</span>
                                                @endif
                                            </div>
                                            <div class="clear-both text-right">
                                                <button type="submit" class="btn btn-danger" >Cập nhật sản phẩm</button>
                                            </div>
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
                                        <td><span class="badge bg-light-blue">{{ $transaction->getStatus($transaction->tst_status)['name'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Tiền Hàng</td>
                                        <td>
                                            <button type="button" class="btn btn-success" id="decrementPrice_js">+</button>
                                            <button type="button" class="btn btn-warning" id="js_tst_deposit" data-url-convert="{{route('admin.transaction.convert.deposit', $transaction->id)}}" style="float: right">Chuyển</button>
                                            <br/>
                                            Tổng: <span class="js_tst_money">{{number_format($transaction->tst_total_money,0,',','.') }}</span> đ
                                            <br>
                                            Đã trả: <span class="js_tst_total_paid">{{number_format($transaction->tst_total_paid,0,',','.')}}</span> đ
                                            <br/>
                                            Đặt cọc: <span class="js_tst_deposit">{{ number_format($transaction->tst_deposit,0,',','.') }}</span> đ
                                            <br/>
                                            Còn nợ: <span class="js_tst_money_verb">{{number_format($transaction->tst_total_money - $transaction->tst_total_paid, 0,',','.')}} đ</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Tiền Vận Chuyển</td>
                                        <td>
                                            <button type="button" class="btn btn-success" id="decrementPrice_transport_js">+</button>
                                            <br/>
                                            Tổng: <span class="js_total_transport">{{number_format($total_transport, 0,',','.')}}</span> đ
                                            <br>
                                            Đã trả: <span class="js_total_transport_paid">{{ number_format($transaction->total_transport_paid,0,',','.') }}</span> đ
                                            <br/>
                                            Còn nợ: <span class="js_total_transport_verb">{{number_format($total_transport - $transaction->total_transport_paid, 0,',','.')}}</span> đ
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Tiền Khách còn nợ </td>
                                        <td>
                                            (Tiền hàng nợ + Tiền vận chuyển nợ:)
                                            <br>
                                            <span class="label label-danger js_total_money_verb" style="font-size: 15px">
                                                {{number_format(($transaction->tst_total_money - $transaction->tst_total_paid - $transaction->tst_deposit) + ($total_transport - $transaction->total_transport_paid), 0,',','.')}} đ
                                            </span> đ
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
                                                <td>{{ $item->product->pro_name ?? "[N\A]" }}</td>
                                                <td class="cls_td " >
                                                    <img src="{{ pare_url_file($item->product->pro_avatar) }}" alt="" width="120px" height="100px">
                                                </td>
                                                <td>{{ number_format($item->od_price,0,',','.') }} đ</td>
                                                <td class="cls_td col-sm-2">
                                                    <input type="number" name="txt_quantity_product[]" class="form-control txt_quantity" value="{{ $item->od_qty }}" min="1" placeholder="SL" required>
                                                </td>
                                                <td>{{ number_format($item->od_price * $item->od_qty,0,',','.') }} đ</td>
                                                <td>
                                                    <textarea class="form-control" value="" name="od_note[]" rows="3" placeholder="Enter ..." >{{$item->od_note}}</textarea>
                                                </td>
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
                                            <th >Vận chuyển *****</th>
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
                                                    <select name="b_transport_id[]" class="form-control" id="js_b_transport_id" data-url-update-transport="{{route('admin.update.transport.id.bao', $item->id)}}">
                                                        <?php if(isset($transports)) { ?>
                                                            <?php foreach($transports as $transport) { ?>
                                                                <option {{ $item->b_transport_id==$transport->id ? 'selected' :''}} value="{{$transport->id}}">{{$transport->tp_name}}</option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>

                                                </td>
                                                <td class="cls_td ">
                                                    <span>
                                                        @if(empty($item->b_success_date))
                                                            {{$item->transport->tp_fee}} đ / 1kg
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
                var row_infor = '<tr><td></td><td><input type="button" value="CLICK" id="get_products" data-url-products="{{route('admin.product.index')}}"><input type="hidden" name="txt_id_product[]" value="" ></td><td></td><td class="cls_td " ><img src="{{ pare_url_file(null) }}" alt="" width="120px" height="100px"></td><td></td><td class="cls_td col-sm-2"><input type="number" name="txt_quantity_product[]" class="form-control txt_quantity" value="" min="1" placeholder="SL" required></td><td></td><td><textarea class="form-control" value="" name="od_note[]" rows="3" placeholder="Enter ..." ></textarea></td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
                $(".tbl_add_orderext tbody").append(row_infor);
                return false;
            });

            $(".add_item_bao").on("click", function() {
                var row_infor = '<tr> <td></td><td class="cls_td "><textarea class="form-control" value="" name="b_name[]" rows="3" placeholder="Enter ..." required="">{{$item->b_name}}</textarea></td><td class="cls_td "><input type="number" name="b_weight[]" class="form-control" value="{{$item->b_weight}}" required=""></td><td class="cls_td "><textarea class="form-control" value="" name="b_note[]" rows="3" placeholder="Enter ...">{{$item->b_note}}</textarea></td><td class="cls_td "><select name="b_transport_id[]" class="form-control" id="js_b_transport_id" data-url-update-transport="{{route('admin.update.transport.id.bao', $item->id)}}"><?php if(isset($transports)) { ?><?php foreach($transports as $transport) { ?><option {{ $item->b_transport_id==$transport->id ? 'selected' :''}} value="{{$transport->id}}">{{$transport->tp_name}}</option><?php } ?><?php } ?></select></td><td class="cls_td "><span></span></td><td class="cls_td "><input type="checkbox" name="b_success_date[]" data-url="{{route('admin.transaction.update.success.date', $item->id)}}" id="update_success_date" class="form-check-input" {{ empty($item->b_success_date) == true ? '' : 'checked' }} ></td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
                $(".tbl_add_bao tbody").append(row_infor);
                return false;
            });

            $(document).on('change','#js_b_transport_id',function(e){
                let $this = $(this);
                let id_bao = this.value;
                let URL = $this.attr('data-url-update-transport');
                if(URL){
                    $.ajax({
                        url:URL,
                        data:{
                            id_bao:id_bao
                        },
                        success:function(results){
                            console.log(results.price_bao)
                            $this.parent().next().html(results.price_bao)
                            toastr.success('Cập nhật thành công');
                        },
                        error:function(error){
                            console.log(error.messages);
                        }
                    });
                }
            })




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
            $(document).on('keyup', '#js_money_pay', function (e) {
                nStr = $(this).val();
                decSeperate = '.';
                groupSeperate = ',';
                nStr += '';
                x = nStr.split(decSeperate);
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
                }
                $("#js_money_pay_format").text(x1+x2 + ' đ')
            })












            $(document).on('click','#decrementPrice_transport_js',function(e){
                $("#decrementPriceTransport").modal("show");
            });
            $(document).on('click','#js_save_transport_pay',function(e){
                e.preventDefault();
                let $this = $(this);
                let value = $('#js_transport_pay').val();
                if(value == '' || value < 0) {
                    toastr.error('Giá trị phải lớn hơn 1');
                    return false;
                }
                let URL = $this.attr('data-url-update-money-transport');
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
                            $('#decrementPriceTransport').modal('hide');
                            // $('#body_list_products').html(results.data);
                            // $data_this = $this;
                        },
                        error:function(error){
                            console.log(error.messages);
                        }
                    });
                }
            });
            $(document).on('keyup', '#js_transport_pay', function (e) {
                nStr = $(this).val();
                decSeperate = '.';
                groupSeperate = ',';
                nStr += '';
                x = nStr.split(decSeperate);
                x1 = x[0];
                x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
                }
                $("#js_transport_pay_format").text(x1+x2 + ' đ')
            })

        });

        $('#js_tst_deposit').click(function(event){
            event.preventDefault();
            let URL=$(this).attr('data-url-convert');
            $.confirm({
                title: ' Bạn muốn chuyển tiền đặt cọc',
                content: 'Chuyển tiền cọc sang tiền khách hàng trả!',
                type: 'red',
                buttons: {
                    ok: {
                        text: "ok!",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            if(URL){
                                $.ajax({
                                    url:URL,
                                    success:function(results){
                                        console.log(results.code)
                                        if(results.code == 200) {
                                            location.reload();
                                            toastr.success(results.message);
                                        } else {
                                            toastr.error(results.message);
                                        }
                                    },
                                    error:function(error){
                                        console.log(error.messages);
                                    }
                                });
                            }
                        }
                    },
                    cancel: function(){
                        console.log('the user clicked cancel');
                    }
                }
            });
        })

        function deleteItem(thistag){
            if(confirm("Bạn chắc chắn muốn xóa item này?")==true){
                $(thistag).parent().parent().remove();
            }
        }
    </script>
@endsection
