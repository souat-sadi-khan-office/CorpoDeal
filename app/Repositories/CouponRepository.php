<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\Cart;
use App\Models\User;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\UserPoint;
use App\Models\UserBroughtCoupon;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\CouponRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CouponRepository implements CouponRepositoryInterface
{
    public function all()
    {
        return Coupon::all();
    }

    public function dataTable()
    {
        $models = $this->all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('discount_amount', function ($model) {
                return $model->discount_type == 'percent' ? $model->discount_amount : round(convert_price($model->discount_amount));
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if (auth()->guard('admin')->user()->hasPermissionTo('coupon.update') === false) {
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $status = $model->status == 1 ? 'Active' : 'Inactive';

                    return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                } else {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.coupon.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                }
            })
            ->addColumn('action', function ($model) {
                return view('backend.coupon.action', compact('model'));
            })
            ->rawColumns(['action', 'discount_amount', 'status'])
            ->make(true);
    }

    public function find($id)
    {
        return Coupon::findOrFail($id);
    }

    public function create($data)
    {
        Coupon::create([
            'coupon_code' => $data->coupon_code,
            'minimum_shipping_amount' => $data->minimum_shipping_amount,
            'discount_amount' => $data->discount_type !== 'percent'?covert_to_usd($data->discount_amount):$data->discount_amount,
            'discount_type' => $data->discount_type,
            'maximum_discount_amount' => covert_to_usd($data->maximum_discount_amount),
            // 'start_date' => $data->start_date ? date('Y-m-d', strtotime($data->start_date)) : null,
            // 'end_date' => $data->end_date ? date('Y-m-d', strtotime($data->end_date)) : null,
            'is_new_user' => $data->is_new_user ?? 0,
            'deadline' => $data->deadline ?? null,
            'platform' => $data->platform ?? 'both',
            'status' => $data->status,
            'is_sellable' => $data->is_sellable,
            'points_to_buy' => $data->points_to_buy ?? 0,
        ]);

        $json = ['status' => true, 'load' => true, 'message' => 'Coupon created successfully'];
        return response()->json($json);
    }

    public function update($id, $data)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->coupon_code = $data->coupon_code;
        $coupon->minimum_shipping_amount = covert_to_usd($data->minimum_shipping_amount);
        $coupon->discount_amount =  $data->discount_type !== 'percent'?covert_to_usd($data->discount_amount):$data->discount_amount;
        $coupon->discount_type = $data->discount_type;
        $coupon->maximum_discount_amount = covert_to_usd($data->maximum_discount_amount);
        $coupon->start_date = $data->start_date ? date('Y-m-d', strtotime($data->start_date)) : null;
        $coupon->end_date = $data->end_date ? date('Y-m-d', strtotime($data->end_date)) : null;
        $coupon->status = $data->status;
        $coupon->is_sellable = $data->is_sellable;
        $coupon->points_to_buy = $data->points_to_buy;
        $coupon->is_new_user = $data->is_new_user;
        $coupon->deadline = $data->deadline;
        $coupon->platform = $data->platform;
        $coupon->update();

        return response()->json(['status' => true, 'load' => true, 'message' => 'Coupon updated successfully.']);
    }

    public function delete($id)
    {
        $coupon = Coupon::findOrFail($id);
        return $coupon->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Coupon not found.'], 404);
        }

        $coupon->status = $request->input('status');
        $coupon->save();
        return response()->json(['success' => true, 'message' => 'Coupon status updated successfully.']);
    }

    public function findByCoupon($couponCode)
    {
        return Coupon::where('status', 1)->where('coupon_code', $couponCode)->first();
    }

    public function userCoupon($couponId)
    {
        return UserCoupon::where('user_id', Auth::guard('customer')->user()->id)->where('coupon_id', $couponId)->first();
    }

    public function checkCoupon($data)
    {
        // 1. Validate Request
        $validator = Validator::make($data, [
            'coupon_code' => 'required|string|min:5|max:50|exists:coupons,coupon_code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        // 2. Fetch Coupon
        $coupon = $this->findByCoupon($data['coupon_code']);
        if (!$coupon) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon not found.'
            ]);
        }

        $today = date('Y-m-d');

        // 4. Check Deadline Date
        if ($coupon->deadline && $coupon->deadline < $today) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon expired on ' . get_system_date($coupon->deadline)
            ]);
        }

        $userId = Auth::guard('customer')->user()->id;

        // 5. New User Only Coupon Check
        if ($coupon->is_new_user_only) {
            $hasPreviousOrder = Order::where('user_id', $userId)->exists();
            if ($hasPreviousOrder) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon is only valid for new users.'
                ]);
            }
        }

        // 6. Mobile App Only Coupon Check
        $isAppRequest = request()->header('X-App-Request') === 'mobile'; // Optional header from app
        if ($coupon->is_mobile_app_only && !$isAppRequest) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon is only valid on the mobile app.'
            ]);
        }


        // 5. Coupon Usage Check (Free vs. Paid)
        if ($coupon->is_sellable == 0) {
            // Free coupon: Check if already used
            if ($this->userCoupon($coupon->id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'This coupon has already been used.'
                ]);
            }
        } else {
            // Paid coupon: Check if user has it and hasn’t used it
            $userCoupon = UserBroughtCoupon::where('user_id', $userId)
                ->where('coupon_id', $coupon->id)
                ->first();

            if (!$userCoupon) {
                return response()->json([
                    'status' => false,
                    'message' => "You don't own this coupon."
                ]);
            }

            if ($userCoupon->status == 1) {
                return response()->json([
                    'status' => false,
                    'message' => "You have already used this coupon."
                ]);
            }
        }

        // 6. Calculate Cart Total and Taxes
        $cart = Cart::where('user_id', $userId)->first();
        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Your cart is empty.'
            ]);
        }

        $items = CartDetail::where('cart_id', $cart->id)->get();
        $totalPrice = 0;
        $taxAmount = 0;
        $productRepository = app(Interface\ProductRepositoryInterface::class);
        $cartUpdated = false;

        foreach ($items as $item) {
            $stockResponse = getProductStock($item->product_id);
            if (!$stockResponse['status']) {
                // Remove out-of-stock items from cart
                $itemQuantity = $item->quantity;
                $item->delete();
                $cart->total_quantity -= $itemQuantity;
                $cart->save();
                $cartUpdated = true;
                continue;
            }

            // Apply taxes if available
            if ($item->product->taxes->isNotEmpty()) {
                foreach ($item->product->taxes as $tax) {
                    $taxAmount += ($tax->tax_type == 'percent')
                        ? (($item->product->unit_price * $tax->tax) / 100) * $item->quantity
                        : ($tax->tax * $item->quantity);
                }
            }

            // Calculate price
            $price = $productRepository->discountPrice($item->product);
            $totalPrice += ($price * $item->quantity);
        }

        if ($cartUpdated) {
            return response()->json([
                'status' => false,
                'message' => 'Some products in your cart are out of stock and have been removed. Please try again.'
            ]);
        }

        // 7. Check Minimum Shipping Amount
        if ($coupon->minimum_shipping_amount > $totalPrice) {
            return response()->json([
                'status' => false,
                'message' => 'Minimum ' . format_price(convert_price($coupon->minimum_shipping_amount)) . ' is required to apply this coupon.'
            ]);
        }

        // 8. Calculate Discount
        $discountAmount = 0;
        if ($coupon->discount_type === 'percent') {
            $discountAmount = ($totalPrice * $coupon->discount_amount) / 100;
        } elseif ($coupon->discount_type === 'amount') {
            $discountAmount = $coupon->discount_amount;
        }

        // Apply max discount cap
        if ($coupon->maximum_discount_amount != 0 && $coupon->maximum_discount_amount < $discountAmount) {
            $discountAmount = $coupon->maximum_discount_amount;
        }

        // 9. Shipping Cost
        $shippingCharge = 0;
        if (get_settings('shipping_cost_type') === 'flat_rate') {
            $shippingCharge = get_settings('system_default_delivery_charge');
        }

        // 10. Final Amounts
        $discountedPrice = $totalPrice - $discountAmount;
        $totalAmount = ($totalPrice + $taxAmount + $shippingCharge) - $discountAmount;

        // 11. Return Response
        return response()->json([
            'status' => true,
            'message' => 'Coupon applied successfully.',
            'formatted_amount' => format_price(convert_price($discountAmount)),
            'total_amount' => format_price(convert_price($totalAmount)),
            'amount' => $discountedPrice,
            'discount_amount' => convert_price($discountAmount),
        ]);
    }


    public function buyCoupon($data)
    {
        $validator = Validator::make($data, [
            'coupon_code' => 'required|string|min:5|max:50|exists:coupons,coupon_code',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }

        if(!Auth::guard('customer')->check()) {
            return response()->json(['status' => false, 'message' => 'You need to login to buy this coupon.']);
        }

        // check the coupon
        $coupon = Coupon::where('coupon_code', $data['coupon_code'])->where('status', 1)->where('is_sellable', 1)->first();
        if(!$coupon) {
            return response()->json(['status' => false, 'message' => 'Coupon not found']);
        }

        // check the user is already used this
        $userCoupon = $this->userCoupon($coupon->id);
        if($userCoupon) {
            return response()->json(['status' => false, 'message' => 'This coupon is already used.']);
        }

        // check start_date & end_date
        // if($coupon->start_date && ($coupon->start_date > date('Y-m-d'))) {
        //     return response()->json(['status' => false, 'message' => 'You can use this coupon after '. get_system_date($coupon->start_date)]);
        // }

        // check minimum shipping amount
        if($coupon->end_date && ($coupon->end_date < date('Y-m-d'))) {
            return response()->json(['status' => false, 'message' => 'This coupon is expired on '. get_system_date($coupon->end_date)]);
        }

        $user = User::find(Auth::guard('customer')->user()->id);
        $userPoints = $user->points;

        if($coupon->points_to_buy > $userPoints) {
            return response()->json(['status' => false, 'message' => "You don't have enough points to buy this coupon"]);
        }

        $buyCoupon = UserBroughtCoupon::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id,
            'status' => 0
        ]);

        if($buyCoupon) {
            $user->points -= $coupon->points_to_buy;
            $user->save();

            if($user) {
                UserPoint::create([
                    'user_id' => $user->id,
                    'quantity' => 1,
                    'points' => $coupon->points_to_buy,
                    'notes' => 'Coupon Bought',
                    'method' => 'minus'
                ]);
            }
        }

        // return true
        return response()->json(['status' => true, 'message' => 'Coupon bought Successfully.']);
    }

    public function assignCoupon($request)
    {
        if(is_array($request->coupon_id) && count($request->coupon_id) > 0) {
            foreach($request->coupon_id as $coupon) {
                if(is_array($request->user_id) && count($request->user_id) > 0) {
                    foreach($request->user_id as $user) {
                        UserBroughtCoupon::create([
                            'user_id' => $user,
                            'coupon_id' => $coupon,
                            'status' => 0
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => true, 'message' => 'Coupon Assigned Successfully.', 'load' => true]);
    }
}
