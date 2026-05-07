<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhoneBookApiController extends Controller
{
    private function userId()
    {
        return auth('api')->user()->id ?? null;
    }

    /**
     * Get list of user phones
     *
     * Get all phone book entries for authenticated user.
     *
     * @group Phone Book
     * @authenticated
     *
     * @header Accept application/json
     * @header X-Api-Token fgnQQIOVNRcpSRy
     * @header Authorization Bearer {sanctum_token}
     *
     * @response 200 [
     *  {
     *    "id": 1,
     *    "user_id": 5,
     *    "phone_number": "01315501001",
     *    "is_default": true,
     *    "is_verified": false,
     *    "code": null,
     *    "created_at": "2025-07-29T11:00:00.000000Z",
     *    "updated_at": "2025-07-29T11:00:00.000000Z"
     *  }
     * ]
     */
    public function index()
    {
        $models = UserPhone::where('user_id', $this->userId())->orderBy('id', 'DESC')->get();

        return response()->json($models);
    }

    /**
     * Store a new phone in user phone book
     *
     * Adds a new phone number for the authenticated user.
     *
     * @group Phone Book
     * @authenticated
     *
     * @header Accept application/json
     * @header X-Api-Token fgnQQIOVNRcpSRy
     * @header Authorization Bearer {sanctum_token}
     *
     * @bodyParam phone_number string required The phone number. Example: 01315501001
     * @bodyParam is_default boolean required Set as default phone number. Example: true
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Record created successfully"
     * }
     *
     * @response 422 {
     *   "phone_number": ["The phone number field is required."],
     *   "is_default": ["The is default field is required."]
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|min:8|max:15',
            'is_default'   => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if($request->is_default == 1) {
            UserPhone::where('user_id', $this->userId())->update(['is_default' => 0]);
        }

        UserPhone::create([
            'user_id' => $this->userId(),
            'phone_number' => $request->phone_number,
            'is_default' => $request->is_default,
            'is_verified' => 0,
            'code' => null
        ]);

        return response()->json([
            'status' => true, 
            'message' => 'Record created successfully'
        ]);
    }

    /**
     * Get details of a phone entry
     *
     * Get a single phone entry by ID for the authenticated user.
     *
     * @group Phone Book
     * @authenticated
     *
     * @urlParam id int required The phone entry ID. Example: 1
     *
     * @header Accept application/json
     * @header X-Api-Token fgnQQIOVNRcpSRy
     * @header Authorization Bearer {sanctum_token}
     *
     * @response 200 {
     *   "id": 1,
     *   "user_id": 5,
     *   "phone_number": "01315501001",
     *   "is_default": true,
     *   "is_verified": false,
     *   "code": null,
     *   "created_at": "2025-07-29T11:00:00.000000Z",
     *   "updated_at": "2025-07-29T11:00:00.000000Z"
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\UserPhone]."
     * }
     */
    public function show($id)
    {
        $model = UserPhone::where('user_id', $this->userId())->where('id', $id)->firstOrFail();
        return response()->json($model);
    }

     /**
     * Update phone book entry
     *
     * Update phone number and default status for the given phone entry ID.
     *
     * @group Phone Book
     * @authenticated
     *
     * @urlParam id int required The phone entry ID to update. Example: 1
     *
     * @header Accept application/json
     * @header X-Api-Token fgnQQIOVNRcpSRy
     * @header Authorization Bearer {sanctum_token}
     *
     * @bodyParam phone_number string required The phone number. Example: 01315501001
     * @bodyParam is_default boolean required Set as default phone number. Example: true
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Phone updated successfully."
     * }
     *
     * @response 422 {
     *   "phone_number": ["The phone number field is required."],
     *   "is_default": ["The is default field is required."]
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\UserPhone]."
     * }
     */
    public function update(Request $request, $id)
    {
        $phone = UserPhone::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|min:8|max:15',
            'is_default'   => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if($request->is_default == 1) {
            UserPhone::where('user_id', $this->userId())->update(['is_default' => 0]);
        }

        $phone->phone_number = $request->phone_number;
        $phone->is_default = $request->is_default;
        $phone->save();

        return response()->json([
            'status' => true,
            'message' => 'Phone updated successfully.'
        ]);
    }

     /**
     * Delete phone book entry (disabled)
     *
     * Deletion is blocked from customer portal.
     *
     * @group Phone Book
     * @authenticated
     *
     * @urlParam id int required The phone entry ID to delete. Example: 1
     *
     * @header Accept application/json
     * @header X-Api-Token fgnQQIOVNRcpSRy
     * @header Authorization Bearer {sanctum_token}
     *
     * @response 403 {
     *   "status": false,
     *   "message": "Delete Option is Block from Customer Portal"
     * }
     */
    public function destroy($id)
    {
        return response()->json([
            'status' => false,
            'message' => 'Delete Option is Block from Customer Portal'
        ]);
    }
}
