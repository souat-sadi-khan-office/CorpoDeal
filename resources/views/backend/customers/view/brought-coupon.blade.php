<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-9">
                <h4 class="h6 mb-3">
                    <b>User Brought Coupon History</b>
                </h4>
            </div>
            <div class="col-md-3 text-end">
                <a href="javascript:;" id="content_management" data-url="{{ route('admin.coupon.assign', ['user' => $model->id]) }}" class="btn btn-soft-success" data-bs-toggle="tooltip" data-bs-placement="Top" title="Assign Coupon To User">
                    <i class="bi bi-gift"></i>
                </a>
            </div>
        </div>
        
        <table class="table table-bordered" id="user-address-table">
            <thead>
                <th width="5%">#</th>
                <th>Date</th>
                <th>Coupon</th>
                <th>Status</th>
            </thead>
            <tbody>
                @if ($model->coupons && count($model->coupons) > 0)
                    @php($couponCounter = 1)
                    @foreach ($model->coupons()->orderBy('id', 'DESC')->get() as $coupon)
                        <tr>
                            <td>{{ $couponCounter }}</td>
                            <td>{{ get_system_date($coupon->created_at) }} {{ get_system_time($coupon->created_at) }}</td>
                            <td>
                                @if ($coupon->coupon)
                                    {{ $coupon->coupon->coupon_code }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $coupon->status == 1 ? 'warning' : 'success' }}">{{ $coupon->status == 1 ? 'Used' : 'Not Used' }}</span>
                            </td>
                        </tr>
                        
                        @php($couponCounter++)
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