<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Jobs\SendAdminCustomerCreateMail;
use App\Models\ProductQuestion;
use App\Models\Rating;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserPhone;
use App\Models\WishList;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function getAllCustomers()
    {
        return User::orderBy('id', 'DESC')->get();
    }

    public function dataTable()
    {
        $models = $this->getAllCustomers();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('customer', function($model) {

                $html = '<div class="row">';
                if($model->provider_name == 'google' || $model->provider_name == 'facebook') {
                    $html .= '<div class="col-auto">' . Images::show($model->avatar) . '</div>';
                } else {
                    $html .= '<div class="col-auto">' . Images::show(($model->avatar)) . '</div>';
                }

                

                $html .= '<div class="col">';

                if($model->is_premium == 1) {
                    $html .= '<span class="badge bg-warning"> <i class="bi bi-award-fill"></i> Premium User</span> <br>';
                }

                          $html .= 'Name: ' . $model->name . '<br>
                            Email: '. $model->email .'<br>
                            ';

                if($model->provider_name) {
                    $html .= 'Provider: '. $model->provider_name .'<br>';
                }

                if($model->is_deleted == 1) {
                    $html .= 'User Deleted: <span class="badge bg-danger">Deleted</span> <br>';
                }

                $html .= 'Star Points: '. $model->points .'<br>';

                $html .= '</div></div>';
                return $html;
            })
            ->editColumn('currency', function ($model) {
                return $model->currency->name;
            })
            ->editColumn('created_at', function ($model) {
                return get_system_date($model->created_at) . ' '. get_system_time($model->created_at);
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';

                if($model->is_deleted == 1) {
                    return '<span class="badge bg-danger">Deleted</span>';
                }

                if (auth()->guard('admin')->user()->hasPermissionTo('website-setup.home-page-category') === false) {
                    $bg = $model->status == 1 ? 'success' : 'warning';
                    $status = $model->status == 1 ? 'Active' : 'Inactive';

                    return '<span class="badge bg-'. $bg .'">'. $status . '</span>';
                } else {
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.customer.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                }
            })
            ->addColumn('action', function ($model) {
                return view('backend.customers.action', compact('model'));
            })
            ->editColumn('provider', function($model) {
                return $model->provider_name == null ? 'Normal Login User': ucfirst($model->provider_name) . ' User';
            })
            ->rawColumns(['action', 'customer', 'status', 'currency'])
            ->make(true);
    }

    public function findCustomerById($id)
    {
        return User::findOrFail($id);
    }

    public function createCustomer($data)
    {
        $user = User::create([
            'currency_id' => $data->currency_id,
            'name' => $data->name,
            'email' => $data->email,
            'avatar' => "pictures/user.png",
            'password' => Hash::make($data->password),
            'status' => $data->status
        ]);

        // Dispatch the email job
        SendAdminCustomerCreateMail::dispatch($user,$data->password)->onQueue('medium');

        if(isset($data['from']) && $data['from'] == 'offline-sale') {
            $json = ['status' => true, 'message' => 'Customer created successfully'];
        } else {
            $json = ['status' => true, 'goto' => route('admin.customer.index'), 'message' => 'Customer created successfully'];
        }

        return response()->json($json);
    }

    public function updateCustomer($id, $data)
    {
        $customer = User::findOrFail($id);
        $customer->name = $data->name;
        $customer->currency_id = $data->currency_id;
        $customer->status = $data->status;
        $customer->is_premium = $data->is_premium;
        $customer->email = $data->email;
        if($data->password != ''){
            $customer->password = Hash::make($data->password);
        }
        $customer->update();

        return response()->json(['status' => true, 'load' => true, 'message' => 'Customer updated successfully.']);
    }

    public function deleteCustomer($id)
    {
        $customer = User::findOrFail($id);
        return $customer->delete();
    }

    public function updateStatus($request, $id) 
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $customer = User::find($id);

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Customer not found.'], 404);
        }

        $customer->status = $request->input('status');
        $customer->save();

        return response()->json(['success' => true, 'message' => 'Customer status updated successfully.']);
    }
    
    public function updateQuestionStatus($request, $id) 
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $question = ProductQuestion::find($id);

        if (!$question) {
            return response()->json(['success' => false, 'message' => 'Question not found.'], 404);
        }

        $question->status = $request->input('status');
        $question->save();

        return response()->json(['success' => true, 'message' => 'Customer status updated successfully.']);
    }

    public function findCustomerAddressById($id)
    {
        return UserAddress::find($id);
    }

    public function storeAddress($request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'is_default' => 'nullable|numeric',
            'address' => 'required|string|max:255',
            'address2' => 'nullable|string',
            'company_name' => 'nullable|string',
            'area' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'zone_id' => 'required|numeric',
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }

        if($request->is_default == 1) {
            UserAddress::where('user_id', $request->user_id)
               ->where('is_default', 1)
               ->update(['is_default' => 0]);
        }

        UserAddress::create([
            'user_id' => $request->user_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'is_default' => $request->is_default,
            'address' => $request->address,
            'address_line_2' => $request->address_line_2,
            'area' => $request->area,
            'postcode' => $request->postcode,
            'zone_id' => $request->zone_id,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'company_name' => $request->company_name
        ]);

        return response()->json(['status' => true, 'message' => 'Address Added Successfully.', 'load' => true]);
    }

    public function updateAddress($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'is_default' => 'nullable|numeric',
            'address' => 'required|string|max:255',
            'address2' => 'nullable|string',
            'company_name' => 'nullable|string',
            'area' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'zone_id' => 'required|numeric',
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }

        $model = UserAddress::find($id);
        
        if($request->is_default == 1) {
            UserAddress::where('user_id', $model->user_id)
               ->where('is_default', 1)
               ->update(['is_default' => 0]);
        }

        $model->first_name = $request->first_name;
        $model->last_name = $request->last_name;
        $model->is_default = $request->is_default;
        $model->address = $request->address;
        $model->address_line_2 = $request->address_line_2;
        $model->area = $request->area;
        $model->postcode = $request->postcode;
        $model->zone_id = $request->zone_id;
        $model->country_id = $request->country_id;
        $model->city_id = $request->city_id;
        $model->company_name = $request->company_name;
        $model->save();

        return response()->json(['status' => true, 'message' => 'Address Updated Successfully.', 'load' => true]);
    }

    public function destroyAddress($id)
    {
        $customer = UserAddress::findOrFail($id);
        return $customer->delete();
    }

    public function storePhone($request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'phone_number' => 'required|string|max:255',
            'is_default' => 'nullable|numeric',
            'is_verified' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }

        if($request->is_default == 1) {
            UserPhone::where('user_id', $request->user_id)
               ->where('is_default', 1)
               ->update(['is_default' => 0]);
        }

        UserPhone::create([
            'user_id' => $request->user_id,
            'phone_number' => $request->phone_number,
            'is_verified' => $request->is_verified,
            'is_default' => $request->is_default,
        ]);

        return response()->json(['status' => true, 'message' => 'Phone Added Successfully.', 'load' => true]);
    }

    public function findCustomerPhoneById($id)
    {
        return UserPhone::find($id);
    }

    public function updatePhone($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:255',
            'is_default' => 'nullable|numeric',
            'is_verified' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()]);
        }

        $model = UserPhone::find($id);

        if($request->is_default == 1) {
            UserPhone::where('user_id', $model->user_id)
               ->where('is_default', 1)
               ->update(['is_default' => 0]);
        }
        
        $model->phone_number = $request->phone_number;
        $model->is_default = $request->is_default;
        $model->is_verified = $request->is_verified;
        $model->save();

        return response()->json(['status' => true, 'message' => 'Phone Updated Successfully.', 'load' => true]);
    }

    public function destroyPhone($id)
    {
        $customer = UserPhone::findOrFail($id);
        return $customer->delete();
    }

    public function destroyCustomerWishList($id)
    {
        $model = WishList::find($id);
        return $model->delete();
    }

    public function destroyRating($id)
    {
        $model = Rating::find($id);
        return $model->delete();
    }
}