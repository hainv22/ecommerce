@extends('layout.master_admin')
@section('content')
<section class="content-header">
    <h1>
      Product
      <small>Carete</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('admin.product.index') }}">Product</a></li>
      <li class="active">Create</li>

    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        {{--  @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif  --}}
        <form role="form" action="" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="col-md-7">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                          <h3 class="box-title">Thông tin cơ bản</h3>
                        </div>
                          <div class="box-body">

                            <div class="form-group {{ $errors->first('pro_name') ? 'has-error' : '' }}">
                              <label for="pro_name">Name</label>
                              <input type="text" name="pro_name" class="form-control" value="{{ old('pro_name') }}"  placeholder="Name ....">
                                @if ($errors->first('pro_name'))
                                    <span class="text-danger">{{ $errors->first('pro_name') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('pro_price') ? 'has-error' : '' }}" >
                                <label for="pro_price">Giá </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="number" name="pro_price" class="form-control" value="{{ old('pro_price') }}">
                                </div>
                                <small id="emailHelp" class="form-text text-muted "></small>
                                @if ($errors->first('pro_price'))
                                    <span class="text-danger">{{ $errors->first('pro_price') }}</span>
                                @endif
                            </div>


                            <div class="form-group {{ $errors->first('pro_description') ? 'has-error' : '' }}">
                                <label>Description</label>
                                <textarea class="form-control" value="" name="pro_description" rows="3" placeholder="Enter ...">{{ old('pro_description') }}</textarea>
                                @if ($errors->first('pro_description'))
                                    <span class="text-danger">{{ $errors->first('pro_description') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('pro_category_id') ? 'has-error' : '' }}">
                                <label>Danh Mục (*)</label>
                                <select name="pro_category_id" class="form-control js-check-type" data-url="{{route('admin.product.get.typeproduct')}}">
                                    @if (isset($categorys))
                                        @php
                                            get_category_parent($categorys)
                                        @endphp
                                    @endif
                                </select>
                                @if ($errors->first('pro_category_id'))
                                    <span class="text-danger">{{ $errors->first('pro_category_id') }}</span>
                                @endif
                            </div>
                          </div>
                      </div>
                    <div class="box box-warning">
                        <div class="box-header">
                            <h3 class="box-title">Thuộc Tính</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-sm-6 {{ $errors->first('pro_country') ? 'has-error' : '' }}">
                                <label>Xuất sứ</label>
                                <select name="pro_country" class="form-control">
                                    <option value="1">Việt Nam</option>
                                    <option value="2">Anh</option>
                                    <option value="3">Thụy Sỹ</option>
                                    <option value="4">Mỹ</option>
                                </select>
                                @if ($errors->first('pro_country'))
                                    <span class="text-danger">{{ $errors->first('pro_country') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-sm-6 {{ $errors->first('pro_number') ? 'has-error' : '' }}">
                                <label>Số Lượng</label>
                                <input type="number" name="pro_number" value="999999999" class="form-control" placeholder="0">
                                @if ($errors->first('pro_number'))
                                    <span class="text-danger">{{ $errors->first('pro_number') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                      <div class="box box-success">
                            <div class="box-header with-border">
                              <h3 class="box-title">Content</h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group {{ $errors->first('pro_content') ? 'has-error' : '' }}">
                                    <label>Nội Dung</label>
                                    <textarea class="form-control" value="" name="pro_content" rows="6" placeholder="Enter ...">{{ old('pro_content') }}</textarea>
                                    @if ($errors->first('pro_content'))
                                        <span class="text-danger">{{ $errors->first('pro_content') }}</span>
                                    @endif
                                </div>
                            </div>
                      </div>

                      <div class="box box-info">
                        <div class="box-header with-border">
                          <h3 class="box-title">Ảnh Đại Diện</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Ảnh Mới</label>
                                <div style="margin-bottom:10px" >
                                    <img id="image_preview_container" src="{{ asset('admin/product.jpg') }}" class="img-thumbnail" style="width: 220px;height:200px" alt=""></div>
                                <input type="file" name="pro_avatar" id="image"  class="js-upload">
                                @if ($errors->first('pro_avatar'))
                                    <span class="text-danger">{{ $errors->first('pro_avatar') }}</span>
                                @endif
                            </div>
                        </div>
                      </div>

                      <div class="box box-info">
                        <div class="box-header with-border">
                          <h3 class="box-title">Album ảnh</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <div class="file-loading">
                                    <input type="file" name="file[]" id="images" multiple class="file" data-overwrite-initial="false" data-min-file-count="0">
                                </div>
                                @if ($errors->first('file.*'))
                                    <span class="text-danger">{{ $errors->first('file.*') }}</span>
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
