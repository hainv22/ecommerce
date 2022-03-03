@extends('layout.master_admin')
@section('content')
    <section class="content-header">
        <h1>
            CateGory
            <small>index</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.category.index') }}">Category</a></li>
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
                    <h3 class="box-title"><a href="{{ route('admin.category.create') }}" class="btn btn-primary">Thêm mới </a></h3>
                    <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control pull-right ajax-search-table" placeholder="Search" data-url="{{ route('admin.category.index') }}">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div id="js-data">
                    @include('admin.category.data')
                </div>
            </div>
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
    </section>
  <!-- /.content -->
@endsection
@section('script')

@endsection
