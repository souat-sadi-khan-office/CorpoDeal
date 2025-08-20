<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-12">
                <h4 class="h6 mb-3">
                    <b>User Wish List Information</b>
                </h4>
            </div>
        </div>
        
        <table class="table table-bordered" id="user-address-table">
            <thead>
                <th width="5%">#</th>
                <th>Date</th>
                <th>Product</th>
                <th>Stock</th>
                <th>Remove</th>
            </thead>
            <tbody>
                @if ($model->wishlists && count($model->wishlists) > 0)
                    @php($wishListCounter = 1)
                    @foreach ($model->wishlists()->orderBy('id', 'DESC')->get()  as $wishlist)
                        <tr>
                            <td>{{ $wishListCounter }}</td>
                            <td>{{ get_system_date($wishlist->created_at) }} {{ get_system_time($wishlist->created_at) }}</td>
                            <td>
                                @if ($wishlist->product)
                                    <a target="_blank" style="text-decoration: none;" class="text-primary" href="{{ route('slug.handle',$wishlist->product->slug) }}">
                                        <div class="row">
                                            <div class="col-auto">
                                                <img style="max-width:80px;" src="{{ asset($wishlist->product->thumb_image) }}" alt="{{ $wishlist->product->name }}"> 
                                            </div>
                                            <div class="col">
                                                {!! add_line_breaks($wishlist->product->name, 3) !!}
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </td>
                            <td class="product-stock-status">
                                @if ($wishlist->product)
                                    @if ($wishlist->product->in_stock)
                                        <span class="badge rounded-pill text-bg-success">In Stock</span>
                                    @else
                                        <span class="badge rounded-pill text-bg-danger">Out of Stock</span>
                                    @endif
                                @endif
                            </td>
                            <td class="product-remove"
                                data-title="Remove">
                                <a id="delete_item"
                                    data-id="{{ $wishlist->id }}"
                                    data-url="{{ route('admin.customer.wishlist.destroy', $wishlist->id) }}"
                                    class="btn btn-sm btn-outline-secondary remove-column-{{ $wishlist->id }}"
                                    href="javascript:;">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @php($wishListCounter++)
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">
                            {!! App\CPU\Images::show('pictures/none.gif') !!} <br>
                            <b>Nothing to show</b>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>