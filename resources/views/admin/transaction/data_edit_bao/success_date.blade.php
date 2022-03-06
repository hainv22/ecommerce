
<input type="checkbox" name="b_success_date[]" data-url="{{route('admin.transaction.update.success.date', $bao->id)}}"
       id="update_success_date" class="form-check-input" {{ empty($bao->b_success_date) == true ? '' : 'checked' }}
>
@if(empty($bao->b_success_date) == true)
    Chưa giao
@else
    Đã giao
@endif
