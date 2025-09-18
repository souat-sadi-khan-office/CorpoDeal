<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserAddressApiController extends Controller
{
    /**
     * Get list of user addresses
     * 
     * Get all addresses belonging to the authenticated user.
     *
     * @group User Addresses
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
     *    "zone_id": 2,
     *    "country_id": 10,
     *    "city": "Dhaka",
     *    "area": "Uttara",
     *    "address": "House 12, Road 5",
     *    "postcode": "1230",
     *    "first_name": "John",
     *    "last_name": "Doe",
     *    "company_name": "Acme Ltd",
     *    "address_line_2": "Floor 3",
     *    "is_default": true,
     *    "created_at": "2025-07-29T11:00:00.000000Z",
     *    "updated_at": "2025-07-29T11:00:00.000000Z"
     *  }
     * ]
     */
    public function index()
    {
        $addresses = UserAddress::where('user_id', auth('api')->id())->get();
        return response()->json($addresses);
    }

    /**
     * Store a new user address
     *
     * Add a new address for the authenticated user.
     *
     * @group User Addresses
     * @authenticated
     *
     * @header Accept application/json
     * @header X-Api-Token fgnQQIOVNRcpSRy
     * @header Authorization Bearer {sanctum_token}
     *
     * @bodyParam city_id int required City ID. Example: 25
     * @bodyParam area string nullable Area name. Example: "Uttara"
     * @bodyParam address string required Main address line. Example: "House 12, Road 5"
     * @bodyParam postcode string required Postal code. Example: "1230"
     * @bodyParam first_name string nullable First name. Example: "John"
     * @bodyParam last_name string nullable Last name. Example: "Doe"
     * @bodyParam company_name string nullable Company name. Example: "Acme Ltd"
     * @bodyParam address_line_2 string nullable Additional address line. Example: "Floor 3"
     * @bodyParam is_default boolean required Set as default address. Example: true
     *
     * @response 201 {
     *   "status": true,
     *   "message": "Address created successfully",
     *   "data": {
     *     "id": 1,
     *     "user_id": 5,
     *     "zone_id": 2,
     *     "country_id": 10,
     *     "city_id": 25,
     *     "area": "Uttara",
     *     "address": "House 12, Road 5",
     *     "postcode": "1230",
     *     "first_name": "John",
     *     "last_name": "Doe",
     *     "company_name": "Acme Ltd",
     *     "address_line_2": "Floor 3",
     *     "is_default": true,
     *     "created_at": "2025-07-29T11:00:00.000000Z",
     *     "updated_at": "2025-07-29T11:00:00.000000Z"
     *   }
     * }
     *
     * @response 422 {
     *   "first_name": ["The first name field is required."],
     *   "postcode": ["The postcode field is required."]
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'city_id' => 'required|integer|exists:cities,id',
            'city' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'postcode' => 'required|string|max:20',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'is_default' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->is_default) {
            UserAddress::where('user_id', auth('api')->id())->update(['is_default' => false]);
        }

        $address = new UserAddress();
        $address->user_id = auth('api')->user()->id;
        $address->zone_id = 2;
        $address->country_id = 6;
        // $address->city_id = $request->city_id;
        $address->city = $request->city;
        $address->area = $request->area;
        $address->address = $request->address;
        $address->postcode = $request->postcode;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->company_name = $request->company_name;
        $address->address_line_2 = $request->address_line_2;
        $address->is_default = $request->is_default;
        $address->save();

        return response()->json([
            'status' => true,
            'message' => 'Address created successfully',
            'data' => $address
        ], 201);
    }

    /**
     * Get details of a specific user address
     *
     * Get a single address by ID for the authenticated user.
     *
     * @group User Addresses
     * @authenticated
     *
     * @urlParam id int required The address ID. Example: 1
     *
     * @header Accept application/json
     * @header X-Api-Token fgnQQIOVNRcpSRy
     * @header Authorization Bearer {sanctum_token}
     *
     * @response 200 {
     *   "id": 1,
     *   "user_id": 5,
     *   "zone_id": 2,
     *   "country_id": 10,
     *   "city_id": 25,
     *   "area": "Uttara",
     *   "address": "House 12, Road 5",
     *   "postcode": "1230",
     *   "first_name": "John",
     *   "last_name": "Doe",
     *   "company_name": "Acme Ltd",
     *   "address_line_2": "Floor 3",
     *   "is_default": true,
     *   "created_at": "2025-07-29T11:00:00.000000Z",
     *   "updated_at": "2025-07-29T11:00:00.000000Z"
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\UserAddress]."
     * }
     */
    public function show($id)
    {
        $address = UserAddress::where('user_id', auth('api')->id())->findOrFail($id);
        return response()->json($address);
    }

    /**
     * Update a user address
     *
     * Update the details of an existing address for the authenticated user.
     *
     * @group User Addresses
     * @authenticated
     *
     * @urlParam id int required The address ID to update. Example: 1
     *
     * @header Accept application/json
     * @header X-Api-Token fgnQQIOVNRcpSRy
     * @header Authorization Bearer {sanctum_token}
     *
     * @bodyParam city_id int required City ID. Example: 25
     * @bodyParam area string nullable Area name. Example: "Uttara"
     * @bodyParam address string required Main address line. Example: "House 12, Road 5"
     * @bodyParam postcode string required Postal code. Example: "1230"
     * @bodyParam first_name string nullable First name. Example: "John"
     * @bodyParam last_name string nullable Last name. Example: "Doe"
     * @bodyParam company_name string nullable Company name. Example: "Acme Ltd"
     * @bodyParam address_line_2 string nullable Additional address line. Example: "Floor 3"
     * @bodyParam is_default boolean required Set as default address. Example: true
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Address updated successfully",
     *   "data": {
     *     "id": 1,
     *     "user_id": 5,
     *     "zone_id": 2,
     *     "country_id": 10,
     *     "city_id": 25,
     *     "area": "Uttara",
     *     "address": "House 12, Road 5",
     *     "postcode": "1230",
     *     "first_name": "John",
     *     "last_name": "Doe",
     *     "company_name": "Acme Ltd",
     *     "address_line_2": "Floor 3",
     *     "is_default": true,
     *     "created_at": "2025-07-29T11:00:00.000000Z",
     *     "updated_at": "2025-07-29T11:00:00.000000Z"
     *   }
     * }
     *
     * @response 422 {
     *   "first_name": ["The first name field is required."],
     *   "postcode": ["The postcode field is required."]
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\UserAddress]."
     * }
     */
    public function update(Request $request, $id)
    {
        $address = UserAddress::where('user_id', auth('api')->id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            // 'city_id' => 'required|integer|exists:cities,id',
            'city' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'postcode' => 'required|string|max:20',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'is_default' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->is_default) {
            UserAddress::where('user_id', auth('api')->id())->update(['is_default' => false]);
        }

        // $address->city_id = $request->city_id;
        $address->city = $request->city;
        $address->area = $request->area;
        $address->address = $request->address;
        $address->postcode = $request->postcode;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->company_name = $request->company_name;
        $address->address_line_2 = $request->address_line_2;
        $address->is_default = $request->is_default;
        $address->save();

        return response()->json([
            'status' => true,
            'message' => 'Address updated successfully',
            'data' => $address
        ]);
    }

    /**
     * Delete a user address
     *
     * Delete an address belonging to the authenticated user.
     *
     * @group User Addresses
     * @authenticated
     *
     * @urlParam id int required The address ID to delete. Example: 1
     *
     * @header Accept application/json
     * @header X-Api-Token fgnQQIOVNRcpSRy
     * @header Authorization Bearer {sanctum_token}
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Address deleted successfully"
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\UserAddress]."
     * }
     */
    public function destroy($id)
    {
        // $address = UserAddress::where('user_id', auth('api')->id())->findOrFail($id);
        // $address->delete();

        return response()->json([
            'status' => false,
            'message' => 'Address deletion is blocked from Customer Portal'
        ]);
    }
}
