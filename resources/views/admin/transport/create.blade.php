@extends('layout.master_admin')
@section('content')
<section class="content-header">
    <h1>
        Transport
      <small>Carete</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('admin.transport.index') }}">Transport</a></li>
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

                            <div class="form-group {{ $errors->first('tp_name') ? 'has-error' : '' }}">
                              <label for="pro_name">Name transport</label>
                              <input type="text" name="tp_name" class="form-control" value="{{ old('tp_name') }}"  placeholder="Name ....">
                                @if ($errors->first('trans_name'))
                                    <span class="text-danger">{{ $errors->first('tp_name') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('tp_fee') ? 'has-error' : '' }}" >
                                <label for="trans_fee"> Fee-Money / 1kg </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="number" name="tp_fee" class="form-control price_format" value="{{ old('tp_fee') }}">
                                </div>
                                <small id="emailHelp" class="form-text text-muted "></small>
                                @if ($errors->first('tp_fee'))
                                    <span class="text-danger">{{ $errors->first('tp_fee') }}</span>
                                @endif
                            </div>
                          </div>
                      </div>
                </div>

                <div class="col-md-5">
                      <div class="box box-success">
                            <div class="box-header with-border">
                              <h3 class="box-title">Description</h3>
                            </div>
                            <div class="box-body">
                                <div class="form-group {{ $errors->first('tp_description') ? 'has-error' : '' }}">
                                    <label>Mô tả Transport </label>
                                    <textarea class="form-control" value="{{ old('tp_description') }}" name="tp_description" rows="5" placeholder="Enter ..."></textarea>
                                    @if ($errors->first('tp_description'))
                                        <span class="text-danger">{{ $errors->first('tp_description') }}</span>
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
  <script src="{{ asset('admin/simple.money.format.js') }}"></script>

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

    <script type="text/javascript">
        $('.price_format').simpleMoneyFormat();
    </script>
@endsection
