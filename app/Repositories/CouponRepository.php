<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\Cart;
use App\Models\User;
use App\Models\CartDetail;
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
            'start_date' => $data->start_date ? date('Y-m-d', strtotime($data->start_date)) : null,
            'end_date' => $data->end_date ? date('Y-m-d', strtotime($data->end_date)) : null,
            'status' => $data->status,
            'is_sellable' => $data->is_sellable,
            'points_to_buy' => $data->points_to_buy,
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
        $validator = Validator::make($data, [
            'coupon_code' => 'required|string|min:5|max:50|exists:coupons,coupon_code',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'validator' => true, 'message' => $validator->errors()]);
        }

        // check the coupon
        $coupon = $this->findByCoupon($data['coupon_code']);
        if(!$coupon) {
            return response()->json(['status' => false, 'message' => 'Coupon not found']);
        }

        // check start_date & end_date
        if($coupon->start_date && ($coupon->start_date > date('Y-m-d'))) {
            return response()->json(['status' => false, 'message' => 'You can use this coupon after '. get_system_date($coupon->start_date)]);
        }

        // check minimum shipping amount
        if($coupon->end_date && ($coupon->end_date < date('Y-m-d'))) {
            return response()->json(['status' => false, 'message' => 'This coupon is expired on '. get_system_date($coupon->end_date)]);
        }

        // check the user is already used this and this is a free coupon
        if($coupon->is_sellable == 0) {
            $userCoupon = $this->userCoupon($coupon->id);
            if($userCoupon) {
                return response()->json(['status' => false, 'message' => 'This coupon is already used.']);
            }
        }

        if($coupon->is_sellable == 1) {
            $userBoughtCoupon = UserBroughtCoupon::where('user_id', Auth::guard('customer')->user()->id)->where('coupon_id', $coupon->id)->first();
            if(!$userBoughtCoupon) {
                return response()->json(['status' => false, 'message' => "You don't have this coupon"]);
            }

            if($userBoughtCoupon->status == 1) {
                return response()->json(['status' => false, 'message' => "You already used this coupon."]);
            }
        }

        // set up discount amount
        $total_price = 0;
        $discounted_amount = 0;
        $discounted_price = 0;
        $tax_amount = 0;

        $cart = Cart::where('user_id', Auth::guard('customer')->user()->id)->first();
        $items = CartDetail::where('cart_id', $cart->id)->get();

        foreach ($items as $item) {
            $stockResponse = getProductStock($item->product_id);
            if (!$stockResponse['status']) {
                $cart_updated = true;
                $itemQuantity = $item->quantity;
                $item->delete();
                $cart->total_quantity -= $itemQuantity;
                $cart->save();
            } else {
                $productRepository = app(Interface\ProductRepositoryInterface::class);;

                if ($item->product->taxes->isNotEmpty()) {
                    foreach ($item->product->taxes as $tax) {
                        if ($tax->tax_type == 'percent') {
                            $product_tax_amount = (($item->product->unit_price * $tax->tax) / 100) * $tax->quantity;
                        } else {
                            $product_tax_amount = ($tax->tax * $item->quantity);
                        }
                    }

                    $tax_amount += $product_tax_amount;
                }

                $price = $productRepository->discountPrice($item->product);
                $total_price += ($price * $item->quantity);
            }
        }

        if($coupon->minimum_shipping_amount > $total_price) {
            return response()->json(['status' => false, 'message' => 'Minimum '. format_price(convert_price($coupon->minimum_shipping_amount)) .' is required to apply this coupon']);
        }

        // check maximum_discount_amount
        if ($coupon->discount_type == 'percent') {
            $discounted_amount = ($total_price * $coupon->discount_amount) / 100;
        } elseif ($coupon->discount_type == 'amount') {
            $discounted_amount = $coupon->discount_amount;
        }

        if($coupon->maximum_discount_amount != 0 && $coupon->maximum_discount_amount < $discounted_amount) {
            $discounted_amount = $coupon->maximum_discount_amount;
        }

        $shipping_charge = get_settings('system_default_delivery_charge')??10;
        $discounted_price = $total_price - $discounted_amount;
        $total_amount = ($total_price + $tax_amount + $shipping_charge) - $discounted_amount;


        // return true
        return response()->json(['status' => true, 'message' => 'Coupon Added Successfully.', 'formatted_amount' => format_price(convert_price($discounted_amount)), 'total_amount' => format_price(convert_price($total_amount)), 'amount' => $discounted_price, 'discount_amount' => convert_price($discounted_amount) ]);
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
