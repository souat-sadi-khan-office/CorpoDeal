<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Repositories\DashboardRepository;
use App\Repositories\Interface\AdminRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Interface\BrandRepositoryInterface;
use App\Repositories\Interface\CustomerRepositoryInterface;
use App\Repositories\Interface\CategoryRepositoryInterface;
use App\Repositories\Interface\DashBoardRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
//    private $customer;
//    private $category;
//    private $brand;
    private $dashboard;
    private $admin;

    public function __construct(
//        BrandRepositoryInterface $brand,
//        CustomerRepositoryInterface $customer,
//        CategoryRepositoryInterface $category,
        DashBoardRepository $dashboard,
        AdminRepositoryInterface $admin,
    ) {
//        $this->customer = $customer;
//        $this->category = $category;
//        $this->brand = $brand;
        $this->dashboard = $dashboard;
        $this->admin = $admin;
    }

    public function index()
    {
        $data = $this->dashboard->index(true);
        return view('backend.dashboard', compact('data'));
    }

    public function profile()
    {
        $logs = ActivityLog::where('admin_id', Auth::guard('admin')->user()->id)->orderBy('id', 'desc')->limit(25)->get();
        return view('backend.profile', compact('logs'));
    }

    public function profileUpdate(Request $request)
    {
        $admin = $this->admin->updateAdmin(Auth::guard('admin')->user()->id, $request->all());

        if ($admin) {
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully'
            ]);
        }
    }

    public function passwordUpdate(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors(),
            ]);
        }

        $user = Auth::guard('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Old password does not match'
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        Auth::guard('admin')->logout();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully. Please try to login',
            'goto' => route('admin.login')
        ]);

    }

    public function offlineOrder()
    {
        return view('backend.customers.offline-order');
    }
    

    public function contactMessage(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('contact-message.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            $models = ContactMessage::all();
            return DataTables::of($models)
                ->addIndexColumn()
                ->editColumn('date', function ($model) {
                    return get_system_date($model->created_at) . ' '. get_system_time($model->created_at);
                })
                ->editColumn('status', function ($model) {
                    $checked = $model->status == 1 ? 'checked' : '';
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.message.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                })
                ->addColumn('action', function ($model) {
                    return view('backend.message.action', compact('model'));
                })
                ->rawColumns(['action', 'status', 'date'])
                ->make(true);
        }

        return view('backend.message.index');
    }

    public function deleteContactMessage($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('contact-message.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $model = ContactMessage::findOrFail($id);
        $model->delete();

        return response()->json(['status' => true, 'message' => 'Record deleted successfully', 'load' => true]);
    }

    public function updateMessageStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $model = ContactMessage::find($id);

        if (!$model) {
            return response()->json(['success' => false, 'message' => 'Message not found.'], 404);
        }

        $model->status = $request->input('status');
        $model->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    public function viewContactMessage($id)
    {
        $model = ContactMessage::findOrFail($id);
        return view('backend.message.show', compact('model'));
    }

    public function offlineOrderStore(Request $request)
    {
        return $this->admin->createOrder($request);
    }
}
