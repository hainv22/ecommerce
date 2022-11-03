<input type="checkbox" name="otd_success_date[]" data-url="{{route('admin.owner-china-transactions.update.success.date', $detail->id)}}" id="update_success_date_otd" class="form-check-input" {{ $detail->otd_status == 1 ? '' : 'checked' }} >
@if($detail->otd_status == 1)
    Chưa giao
@else
    Đã giao
@endif
<a href="#" class="btn_action btn_del " onclick="deleteItem(this);return false;"><i class="fa fa-trash-o fa_user fa_del"></i></a>
