<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FlashDealRequest;
use App\Models\FlashDealType;
use App\Repositories\Interface\FlashDealRepositoryInterface;
use Illuminate\Http\Request;

class FlashDealController extends Controller
{
    private $dealRepository;

    public function __construct(FlashDealRepositoryInterface $dealRepository)
    {
        $this->dealRepository = $dealRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('flash-deal.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->dealRepository->dataTable();
        }

        return view('backend.deal.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('flash-deal.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('backend.deal.create');
    }

    public function store(FlashDealRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('flash-deal.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->dealRepository->createDeal($request);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('flash-deal.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->dealRepository->findDealById($id);
        $products = FlashDealType::with('product')->where('flash_deal_id', $id)->get();

        $pp = $products->map(function($deal) {
            return $deal->product;
        });
        return view('backend.deal.edit', compact('model', 'products', 'pp'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('flash-deal.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->dealRepository->updateDeal($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('flash-deal.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->dealRepository->deleteDeal($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Brand deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('flash-deal.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->dealRepository->updateStatus($request, $id);
    }
}
