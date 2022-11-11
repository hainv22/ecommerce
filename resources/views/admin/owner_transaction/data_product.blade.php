@isset($products)
    @if(count($products) > 0)
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
                {{ $product->pro_money_yuan }} NDT
            </td>
            <td class="cls_td">
                {{ $product->pro_number }}
            </td>
            <td class="cls_td">
                <input type="button" class="btn btn-primary" value="Chọn Sản Phẩm" data-id-product="{{$product->id}}"
                       data-name="{{$product->pro_name}}" data-price="{{ number_format($product->pro_price,0,',','.') }}"
                       data-url-image="{{ pare_url_file($product->pro_avatar) }}" data-price-yuan="{{$product->pro_money_yuan}}"
                       data-number="{{ $product->pro_number }}"
                       id="click_get_id_product">
                <a href="{{ route('admin.product.update',$product->id) }}" target="_blank" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Edit</a>
            </td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td class="cls_td">
            <div>{!! $products->appends($query ?? [])->links() !!}</div>
        </td>
    </tr>
    @else
        <a href="{{ route('admin.product.create') }}" target="_blank" class="btn btn-danger"><i class="fa fa-save"></i> Create</a>
    @endif
@endisset
