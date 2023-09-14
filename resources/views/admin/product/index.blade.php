@extends('layout.master_admin')
@section('content')
<section class="content-header">
    <h1>
      Product
      <small>index</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('admin.product.index') }}">Product</a></li>
      <li class="active">list</li>

    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
              <div class="box-header">
                    <h3 class="box-title"><a href="{{ route('admin.product.create') }}" class="btn btn-primary">Thêm mới </a></h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right ajax-search-table" placeholder="Search" data-url="{{ route('admin.product.index') }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default" id="icon-search-js-pr" data-url="{{ route('admin.product.index') }}"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="box-title">
                    <form action="" method="GET" class="form-inline">
{{--                        <input type="text" value="{{ Request::get('id') }}" class="form-control" name="id" placeholder="ID">--}}
                        <input type="text" value="{{ Request::get('name') }}" class="form-control" name="name" placeholder="name ..." style="margin-bottom: 10px">
                        <select name="category" class="form-control" style="margin-bottom: 10px">
                            <option value="0">__Danh Mục__</option>
                            @if (isset($categorys))
                                @php
                                    get_category_parent($categorys,0,'',Request::get('category'))
                                @endphp
                            @endif
                        </select>
                        <select name="sort" class="form-control" style="margin-bottom: 10px">
                            <option value="0" >Xắp Xếp</option>
                            <option value="1" {{ Request::get('sort') == 1 ? "selected='selected'" : "" }}>Cũ -> Mới</option>
                            <option value="2" {{ Request::get('sort') == 2 ? "selected='selected'" : "" }}>Mới -> Cũ</option>
                            <option value="3" {{ Request::get('sort') == 3 ? "selected='selected'" : "" }}>Giá Thấp -> Cao</option>
                            <option value="4" {{ Request::get('sort') == 4 ? "selected='selected'" : "" }}>Giá Cao -> Thấp</option>
                        </select>
                        <select name="hot" class="form-control" style="margin-bottom: 10px">
                            <option value="">_ Hót _</option>
                            <option value="1" {{ Request::get('hot') == 1 ? "selected='selected'" : "" }}>Sản Phẩm Hót</option>
                            <option value="2" {{ Request::get('hot') == 2 ? "selected='selected'" : "" }}>Sản Phẩm Không Hót</option>
                        </select>
                        <select name="status" class="form-control" style="margin-bottom: 10px">
                            <option value="">_ Active _</option>
                            <option value="1" {{ Request::get('status') == 1 ? "selected='selected'" : "" }}>Sản Phẩm active</option>
                            <option value="2" {{ Request::get('status') == 2 ? "selected='selected'" : "" }}>Sản Phẩm Không active</option>
                        </select>
                        <select name="sort_pay" class="form-control" style="margin-bottom: 10px">
                            <option value="" >Số lượt mua</option>
                            <option value="1" {{ Request::get('sort_pay') == 1 ? "selected='selected'" : "" }}>nhiều -> ít</option>
                            <option value="2" {{ Request::get('sort_pay') == 2 ? "selected='selected'" : "" }}>ít -> nhiều</option>
                        </select>
                        <select name="sort_pro_number" class="form-control" style="margin-bottom: 10px">
                            <option value="" >Tồn Kho</option>
                            <option value="1" {{ Request::get('sort_pro_number') == 1 ? "selected='selected'" : "" }}>Nhiều -> Ít</option>
                            <option value="2" {{ Request::get('sort_pro_number') == 2 ? "selected='selected'" : "" }}>Ít -> Nhiều</option>
                        </select>
                        <select name="user_id" class="js-example-basic-single form-control">
                            <option value="0">__ Chọn Khách Hàng __</option>
                            @if (isset($users))
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" {{ Request::get('user_id') == $user->id ? "selected='selected'" : "" }}>{{$user->name}} - @if(\Auth::user()->role == \App\Models\User::ADMIN) {{$user->phone}} @endif</option>
                                @endforeach
                            @endif
                        </select>
                        <br>
                        <button type="submit" class="btn btn-success" style="margin-bottom: 10px"><i class="fa fa-search"> </i> Search</button>
                        <button type="submit" class="btn btn-danger" style="margin-bottom: 10px">
                            <a href="{{route('admin.product.index')}}" style="color: white">Reset</a>
                        </button>

                        {{--  <button type="submit" name="export" value="true" class="btn btn-info">
                            <i class="fa fa-save"> </i> Export
                        </button>  --}}
                    </form>
                </div>
              <!-- /.box-header -->
                <div id="js-data">
                    @include('admin.product.data')
                </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <!-- /.row (main row) -->
  </section>
  <!-- /.content -->
<div class="modal fade" id="check-purchase" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">CHECK GIA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="data-check-purchase">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        // $(document).on('click', '.page-link', function(event){
        //     event.preventDefault();
        //     var page = $(this).attr('href').split('page=')[1];
        //     let URL = $(this).attr('href');

        //     console.log(URL);
        //     // fetch_data(URL);
        // });
        $(document).on('click','.status-actives',function(e){
            e.preventDefault();
            var URL = $(this).attr('href');
            console.log(URL);
            fetch_data(URL);
        });

        function fetch_data(URL){
            $.ajax({
                url:URL,
                type:"GET",
                success:function(results){
                    $('#js-data').html(results.data);
                    if(results.messages) {
                        toastr.success(results.messages);
                    }
                }
            });
        }

        $(document).on('click',"#icon-search-js-pr",function(e){
            e.preventDefault();
            var URL = $(this).attr('data-url');
            console.log(URL);
            var res = $(".ajax-search-table").val();
            console.log(res);
            $.ajax({
                url:URL,
                type:"GET",
                data:{search:res},
                success:function(results){
                    if(results.data){
                        $("#js-data").html(results.data);
                    }
                }
            });
        });

        $(document).on('click','.js-view-purchase',function(e){
            $("#check-purchase").modal("show");
            let $this = $(this);
            let URL = $this.attr('href');
            console.log(URL)
            if(URL){
                $.ajax({
                    url:URL,
                    type:"GET",
                    success:function(results){
                        $('#data-check-purchase').html(results.data);
                        $data_this = $this;
                    },
                    error:function(error){
                        console.log(error.messages);
                    }
                });
            }
            return false;
        });

  });
  </script>
@endsection
