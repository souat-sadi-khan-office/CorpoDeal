<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interface\CustomerQuestionRepositoryInterface;

class QuestionController extends Controller
{
    private $customer;

    public function __construct(CustomerQuestionRepositoryInterface $customer)
    {
        $this->customer = $customer;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer-question.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            if($request->product_id != null) {
                return $this->customer->dataTableWithAjaxSearch($request->product_id);
            } else {
                return $this->customer->dataTable();
            }
        }
        
        $product_id = $request->product_id;

        return view('backend.question.index', compact('product_id'));
    }

    public function answer($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer-question.answer') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $question = $this->customer->findQuestionById($id);
        $answer = null;
        if($question->answer) {
            $answer = $this->customer->findAnswerById($question->answer->id);
        }

        return view('backend.question.answer', compact('question', 'answer'));
    }

    public function submitAnswer(Request $request) 
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer-question.answer') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }
        
        return $this->customer->updateOrCreateAnswer($request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer-question.answer') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->customer->destroyQuestion($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Query deleted successfully"
        ]);
    }
}
