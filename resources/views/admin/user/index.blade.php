@extends('layout.master_admin')
@section('content')
<section class="content-header">
    <h1>
      User
      <small>index</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('admin.user.index') }}">User</a></li>
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
                  <h3 class="box-title"><a href="{{ route('admin.user.create') }}" class="btn btn-primary">Thêm mới </a></h3>
                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" value="{{ Request::get('email') }}" class="form-control ajax-search-table" data-url="{{ route('admin.user.index') }}" name="table_search" placeholder="nhập">
                      <div class="input-group-btn">
                        <button type="button" class="btn btn-default" id="icon-search-js-pr" data-url="{{ route('admin.user.index') }}"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                </div>
              </div>
              <!-- /.box-header -->
                <div id="js-data">
                    @include('admin.user.data')
                </div>
              <!-- /.box-body -->
              
              <div></div>
            </div>
            <!-- /.box -->
          </div>
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <!-- /.row (main row) -->
  </section>
  <!-- /.content -->
@endsection
@section('script')
<script>
    $(document).ready(function(){
      
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
  });
  </script>
@endsection