<?php

namespace App\Http\Controllers\Admin;

use TCPDF;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use App\Http\Controllers\Controller;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\Interface\OrderRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;

class OrderController extends Controller
{
    private $orderRepository;
    private $userRepository;
    private $productRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        UserRepositoryInterface $userRepository,
        ProductRepositoryInterface $productRepository,
    ) {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
    }


    public function index(Request $request)
    {
        switch($request->status) {
            case 'pending':
                if (auth()->guard('admin')->user()->hasPermissionTo('pending-order.view') === false) {
                    return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
                }
            break;
            case 'packaging':
                if (auth()->guard('admin')->user()->hasPermissionTo('packaging-order.view') === false) {
                    return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
                }
            break;
            case 'shipping':
                if (auth()->guard('admin')->user()->hasPermissionTo('shipping-order.view') === false) {
                    return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
                }
            break;
            case 'out_of_delivery':
                if (auth()->guard('admin')->user()->hasPermissionTo('confirm-order.view') === false) {
                    return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
                }
            break;
            case 'delivered':
                if (auth()->guard('admin')->user()->hasPermissionTo('delivered-order.view') === false) {
                    return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
                }
            break;
            case 'returned':
                if (auth()->guard('admin')->user()->hasPermissionTo('returned-order.view') === false) {
                    return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
                }
            break;
            case 'failed':
                if (auth()->guard('admin')->user()->hasPermissionTo('failed-order.view') === false) {
                    return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
                }
            break;
            case 'refund_requested':
                if (auth()->guard('admin')->user()->hasPermissionTo('refund-requested-order.view') === false) {
                    return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
                }
            break;
            default: 
                if (auth()->guard('admin')->user()->hasPermissionTo('all-order.view') === false) {
                    return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
                }
            break;
        }
        
        $orders = $this->orderRepository->index($request);
        if ($request->ajax()) {
            return $this->orderRepository->indexDatatable($orders);
        }

        $status = $request->status;
        $payment_status = $request->payment_status;
        $refund_requested = $request->refund_requested;
        return view('backend.order.index', compact('status', 'payment_status', 'refund_requested'));
    }

    public function details($id)
    {

        $order = $this->orderRepository->details($id);
        return view('backend.order.details', compact('order'));
    }
    public function invoice($id, Request $request)
    {
        $order = $this->orderRepository->details($id);
        return view('backend.order.invoice', compact('order','request'));
    
    }
    


    public function updateStatus(Request $request, $orderId)
    {
        return $this->orderRepository->updateStatus($request, $orderId);
    }
}
