<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Models\ProductQuestion;
use App\Models\ProductQuestionAnswer;
use App\Repositories\Interface\CustomerQuestionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CustomerQuestionRepository implements CustomerQuestionRepositoryInterface
{
    public function getAllQuestions()
    {
        return ProductQuestion::all();
    }

    public function dataTable()
    {
        $models = $this->getAllQuestions();
            return DataTables::of($models)
                ->addIndexColumn()
                ->editColumn('question', function ($model) {
                    return $model->message;
                })
                ->editColumn('date', function ($model) {
                    return get_system_date($model->created_at) . ' '. get_system_time($model->created_at);
                })
                ->editColumn('product', function ($model) {
                    return '<div class="row"><div class="col-auto">' . Images::show($model->product->thumb_image) . '</div><div class="col">' . $model->product->name . '</div></div>';
                })
                ->editColumn('status', function ($model) {
                    $checked = $model->status == 1 ? 'checked' : '';
                    if (auth()->guard('admin')->user()->hasPermissionTo('customer-review.update') === false) {
                        $bg = $model->status == 1 ? 'success' : 'warning';
                        $status = $model->status == 1 ? 'Active' : 'Inactive';

                        return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                    } else {
                        return '<div class="form-check form-switch"><input data-url="' . route('admin.customer-review.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                    }
                })
                ->addColumn('action', function ($model) {
                    return view('backend.question.action', compact('model'));
                })
                ->rawColumns(['action', 'date', '', 'product', 'status'])
                ->make(true);
    }

    public function dataTableWithAjaxSearch($productId)
    {
        $models = $this->getAllQuestionsForProduct($productId);
            return Datatables::of($models)
                ->addIndexColumn()
                ->editColumn('question', function ($model) {
                    return $model->message;
                })
                ->editColumn('date', function ($model) {
                    return get_system_date($model->created_at) . ' '. get_system_time($model->created_at);
                })
                ->editColumn('status', function ($model) {
                    $checked = $model->status == 1 ? 'checked' : '';
                    if (auth()->guard('admin')->user()->hasPermissionTo('customer-question.answer') === false) {
                        $bg = $model->status == 1 ? 'success' : 'warning';
                        $status = $model->status == 1 ? 'Active' : 'Inactive';

                        return '<span class="badge bg-'. $bg .'">'. $status .'</span>';
                    } else {
                        return '<div class="form-check form-switch"><input data-url="' . route('admin.customer.question.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                    }
                })
                ->addColumn('action', function ($model) {
                    return view('backend.question.action', compact('model'));
                })
                ->rawColumns(['action', 'date', 'question', 'status'])
                ->make(true);
    }

    public function getAllQuestionsForProduct($productId)
    {
        return ProductQuestion::where('product_id', $productId)->orderBy('id', 'DESC')->get();
    }

    public function findQuestionById($id)
    {
        return ProductQuestion::with('answer')->find($id);
    }

    public function findAnswerById($id)
    {
        return ProductQuestionAnswer::find($id);
    }

    public function updateOrCreateAnswer($request)
    {
        $id = $request->id;
        $message = $request->answer;

        $question = ProductQuestion::find($id);
        if(!$question) {
            return response()->json(['status' => false, 'message' => 'Question Not Found']);
        }

        $answer = ProductQuestionAnswer::where('question_id', $question->id)->first();
        if($answer) {

            $answer->admin_id = Auth::guard('admin')->id();
            $answer->message = $message;
            $answer->save();

        } else {
            ProductQuestionAnswer::create([
                'question_id' => $question->id,
                'admin_id' => Auth::guard('admin')->id(),
                'message' => $message
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Answer added successfully.', 'load' => true]);
    }

    public function destroyQuestion($id)
    {
        $model = ProductQuestion::find($id);
        if($model) {
            ProductQuestionAnswer::where('question_id', $id)->delete();
            $model->delete();
        }

        return 1;
    }
}
