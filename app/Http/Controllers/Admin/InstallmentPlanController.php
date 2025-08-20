<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Repositories\Interface\InstallmentPlanInterface;
use Illuminate\Http\Request;

class InstallmentPlanController extends Controller
{
    private $installment;

    public function __construct(InstallmentPlanInterface $installment)
    {
        $this->installment = $installment;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('installment-plans.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $plans = $this->installment->plansIndex($request);

        return view('backend.installments.index', compact('plans'));
    }

    public function create(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('installment-plans.create') === false) {
            return response()->json([
                'status' => false,
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return view('backend.installments.create');
    }

    public function store(Request $request)
    {
        $response = $this->installment->storePlan($request);
        return response()->json($response);
    }

    public function status($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('installment-plans.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->installment->planStatus($id);
    }


    //Negative balance
    public function balanceRequests(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('installment-plans.view-balance-request') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $data = $this->installment->balanceRequests($request);
        if ($request->ajax()) {
            return $this->installment->balanceRequestsDatatable($data);
        }
        return view('backend.balance-request.index');
    }

    public function balanceRequest($id)
    {

        $data=$this->installment->balanceRequest($id);
        return view('backend.balance-request.details',compact('data'));
    }

    public function requestUpdate(Request $request,$id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('installment-plans.update-balance-request') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->installment->requestUpdate($request,$id);
    }

    public function negativeBalance()
    {
        $history = $this->installment->myNegativeBalance();
        $plans = $this->installment->plansIndex(null)->where('status', 1);
        $currencies = Currency::where('status', 1)->select('id', 'name', 'code', 'symbol')->get();
        return view('frontend.customer.negative_balance', compact('history', 'plans', 'currencies'));
    }

    public function negativeBalanceStore(Request $request)
    {
        return $this->installment->negativeBalanceStore($request);
    }
}
