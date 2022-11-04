@extends('layout.master_admin')
@section('content')
    <section class="content-header">
        <h1>
            Owner China
            <small>Detail Owner China</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.owner-china.index') }}">Owner China</a></li>
            <li class="active">Details</li>

        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <form role="form" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Thông tin cơ bản</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" disabled  name="name" class="form-control" value="{{ $owner->oc_name }}">
                            </div>

                            <div class="form-group">
                                <label for="email">Total</label>
                                <input type="text" disabled  name="email" class="form-control" value="{{number_format($owner->oc_total_money,0,',','.') }}">
                            </div>

                            <div class="form-group ">
                                <label for="pro_name">Total VND</label>
                                <input type="text" disabled  name="address" class="form-control" value="{{number_format((0),0,',','.') }}">
                            </div>

                            <div class="form-group ">
                                <label for="pro_name">Date-paid</label>
                                <input type="text" disabled  name="address" class="form-control" value="{{ $owner->updated_at }}">
                            </div>


                        </div>
                    </div>
                    <a href="{{ route('admin.owner-china-transactions.index') }}?user_owner_id={{$owner->id}}" class="btn btn-warning"><i class="fa fa"></i> Click để xem danh sách đơn hàng nha {{$owner->oc_name}}</a>

                </div>


                <div class="col-md-6">

                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Lịch sử cộng trừ 3 tháng gần nhất </h3>
                        </div>
                        <div class="box-body">
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <th>ID -- STT</th>
                                        <th>Trước</th>
                                        <th>Trả / Mua</th>
                                        <th>Sau</th>
                                        <th>content</th>
                                        <th>Ngày</th>
                                    </tr>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @if(isset($use))
                                        @foreach ($use as $item)
                                            <tr>
                                                <td>{{ $item->id .'--'.  ++$i}}</td>
                                                <td>{{number_format($item->cmh_money_before,0,',','.')}} NDT</td>
                                                <td>{{number_format($item->cmh_money,0,',','.')}} NDT * {{number_format($item->cmh_yuan,0,',','.')}} ({{number_format(abs($item->cmh_money*$item->cmh_yuan),0,',','.') . ' VND'}})</td>
                                                <td>{{number_format($item->cmh_money_after,0,',','.')}} NDT</td>
                                                <td>
                                                    {{$item->cmh_content}}
                                                    @if($item->cmh_owner_transaction_id != 9999999999)
                                                        <a target="_blank" href="{{route('admin.owner-china-transactions.detail', $item->cmh_owner_transaction_id)}}">(Click)</a>
                                                    @endif
                                                </td>
                                                <td>{{ $item->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if(\Auth::user()->role == \App\Models\User::ADMIN)
                        <button type="button" class="btn btn-warning" id="js_owner_china_paid" data-url-paid-owner="{{route('admin.owner-china.paid-owner', $owner->id	)}}" style="float: right">Paid</button>
                    @endif
                </div>

                {{--<div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Thông Tin Khách Hàng</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">

                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box-footer" style="text-align: center;">
                        <a href="{{ route('admin.owner-china.index') }}" class="btn btn-danger"><i class="fa fa-undo"></i> Trở Lại</a>
                    </div>
                </div>--}}
            </form>
        </div>
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css"  />
    <script src="https://code.jquery.com/jquery-3.2.1.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js" ></script>

    <div class="modal fade" id="paid-owner-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Paid</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="pro_price">Số tiền trả: </label><span id="" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="number" name="js_money_paid_owner-name" id="js_money_paid_owner" class="form-control txt_quantity" value="" min="1" placeholder="money" required="">
                </div>
                <div class="modal-body">
                    <label for="pro_price">Nội dung : </label><span id="" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="text" name="js_content_paid_owner-name" id="js_content_paid_owner" class="form-control txt_quantity" value="" min="1" placeholder="money" required="">
                </div>
                <div class="modal-body">
                    <label for="pro_price">Giá tiền Trung Tại Thời Điểm Này : </label><span id="" style="color: red; font-size: 15px; margin-left:15px"></span>
                    <input type="text" name="js_yuan_paid_owner-name" maxlength="4"  id="js_yuan_paid_owner" class="form-control txt_quantity" value="" min="1" placeholder="money" required="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="js_save_owner_paid" data-url-paid-owner="{{route('admin.owner-china.paid-owner', $owner->id)}}">Paid</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on('click','#js_owner_china_paid',function(e){
            $("#paid-owner-model").modal("show");
        });
        $(document).on('click','#js_save_owner_paid',function(e){
            e.preventDefault();
            let $this = $(this);
            let money_paid = $('#js_money_paid_owner').val();
            let content_paid = $('#js_content_paid_owner').val();
            let yuan_paid = $('#js_yuan_paid_owner').val();
            console.log(money_paid, content_paid, yuan_paid)
            if(money_paid == '' || money_paid < 0 || yuan_paid == '' || yuan_paid < 0) {
                toastr.error('Giá trị phải lớn hơn 1');
                return false;
            }
            let URL = $this.attr('data-url-paid-owner');
            if(URL){
                $.ajax({
                    url:URL,
                    type:"GET",
                    data:{
                        money_paid: money_paid,
                        content_paid: content_paid,
                        yuan_paid: yuan_paid
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
    </script>
@endsection
