<div class="row">
    <div class="col-md-12 table-responsive">
        <div class="row">
            <div class="col-md-12">
                <h4 class="h6 mb-3">
                    <b>User Coupon Usage History</b>
                </h4>
            </div>
        </div>
        
        <table class="table table-bordered" id="user-address-table">
            <thead>
                <th width="5%">#</th>
                <th>Date</th>
                <th>Coupon</th>
                <th>Discount</th>
            </thead>
            <tbody>
                @if ($model->userCoupon && count($model->userCoupon) > 0)
                    @php($couponUsageCounter = 1)
                    @foreach ($model->userCoupon()->orderBy('id', 'DESC')->get()  as $couponUsage)
                        <tr>
                            <td>{{ $couponUsageCounter }}</td>
                            <td>{{ get_system_date($couponUsage->created_at) }} {{ get_system_time($couponUsage->created_at) }}</td>
                            <td>
                                @if ($couponUsage->coupon)
                                    {{ $couponUsage->coupon->coupon_code }}
                                @endif
                            </td>
                            <td>{{ $couponUsage->discount_amount }}</td>
                        </tr>
                        
                        @php($couponUsageCounter++)
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