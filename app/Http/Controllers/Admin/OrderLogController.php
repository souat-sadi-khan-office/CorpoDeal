<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderLogController extends Controller
{
    public function create($id)
    {
        $order = Order::findOrFail($id);

        return view('backend.order.log.create', compact('order'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model = new OrderLog();
        $model->order_id = $request->order_id;
        $model->user_id = auth()->guard('admin')->user()->id;
        $model->subject = $request->subject;
        $model->content = $request->content;
        $model->save();

        return response()->json([
            'status' => true, 
            'load' => true, 
            'message' => 'Order Log Created'
        ]);
    }
}
