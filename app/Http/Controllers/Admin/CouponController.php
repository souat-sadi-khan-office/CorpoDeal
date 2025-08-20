<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interface\CouponRepositoryInterface;
use App\Repositories\Interface\CustomerRepositoryInterface;

class CouponController extends Controller
{
    private $couponRepository;
    private $customerRepository;

    public function __construct(
        CouponRepositoryInterface $couponRepository,
        CustomerRepositoryInterface $customerRepository
    )
    {
        $this->couponRepository = $couponRepository;
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('coupon.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            return $this->couponRepository->dataTable();
        }

        return view('backend.coupon.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('coupon.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.coupon.create');
    }

    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('coupon.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->couponRepository->create($request);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('coupon.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->couponRepository->find($id);
        return view('backend.coupon.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('coupon.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->couponRepository->update($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('coupon.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->couponRepository->delete($id);

        return response()->json([
            'status' => true,
            'load' => true,
            'message' => "Coupon deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('coupon.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->couponRepository->updateStatus($request, $id);
    }

    public function assignCoupon($id = null)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('coupon.assign-to-customer') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = null;
        $user_id = null;

        if(isset($request->user) && $request->user != '') {
            $user_id = $request->user;
        }

        if($id != null) {
            $model = $this->couponRepository->find($id);
        }

        $users = $this->customerRepository->getAllCustomers();
        $coupons = $this->couponRepository->all();

        return view('backend.coupon.assign', compact('model', 'user_id', 'coupons', 'users'));
    }

    public function assignToCustomer(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('coupon.assign-to-customer') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->couponRepository->assignCoupon($request);
    }
}
