@extends('layout.master_admin')
@section('content')
    <section class="content-header">
        <h1>
            User
            <small>Carete</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.user.index') }}">User</a></li>
            <li class="active">Create</li>

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
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}"  placeholder="Name ....">
                                @if ($errors->first('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('email') ? 'has-error' : '' }}">
                                <label for="email">Email</label>
                                <input type="text" name="email" class="form-control" value="{{ $user->email }}"  placeholder="Email ....">
                                @if ($errors->first('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('phone') ? 'has-error' : '' }}">
                                <label for="pro_name">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}"  placeholder="Số điện thoại ....">
                                @if ($errors->first('phone'))
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('address') ? 'has-error' : '' }}">
                                <label for="pro_name">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $user->address }}"  placeholder="Nhập địa chỉ khách hàng ....">
                                @if ($errors->first('address'))
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                @endif
                            </div>


                            <div class="form-group {{ $errors->first('password') ? 'has-error' : '' }}">
                                <label for="pro_name">Password New </label>
                                <input type="password" name="password" class="form-control" value=""  placeholder="Nhập mật khẩu mới ...">
                                @if ($errors->first('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
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
                                    <img src="{{ pare_url_file($user->avatar) }}" class="img-thumbnail" style="width: 170px;height:170px" alt="">
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
                    <div class="box-footer" style="text-align: center;">
                        <a href="{{ route('admin.product.index') }}" class="btn btn-danger"><i class="fa fa-undo"></i> Trở Lại</a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
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
