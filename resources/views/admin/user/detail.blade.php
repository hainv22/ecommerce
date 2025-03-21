@extends('layout.master_admin')
@section('content')
    <section class="content-header">
        <h1>
            User
            <small>Detail User</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.user.index') }}">User</a></li>
            <li class="active">Details</li>

        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <form role="form" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-md-7">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Thông tin cơ bản</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group {{ $errors->first('name') ? 'has-error' : '' }}">
                                <label for="name">Name</label>
                                <input type="text" disabled  name="name" class="form-control" value="{{ $user->name }}"  placeholder="Name ....">
                                @if ($errors->first('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('email') ? 'has-error' : '' }}">
                                <label for="email">Email</label>
                                <input type="text" disabled  name="email" class="form-control" value="{{ $user->email }}"  placeholder="Email ....">
                                @if ($errors->first('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('phone') ? 'has-error' : '' }}">
                                <label for="pro_name">Phone</label>
                                <input type="text" disabled  name="phone" class="form-control" value="{{ $user->phone }}"  placeholder="Số điện thoại ....">
                                @if ($errors->first('phone'))
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('address') ? 'has-error' : '' }}">
                                <label for="pro_name">Address</label>
                                <input type="text" disabled  name="address" class="form-control" value="{{ $user->address }}"  placeholder="Nhập địa chỉ khách hàng ....">
                                @if ($errors->first('address'))
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                @endif
                            </div>


                        </div>
                    </div>
                </div>


                <div class="col-md-5">

                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ảnh Đại Diện</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Ảnh Mới</label>
                                <div style="margin-bottom:10px" >
                                    <img id="image_preview_container" src="{{ asset('admin/product.jpg') }}" class="img-thumbnail" style="width: 220px;height:200px" alt=""></div>
                                <input type="file" name="avatar" id="image"  class="js-upload">
                                @if ($errors->first('avatar'))
                                    <span class="text-danger">{{ $errors->first('avatar') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
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
                                        <td>Danh sách đơn hàng khách đặt</td>
                                        <td>
                                            <a href="{{ route('admin.transaction.index') }}?user_id={{$user->id}}" class="btn btn-warning"><i class="fa fa"></i> Click để xem danh sách đơn hàng khác đã đặt</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tất cả Tiền hàng của khách</td>
                                        <td>
                                            Tổng số tiền Khách đã chi vào cửa hàng:
                                            <span class="js_tst_money">{{number_format($total_money_user,0,',','.') }}</span> đ
                                            <br>
                                            Tổng số tiền khách đã trả:
                                            <span class="js_tst_total_paid">{{number_format($total_money_paid,0,',','.')}}</span> đ
                                            <br/>
                                            Tổng số khách Còn nợ:
                                            <span class="js_tst_money_verb">{{number_format($total_money_user - $total_money_paid, 0,',','.')}} đ</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tất cả Tiền vận chuyển</td>
                                        <td>
                                            Tổng số tiền Khách đã chi vào cửa hàng:
                                            <span class="js_tst_money">{{number_format($total_transport,0,',','.') }}</span> đ
                                            <br>
                                            Tổng số tiền khách đã trả:
                                            <span class="js_tst_total_paid">{{number_format($total_money_transport_paid,0,',','.')}}</span> đ
                                            <br/>
                                            Tổng số khách Còn nợ:
                                            <span class="js_tst_money_verb">{{number_format($total_transport - $total_money_transport_paid, 0,',','.')}} đ</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Tiền lãi sơ bộ</td>
                                        <td>
                                            <span style="color: red;"> Tổng: </span><span class="js_tst_money">{{number_format($tst_interest_rate_total,0,',','.') }}</span> đ <br>
                                            <span style="color: red;"> 2023: </span><span class="js_tst_money">{{number_format($tst_interest_rate_2023,0,',','.') }}</span> đ <br>
                                            <span style="color: red;"> 2024: </span><span class="js_tst_money">{{number_format($tst_interest_rate_2024,0,',','.') }}</span> đ <br>
                                            <span style="color: red;"> 2025: </span><span class="js_tst_money">{{number_format($tst_interest_rate_2025,0,',','.') }}</span> đ <br>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box-footer" style="text-align: center;">
                        <a href="{{ route('admin.product.index') }}" class="btn btn-danger"><i class="fa fa-undo"></i> Trở Lại</a>
                        <a href="{{ route('admin.user.update',$user->id) }}" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                    </div>
                </div>
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

@endsection
@section('script')
    <script>
        $(function(){

            $('#image').change(function(){
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
            //run js-select2-keyword
            if($('.js-select2-keyword').length >0){
                $('.js-select2-keyword').select2({
                    placeholder :'Chọn Keyword',
                    maximumSelectionLength : 3
                });

            }


            $('.js-check-type').change(function(){
                let $this = $(this);
                let idCategory = this.value;
                let URL = $this.attr('data-url') + '/' + idCategory;
                if(URL){
                    $.ajax({
                        url:URL,
                        // data:{
                        //     idCategory:idCategory
                        // },
                        success:function(results){
                            $('.js-type-product').html(results.type_product)
                            $('.js-attribute').html(results.attribute)
                        },
                        error:function(error){
                            console.log(error.messages);
                        }
                    });
                }
            });

            // $(document).on('keyup','.pro_price_js',function(e){
            //     e.preventDefault();
            //     var res = $(this).val();
            //     res = new Intl.NumberFormat('en-IN').format(res);
            //     $('.convert-price-js').html(res + ' vnd');
            // });
        });

    </script>
@endsection
