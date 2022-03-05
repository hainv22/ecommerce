@extends('layout.master_admin')
@section('content')
<section class="content-header">
    <h1>
        Transaction
        <small>Carete</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('admin.transaction.index') }}">Transaction</a></li>
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

                        <div class="form-group {{ $errors->first('pro_category_id') ? 'has-error' : '' }}">
                            <label for="pro_name">Người Mua Hàng</label>
                            <select name="tst_user_id" class="form-control">
                            @if (isset($users))
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}} - {{$user->phone}}</option>
                                    @endforeach
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
                                    <th>Số lượng<span style="color: red; ">*</span></th>
                                    <th>Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="cls_td col-sm-2" >
                                        <input type="input" name="txt_id_product[]" class="form-control txt_id" value="Click Chọn SP" class="form-control" placeholder="id" id="get_products" data-url-products="{{route('admin.product.index')}}" required>
                                    </td>
                                    <td class="cls_td col-sm-5" >
                                        <img src="{{ pare_url_file(null) }}" alt="" width="150px" height="100px">
                                    </td>
                                    <td class="cls_td col-sm-2">
                                        <input type="number" name="txt_quantity_product[]" class="form-control txt_quantity" value="" min="1" placeholder="SL" required>
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

            <div class="col-md-5">

                <div class="box box-warning">
                    <div class="box-header">
                        <div class="clear-both text-center">
                            <a href="#" class="btn btn-primary add_item_bao">+ Thêm số bao</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-bordered tbl_add_bao ">
                                <thead>
                                <tr>
                                    <th width="200px">Tên bao</th>
                                    <th >Số cân nặng</th>
                                    <th width="200px">Note</th>
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
                        <div class="form-group {{ $errors->first('tst_note') ? 'has-error' : '' }}">
                            <label>Nội Dung</label>
                            <textarea class="form-control" value="" name="tst_note" rows="6" placeholder="Enter ...">{{ old('tst_note') }}</textarea>
                            @if ($errors->first('tst_note'))
                            <span class="text-danger">{{ $errors->first('tst_note') }}</span>
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

</section>
<div id="myModal" class="modal fade col-md-12" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
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
                            <th scope="col">action</th>
                        </tr>
                        </thead>
                        <tbody id="body_list_products">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
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
                    data:{
                        transaction_get_products: 1
                    },
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
            $data_this.val(ID)
            $data_this.css("border", "1px solid red")
            $data_this.css("font-size", "15px")
            $data_this.css("color", "red")
            $data_this.parent().next().children().attr("src", SRC);
            $('#myModal').modal('toggle');
        });

        $(".add_item_order").on("click", function() {
            var row_infor = '<tr> <td class="cls_td col-sm-2" > <input type="input" name="txt_id_product[]" class="form-control txt_id" value="Click Chọn SP" class="form-control" placeholder="id" id="get_products" data-url-products="{{route('admin.product.index')}}" required> </td><td class="cls_td col-sm-5" ><img src="{{ pare_url_file(null) }}" alt="" width="150px" height="100px"></td><td class="cls_td col-sm-2"><input type="number" name="txt_quantity_product[]" class="form-control txt_quantity" value="" min="1" placeholder="SL" required></td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
            $(".tbl_add_orderext tbody").append(row_infor);
            return false;
        });

        $(".add_item_bao").on("click", function() {
            var row_infor = '<tr> <td class="cls_td col-md-5" > <textarea class="form-control" value="" name="b_name[]" rows="3" placeholder="Enter ..." required></textarea></td><td class="cls_td col-md-2" ><input type="number" name="b_weight[]" class="form-control" value="" required></td><td class="cls_td col-md-5"><textarea class="form-control" value="" name="b_note[]" rows="3" placeholder="Enter ..."></textarea></td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
            $(".tbl_add_bao tbody").append(row_infor);
            return false;
        });

    });
    function deleteItem(thistag){
        if(confirm("Bạn chắc chắn muốn xóa item này?")==true){
            $(thistag).parent().parent().remove();
        }
    }
</script>
@endsection
