@extends('layout.master_admin')
@section('content')
    <section class="content-header">
        <h1>
            Owner Transaction
            <small>Carete</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('admin.owner-china-transactions.index') }}">danh sach don</a></li>
            <li class="active">Create</li>

        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form role="form" id="frm_action" name="frm_action" method="post" action="" enctype="multipart/form-data">
                @csrf
                <div class="col-md-7">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Thông tin cơ bản</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group {{ $errors->first('ot_user_id') ? 'has-error' : '' }}">
                                <label for="pro_name">Owner</label>
                                <select name="ot_user_id" class="form-control">
                                    @if (isset($users))
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->oc_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->first('ot_user_id'))
                                    <span class="text-danger">{{ $errors->first('ot_user_id') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('ot_order_date') ? 'has-error' : '' }} col-md-6" >
                                <label for="pro_price">Ngày đặt hàng</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                    <input type="date" name="ot_order_date" class="form-control" value="{{ old('ot_order_date') }}">
                                </div>
                                <small id="emailHelp" class="form-text text-muted "></small>
                                @if ($errors->first('ot_order_date'))
                                    <span class="text-danger">{{ $errors->first('ot_order_date') }}</span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->first('ot_transaction_role') ? 'has-error' : '' }}" >
                                <label for="pro_price"> Loại </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                    <select name="ot_transaction_role" class="form-control">
                                        @if(\Auth::user()->role == \App\Models\User::ADMIN)
                                            <option value="1" >Admin - Của Tôi</option>
                                        @endif
                                        <option value="2" >Chung</option>
                                    </select>
                                </div>
                                @if ($errors->first('ot_transaction_role'))
                                    <span class="text-danger">{{ $errors->first('ot_transaction_role') }}</span>
                                @endif
                            </div>

                        </div>
                    </div>


                </div>

                <div class="col-md-5">
                    <div class="box box-warning">
                        <div class="box-header">
                            <div class="clear-both text-center">
                                <a href="#" class="btn btn-primary add_item_bao">+ Thêm số bao</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-bordered tbl_add_bao">
                                    <thead>
                                        <tr>
                                            <th >Số cân nặng</th>
                                            <th >Note *********</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Content</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group {{ $errors->first('ot_note') ? 'has-error' : '' }}">
                                <label>Nội Dung</label>
                                <textarea class="form-control" value="" name="ot_note" rows="6" placeholder="Enter ...">{{ old('ot_note') }}</textarea>
                                @if ($errors->first('ot_note'))
                                    <span class="text-danger">{{ $errors->first('ot_note') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header">
                            <div class="clear-both text-center">
                                <a href="#" class="btn btn-primary add_item_order">+ Thêm sản phẩm</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-bordered tbl_add_orderext ">
                                    <thead>
                                    <tr>
                                        <th >ID SảnPhẩm</th>
                                        <th >image</th>
                                        <th >gia + name</th>
                                        <th>Số lượng<span style="color: red; ">*</span></th>
                                        <th>Chú ý ********</th>
                                        <th>Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="cls_td col-sm-1" >
                                            <input type="button" value="CLICK" id="get_products" data-url-products="{{route('admin.owner-china-transactions.get.products')}}">
                                            <input type="hidden" name="txt_id_product[]" value="" >
                                            {{--                                            <input type="input" name="txt_id_product[]" class="form-control txt_id" value="Click Chọn SP" class="form-control" placeholder="id" id="get_products" data-url-products="{{route('admin.owner-china-transactions.get.products')}}" required>--}}
                                        </td>
                                        <td class="cls_td col-sm-4" >
                                            <img src="{{ pare_url_file(null) }}" alt="" width="150px" height="100px">
                                        </td>
                                        <td class="cls_td col-sm-2">
                                            <span>Giá: </span> <br>
                                            <span>Name: </span>
                                        </td>
                                        <td class="cls_td col-sm-2">
                                            <input type="number" name="txt_quantity_product[]" class="form-control txt_quantity" value="" min="1" placeholder="SL" required>
                                        </td>
                                        <td class="cls_td col-sm-4">
                                            <textarea class="form-control" value="" name="od_note[]" rows="3" placeholder="Enter ..." ></textarea>
                                        </td>
                                        <td align="center" class="cls_td">
                                            <a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box-footer" style="text-align: center;">
                        <a href="{{ route('admin.owner-china-transactions.index') }}" class="btn btn-danger"><i class="fa fa-undo"></i> Trở Lại</a>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
        </div>

    </section>
    <div id="myModal" class="modal fade col-md-12" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                    <input type="text" name="table_search" class="form-control pull-right ajax-search-product" placeholder="name hoặc giá" data-url="{{ route('admin.owner-china-transactions.get.products') }}">
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
                                <th scope="col">Gia Trung</th>
                                <th scope="col">số lượng</th>
                                <th scope="col">action</th>
                            </tr>
                            </thead>
                            <tbody id="body_list_products">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.product.create') }}" target="_blank" class="btn btn-danger"><i class="fa fa-save"></i> Create</a>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="close_modal">Đóng</button>
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

            $(document).on('click','#click_get_id_product',function(e){
                let $this = $(this);
                let ID = $this.attr('data-id-product');
                let SRC = $this.attr('data-url-image');
                let NAME = $this.attr('data-name');
                let PRICE = $this.attr('data-price');
                let PRICE_YUAN = $this.attr('data-price-yuan');
                let PRO_NUMBER = $this.attr('data-number');
                $data_this.val(ID)
                $data_this.next().val(ID)
                $data_this.css("border", "1px solid red")
                $data_this.css("font-size", "15px")
                $data_this.css("color", "red")
                $data_this.parent().next().children().attr("src", SRC);
                $data_this.parent().next().next().html("<span> việt: "+ PRICE +"</span>"+"<br><span> trung: "+ PRICE_YUAN +"</span>" + " <br><span>"+ NAME +"</span>" + " <br><span>"+ PRO_NUMBER +" Cái</span>");
                $('#myModal').modal('toggle');
            });

            $(".add_item_order").on("click", function() {
                var row_infor = '<tr> <td class="cls_td col-sm-1" > <input type="button" value="CLICK" id="get_products" data-url-products="{{route('admin.owner-china-transactions.get.products')}}"> <input type="hidden" name="txt_id_product[]" value="" > </td><td class="cls_td col-sm-4" ><img src="{{ pare_url_file(null) }}" alt="" width="150px" height="100px"></td><td class="cls_td col-sm-2"> <span>Giá: </span> <br><span>Name: </span></td><td class="cls_td col-sm-2"><input type="number" name="txt_quantity_product[]" class="form-control txt_quantity" value="" min="1" placeholder="SL" required></td><td class="cls_td col-sm-4"><textarea class="form-control" value="" name="od_note[]" rows="3" placeholder="Enter ..." ></textarea></td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
                $(".tbl_add_orderext tbody").append(row_infor);
                return false;
            });

            $(".add_item_bao").on("click", function() {
                var row_infor = '<tr> <td class="cls_td col-md-3"><input type="number" name="b_weight[]" class="form-control" value="" required=""></td><td class="cls_td col-md-7"><textarea class="form-control" value="" name="b_note[]" rows="3" placeholder="Enter ..."></textarea></td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
                $(".tbl_add_bao tbody").append(row_infor);
                return false;
            });

        });
        function deleteItem(thistag){
            if(confirm("Bạn chắc chắn muốn xóa item này?")==true){
                $(thistag).parent().parent().remove();
            }
        }

        $(document).ready(function(){
            $('body').on('click','.pagination a',function(e){
                e.preventDefault();
                $('li').removeClass('active');
                $(this).parent('li').addClass('active');
                var URL = $(this).attr('href');
                getPosts(URL);
            });
            function getPosts(URL){
                $.ajax({
                    url:URL,
                    type:"GET",
                    data:{
                        transaction_get_products: 1
                    },
                    success:function(results){
                        $('#body_list_products').html(results.data);
                    },
                    error:function(error){
                        console.log(error.messages);
                    }
                });
            }

            $(document).on('keyup','.ajax-search-product',function(e){
                e.preventDefault();
                var URL = $(this).attr('data-url');
                var res = $(this).val();
                $.ajax({
                    url:URL,
                    type:"GET",
                    data:{name:res,transaction_get_products: 1},
                    success:function(results){
                        $('#body_list_products').html(results.data);
                    }
                });
            });
        });
    </script>
@endsection
