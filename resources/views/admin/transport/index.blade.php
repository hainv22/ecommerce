@extends('layout.master_admin')
@section('content')
<section class="content-header">
    <h1>
      Transport
      <small>index</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('admin.transport.index') }}">Transports</a></li>
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
                    <h3 class="box-title"><a href="{{ route('admin.transport.create') }}" class="btn btn-primary">Thêm mới </a></h3>
                </div>
                <div class="box-title">
                    <form action="" method="GET" class="form-inline">
                        <input type="text" value="{{ Request::get('name') }}" class="form-control" name="tp_name" placeholder="Nhập tên nhà vận chuyển ..." style="margin-bottom: 10px">
                        <button type="submit" class="btn btn-success" style="margin-bottom: 10px"><i class="fa fa-search"> </i> Search</button>
                        <button type="submit" class="btn btn-danger" style="margin-bottom: 10px">
                            <a href="{{route('admin.transport.index')}}" style="color: white">Reset</a>
                        </button>
                    </form>
                </div>
              <!-- /.box-header -->
                <div id="js-data">
                    @include('admin.transport.data')
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

  });
</script>
@endsection
