@extends('layout.master_admin')
@section('content')
<section class="content-header">
    <h1>
      Quản lý đơn hàng
      <small>index</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('admin.transaction.index') }}">transaction</a></li>
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
                    <div class="box-title">
                        <form action="" method="GET" class="form-inline">
                            <input type="text" value="{{ Request::get('id') }}" class="form-control" name="id" placeholder="ID">
                            <input type="text" value="{{ Request::get('email') }}" class="form-control" name="email" placeholder="Email ...">
                            {{-- <select name="type" class="form-control">
                                <option value="0">__Phân Loại Khách__</option>
                                <option value="1" {{ Request::get('type') == 1 ? "selected='selected'" : "" }}>Thành Viên</option>
                                <option value="2" {{ Request::get('type') == 2 ? "selected='selected'" : "" }}>Khách</option>
                            </select> --}}
                            <select name="status" class="form-control">
                                <option value="0">__Trạng Thái__</option>
                                <option value="1" {{ Request::get('status') == 1 ? "selected='selected'" : "" }}>Tiếp Nhận</option>
                                <option value="2" {{ Request::get('status') == 2 ? "selected='selected'" : "" }}>Đang Vận Chuyển</option>
                                <option value="3" {{ Request::get('status') == 3 ? "selected='selected'" : "" }}>Đã Bàn Giao</option>
                                <option value="-1" {{ Request::get('status') == -1 ? "selected='selected'" : "" }}>Hủy Bỏ</option>
                            </select>
                            <button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Search</button>
                        </form>
                    </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                    <form id="frm_action" class="form-horizontal" name="frm_action" method="post" action="" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="txtcustomer" id="txtcustomer" value="">
                        <table class="table table-bordered tbl_add_orderext">
                            <thead>
                            <tr>
                                <th>Link sản phẩm <font color="red">*</font></th>
                                <th>Link ảnh (nếu có)</th>
                                <th>Tên shop </th>
                                <th>Mô tả <font color="red">*</font></th>
                                <th>Loại hàng</th>
                                <th width="100">Đơn giá (tệ)</th>
                                <th width="100">Số lượng<font color="red">*</font></th>
                                <th width="100">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input name="txt_link_product[]" class="form-control txt_link_product" id="txt_link_product" type="text" placeholder="Link sản phẩm" required >
                                </td>
                                <td>
                                    <input name="txt_link_image[]" class="form-control txt_link_image" type="text" placeholder="Link hình ảnh">
                                </td>
                                <td>
                                    <input name="txt_name_shop[]" class="form-control txt_name_shop"  type="text" placeholder="Tên shop">
                                </td>
                                <td>
                                    <input name="txt_intro_product[]" class="form-control txt_intro_product"  placeholder="Màu, size..." require>
                                </td>
                                <td class="cls_td">
                                    <input name="txt_note[]" class="form-control txt_note" placeholder="Dễ vỡ, đóng gỗ...">
                                </td>
                                <td class="cls_td">
                                    <input type="text" name="txt_price[]" class="form-control txt_price" value="" class="form-control" placeholder="Đơn giá" >
                                </td>
                                <td class="cls_td">
                                    <input type="number" name="txt_quantity[]" class="form-control txt_quantity" value="1" min="1" placeholder="SL" required>
                                </td>
                                <td align="center" class="cls_td">
                                    <a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <hr>
                            <tr>
                                <td colspan="100%" class="text-left" >
                                    <input type="checkbox" required id="check_dieukien" checked> Tôi đã xem và đồng ý với <a href="#" style="color:red;font-weight:bold;" id="quydinh">QUY ĐỊNH ĐỐI VỚI CÁC ĐƠN HÀNG ORDER</a> (*)
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                        <div class="clear-both text-center">
                            <a href="#" class="btn btn-primary add_item_order">+ Thêm sản phẩm</a>
                            <a class="btn btn-danger save_order" name="cmdsave" id="cmdsave"  onclick="return check_input();" >Lưu đơn hàng</a>
                        </div>
                        <p></p>
                    </form>
              </div>
            </div>
            <!-- /.box -->
          </div>
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <!-- /.row (main row) -->
  </section>
  <!-- /.content -->
    <div id="myModal" class="modal fade col-md-12" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body" id="data_modal">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="close_modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            // thêm item mới
            $(".add_item_order").on("click",function(){
                var row_infor='<tr><td><input name="txt_link_product[]" class="form-control txt_link_product"  type="text" placeholder="Link sản phẩm" required></td><td><input name="txt_link_image[]" class="form-control txt_link_image"  type="text" placeholder="Link hình ảnh"></td><td><input name="txt_name_shop[]" class="form-control txt_name_shop"  type="text" placeholder="Tên shop"></td><td><input name="txt_intro_product[]" class="form-control txt_intro_product"  placeholder="VD: màu sắc, kích thước..." ></td><td class="cls_td"><input name="txt_note[]" class="form-control txt_note" placeholder="Dễ vỡ, đóng gỗ..."></td><td class="cls_td"><input type="text" name="txt_price[]" class="form-control txt_price" value="" class="form-control" placeholder="Đơn giá" required></td><td class="cls_td"><input type="number" name="txt_quantity[]" class="form-control txt_quantity" value="1" min="1" placeholder="SL" required></td><td align="center" class="cls_td"><a href="#" class="btn_action btn_del" onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a></td></tr>';
                $(".tbl_add_orderext tbody").append(row_infor);
                return false;
            });
        });
    </script>
    <script type="text/javascript">
        //check url
        function isUrlValid(url) {
            return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
        }
        function deleteItem(thistag){
            if(confirm("Bạn chắc chắn muốn xóa item này?")==true){
                $(thistag).parent().parent().remove();
            }
        }
        function check_input(){
            // var customer=$("#txtcustomer").val();
            // console.log(customer);
            // if(customer=="") {
            //     alert("Bạn cần đăng nhập để thực hiện chức năng này!");
            //     $("#txtcustomer").focus(); return false;
            // }
            var flag=true; var mess="";
            $(".txt_intro_product").each(function(){
                var value=$(this).val();
                if(value=="") {
                    $(this).css("background-color","#eee"); $(this).focus(); flag= false;
                }
            })
            // $(".txt_price").each(function(){
            // 	var value=$(this).val();
            //  	if(parseFloat(value)==0 || value=="") {$(this).css("background-color","#eee"); $(this).focus(); flag= false;}
            // })
            $(".txt_quantity").each(function(){
                var value=$(this).val();
                if(parseFloat(value)==0 || value=="") {
                    $(this).css("background-color","#eee"); $(this).focus(); flag= false;
                }
            });
            if($("#check_dieukien").is(":checked") !=true){
                $(this).focus(); flag= false; mess=" Bạn cần đồng ý điều khoản của chúng tôi!";
            }
            var rowCount=0;
            if($(".tbl_add_orderext tbody tr").length>0)
                rowCount=$(".tbl_add_orderext tbody tr").length;
            else if($(".tbl_add_orderext .item").length>0)
                rowCount=$(".tbl_add_orderext .item").length;
            if(rowCount==0) {flag=false; mess="Dữ liệu trống";}
            if(flag==true){
                $("#frm_action").submit();
                return true;
            }else {
                alert("Cần nhập đủ thông tin! "+mess);
            }

        }
        $(document).ready(function(){
            // datepicker bootstrap
            $.fn.datepicker.defaults.language = 'vi';
            $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $('.datepicker').datepicker({
                startDate: '-3d'
            });

            // view quy định điều khoản
            $("#quydinh").click(function(){
                $("#myModal").modal("show");
                $("#myModal .modal-title").html("Quy định và chính sách khi tạo đơn hàng");
                $("#myModal .modal-dialog").addClass("modal-lg");
                $("#myModal .modal-body").html("...Dm hải");
                $("#myModal .modal-body").load("https://www.youtube.com/watch?v=TucgDVWI9wc");
                return false;
            })

        });
    </script>
@endsection
