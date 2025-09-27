<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ComputerBuild;
use App\Models\Country;
use App\Models\Currency;
use App\Models\NegativeBalanceRequest;
use App\Models\ProductQuestion;
use App\Models\User;
use App\Models\UserBroughtCoupon;
use App\Models\UserPoint;
use App\Models\WishList;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\Interface\OrderRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\CurrencyRepositoryInterface;
use App\Repositories\Interface\InstallmentPlanInterface;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    private $user;
    private $currency;
    private $orderRepository;
    protected $productRepository;
    private $installment;

    public function __construct(
        UserRepositoryInterface $user,
        CurrencyRepositoryInterface $currency,
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        InstallmentPlanInterface $installment,
    ) {
        $this->user = $user;
        $this->currency = $currency;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->installment = $installment;
    }

    public function getCountries()
    {
        return Country::where('status', 1)->orderBy('name')->get();
    }

    public function getCurrency($countryId)
    {
        return Currency::where('country_id', $countryId)->where('status', 1)->get();
    }

    private function userId() 
    {
        return auth('api')->user()->id ?? null;
    }

    /**
     * Get user dashboard data
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @response 200 {
     *   "wishlists": [...]
     * }
     */
    public function dashboard()
    {
        $data = $this->orderRepository->userData($this->userId());
        $models = $this->user->getUserWishList($this->userId());

        $data['total_points'] = auth('api')->user()->points;
        $data['wishlists'] = $models;

        return response()->json($data);
    }

    public function myProfile()
    {
        $user = auth('api')->user();
        return response()->json($user);
    }

    /**
     * Get user wishlist data
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @response 200 {
     *   "id": 7,
     *   "user_id": 18,
     *   "product_id": 75,
     *   "created_at": "2025-07-29T05:49:55.000000Z",
     *   "updated_at": "2025-07-29T05:49:55.000000Z",
     *   "user": {
     *       ...
     *   },
     *   "product": {
     *       ...
     *   }
     * }
     */
    public function wishlist()
    {
        $models = $this->user->getUserWishList($this->userId());
        return response()->json($models);
    }

    public function storeWishlist(Request $request)
    {
        $productId = $request->id;

        if (!auth('api')->user()->id) {
            return response()->json(['status' => false, 'message' => 'You must login or create an account to save products on your wishlist.']);
        }

        $userId = auth('api')->user()->id;

        if (WishList::where('user_id', $userId)->where('product_id', $productId)->first()) {
            return response()->json(['status' => false, 'message' => 'This product is already added to your wishlist.']);
        }

        WishList::create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);

        return response()->json(['status' => true, 'message' => 'Successfully added to your Wishlist.']);
    }

    /**
     * Get user coupon data
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @response 200 {
     *   "id": 7,
     *   "user_id": 18,
     *   "coupon_id": 75,
     *   "coupon_id": 1,
     *   "status": 0,
     *   "created_at": "2025-07-29T05:49:55.000000Z",
     *   "updated_at": "2025-07-29T05:49:55.000000Z",
     *   "coupon": {
     *       ...
     *   }
     * }
     */
    public function myPoints()
    {
        $models = UserBroughtCoupon::with('coupon')->where('user_id', auth('api')->user()->id)->orderBy('id', 'DESC')->get();
        return response()->json($models);
    }

    /**
     * Get user star points data
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @response 200 {
     *   "id": 1,
     *   "user_id": 18,
     *   "product_id": null,
     *   "quantity": 1,
     *   "points": 10000,
     *   "method": "plus",
     *   "notes": "Gift Enjoy!",
     *   "created_at": "2025-05-14T13:09:14.000000Z",
     *   "updated_at": "2025-05-14T13:09:14.000000Z"
     * }
     */
    public function star_points()
    {
        $models = UserPoint::where('user_id', auth('api')->user()->id)->orderBy('id', 'DESC')->paginate(25);
        // $models = UserPoint::where('user_id', auth('api')->user()->id)->orderBy('id', 'DESC')->get();
        return response()->json($models);
    }

    /**
     * Delete wishlist item
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
     *   "message": "Item is removed from your wishlist"
     * }
     *
     * @response 404 {
     *   "message": "Product not found."
     * }
     */
    public function destroyWishlist($id)
    {
        return $this->user->removeWishList($id);
    }

    /**
     * List all orders of the user
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @response 200 [
     *   {
     *     "id": 18,
     *     "unique_id": "#6885b9ad038a0",
     *     "currency": "BDT",
     *     "currency_symbol": "৳",
     *     "gateway_name": "Cash on Delivery",
     *     "payment_status": "Unpaid",
     *     "is_manual_pay": 0,
     *     "status": "pending",
     *     "amount": 575.51,
     *     "created_at": "27 Jul 2025, 11:31:25 AM"
     *   }
     * ]
     */
    public function myOrders()
    {
        $models = $this->orderRepository->userOrders($this->userId());
        
        return response()->json($models);
    }

    /**
     * Show order details
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @urlParam id int required The ID of the order.
     * 
     * @response 200 {
     *   "id": 1,
     *   "products": [...],
     *   "total": "500.00"
     * }
     */
    public function orderDetails($id)
    {
        $order = $this->orderRepository->details($id);
        return response()->json($order);
    }

    /**
     * List user's product quotes
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @response 200 [
     *   {
     *     "id": 1,
     *     "product": {...},
     *     "answer": {...}
     *   }
     * ]
     */
    public function quotes()
    {
        $quotes = ProductQuestion::where('user_id', $this->userId())->with('product', 'answer')->orderBy('id', 'DESC')->get();
        return response()->json($quotes);
    }

    /**
     * Update user profile
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @bodyParam name string required The name of the user.
     * @bodyParam email string required The email of the user.
     * 
     * @response 200 {
     *   "message": "Profile updated successfully"
     * }
     */
    public function updateProfile(Request $request)
    {
        return $this->user->updateProfile($request, $this->userId());
    }

    /**
     * Update user password
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @bodyParam current_password string required The current password.
     * @bodyParam new_password string required The new password.
     * @bodyParam new_password_confirmation string required Confirm the new password.
     * 
     * @response 200 {
     *   "message": "Password updated successfully"
     * }
     */
    public function updatePassword(Request $request)
    {
        return $this->user->updatePassword($request, $this->userId());
    }

    /**
     * List user's negative balance
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @response 200 [
     *   {
     *     "id": 1,
     *      ...
     *   }
     * ]
     */
    public function myNegativeBalance()
    {
        $history = NegativeBalanceRequest::where('user_id', auth('api')->user()->id)
            ->with('currency:id,code,symbol', 'installmentPlan')
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json($history);
    }

    /**
     * Submit Installment Request
     *
     * Handles the submission of a new installment request by the user.
     *
     * @group Installments
     * @authenticated
     *
     * @bodyParam amount string required The requested installment amount. Example: 5000.00
     * @bodyParam installment_plan_id integer required The ID of the selected installment plan. Must exist in the `installment_plans` table. Example: 3
     * @bodyParam document file required The primary document. Allowed types: jpg, jpeg, png, pdf, doc, docx. Max size: 512KB.
     * @bodyParam document_2 file Optional secondary document. Allowed types: jpg, jpeg, png, pdf, doc, docx. Max size: 512KB.
     * @bodyParam document_3.* file Optional array of additional documents. Allowed types: jpg, jpeg, png, pdf, doc, docx. Max size per file: 512KB.
     * @bodyParam description string required A description or note for the installment request. Max 1000 characters.
     * @bodyParam currency_id integer required The ID of the selected currency. Must exist in the `currencies` table. Example: 1
     *
     * @response 200 {
     *   "status": true,
     *   "message": "Installment request submitted successfully."
     * }
     *
     * @response 422 {
     *   "errors": {
     *     "amount": ["The amount field is required."],
     *     ...
     *   }
     * }
     */
    public function negativeBalanceStore(Request $request)
    {
        return $this->installment->negativeBalanceStore($request, auth('api')->user()->id);
    }

    /**
     * List user's saved pc
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @response 200 [
     *   {
     *     "id": 1,
     *      ...
     *   }
     * ]
     */
    public function saved_pc()
    {
        $models = ComputerBuild::where('user_id', auth('api')->user()->id)->orderBy('id', 'DESC')->get();
        return response()->json($models);
    }

    /**
     * List user's saved pc
     * 
     * @group Account
     * @authenticated
     * 
     * @header Accept application/json
     * @header X-Api-Token EXAMPLEAPItoken
     * @header Authorization Bearer USERTOKEN
     * 
     * @response 200 [
     *   {
     *     "status": true,
     *      "html": "..."
     *   }
     * ]
     */
    public function deletionNote()
    {
        $html = <<<HTML
            <div class="card-header">
                <h1 class="h5 text-danger">For Removing your account, you will loosse</h1>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Account Benefits Reminder:</strong>
                        <ul class="list-unstyled ms-3">
                            <li>Exclusive discounts or rewards</li>
                            <li>Loyalty points and account credits</li>
                            <li>Saved wishlists and order history for easy reordering</li>
                            <li>Personalized recommendations and offers</li>
                        </ul>
                    </li>
                    <li class="list-group-item">
                        <strong>Pending Orders and Returns:</strong>
                        <ul class="list-unstyled ms-3">
                            <li>Details about active orders</li>
                            <li>Return status information</li>
                        </ul>
                    </li>
                    <li class="list-group-item">
                        <strong>Data to be Deleted:</strong>
                        <ul class="list-unstyled ms-3">
                            <li>Personal information (e.g., name, email, phone)</li>
                            <li>Order history</li>
                            <li>Saved addresses and payment methods</li>
                            <li>Account preferences and settings</li>
                        </ul>
                    </li>
                    <li class="list-group-item">
                        <strong>Data to be Retained:</strong>
                        <ul class="list-unstyled ms-3">
                            <li>Order invoices for accounting and tax purposes</li>
                            <li>Data necessary to comply with regulations</li>
                        </ul>
                    </li>
                    <li class="list-group-item">
                        <strong>Reactivation Policy:</strong>
                        <p class="ms-3 mb-0">Explanation of how long the data will be retained before complete deletion (if applicable).</p>
                    </li>
                    <li class="list-group-item">
                        <strong>Feedback Request:</strong>
                        <p class="ms-3 mb-0">Optional survey asking for feedback on why they are leaving.</p>
                    </li>
                </ul>
            </div>
        HTML;

        return response()->json([
            'status' => true,
            'html' => $html
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        // Check if the password is correct
        if (!Hash::check($request->password, auth('api')->user()->password)) {
            return response()->json([
                'status' => false,
                'message' => 'The password you entered is incorrect.'
            ]);
        }

        // Update the is_deleted column for the authenticated user
        $user = User::findOrFail(auth('api')->user()->id);
        $user->is_deleted = 1;
        $user->save();

        $request->user()->currentAccessToken()->delete();

        // Redirect to a specified route (e.g., the homepage)
        return response()->json([
            'status' => true,
            'message' => 'Your account has been successfully removed.'
        ]);
    }
}