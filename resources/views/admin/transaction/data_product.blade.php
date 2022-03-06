@isset($products)
    @foreach($products as $product)
    <tr>
        <td class="cls_td">
            {{$product->id}}
        </td>
        <td class="cls_td">
            {{$product->pro_name}}
        </td>
        <td class="cls_td">
            <img src="{{ pare_url_file($product->pro_avatar) }}" alt="" width="150px" height="150px">
        </td>
        <td class="cls_td">
            {{ number_format($product->pro_price,0,',','.') }} VND
        </td>
        <td class="cls_td">
            <input type="button" class="btn btn-primary" value="Chọn Sản Phẩm" data-id-product="{{$product->id}}" data-name="{{$product->pro_name}}" data-price="{{ number_format($product->pro_price,0,',','.') }}" data-url-image="{{ pare_url_file($product->pro_avatar) }}" id="click_get_id_product">
        </td>
    </tr>
    @endforeach
@endisset
