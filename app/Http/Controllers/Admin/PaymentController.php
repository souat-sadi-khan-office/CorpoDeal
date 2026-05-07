<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use DataTables;
use App\CPU\Helpers;
use App\CPU\Images;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('payments.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $models = Payment::all();
        $view = Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('status', function ($model) {

                $bg = $model->status === 'VALID' || $model->status === 'VALIDATED' ? 'success' : 'danger';

                return '<span class="badge bg-' . $bg . '">' . $model->status . '</span>';

            })
            ->editColumn('order', function ($model) {
                return strtoupper($model->payment_order_id) .'<br>'.$model?->user?->name;
            })
            ->editColumn('trx_id', function ($model) {
                return $model->trx_id;
            })
            ->editColumn('payer_id', function ($model) {
                return $model->payer_id??'N/A';
            })
            ->editColumn('amount', function ($model) {
                return $model->amount.' '.$model->currency;
            })
            ->editColumn('gateway', function ($model) {
                return $model->gateway_name;
            })
            ->rawColumns(['status', 'order', 'trx_id', 'amount', 'gateway'])
            ->make(true);

        if ($request->ajax()) {
            return $view;
        }
        return view('backend.payment.index');
    }
}
