<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ComputerBuild;
use App\Models\ComputerBuildFan;
use App\Models\ComputerBuildRam;
use App\Models\ComputerBuildStorage;
use App\Models\User;
use App\Models\UserBroughtCoupon;
use App\Models\UserPoint;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\Interface\OrderRepositoryInterface;
use App\Repositories\Interface\ProductRepositoryInterface;
use App\Repositories\Interface\CurrencyRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    private $user;
    private $currency;
    private $orderRepository;
    protected $productRepository;

    public function __construct(
        UserRepositoryInterface $user,
        CurrencyRepositoryInterface $currency,
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,

    ) {
        $this->user = $user;
        $this->currency = $currency;
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;

    }

    public function trackOrder()
    {
        return view('frontend.track_order');
    }

    public function savePc()
    {
        $model['user_id'] = Auth::guard('customer')->user()->id;
        if(Session::has('pc_builder_item_cpu')) {
            $model['processor_id'] = Session::get('pc_builder_item_cpu')['id'];
        }

        if(Session::has('pc_builder_item_cooler')) {
            $model['cpu_cooler_id'] = Session::get('pc_builder_item_cooler')['id'];
        }

        if(Session::has('pc_builder_item_motherboard')) {
            $model['mother_board_id'] = Session::get('pc_builder_item_motherboard')['id'];
        }

        $ramArray = [];
        if(Session::has('pc_builder_item_ram')) {
            $ramArray = Session::get('pc_builder_item_ram');
        }

        $ssdArray = [];
        if(Session::has('pc_builder_item_ssd')) {
            $ssdArray = Session::get('pc_builder_item_ssd');
        }

        if(Session::has('pc_builder_item_gc')) {
            $model['graphics_card_id'] = Session::get('pc_builder_item_gc')['id'];
        }

        if(Session::has('pc_builder_item_psu')) {
            $model['psu_id'] = Session::get('pc_builder_item_psu')['id'];
        }

        if(Session::has('pc_builder_item_casing')) {
            $model['casing_id'] = Session::get('pc_builder_item_casing')['id'];
        }

        if(Session::has('pc_builder_item_monitor')) {
            $model['monitor_id'] = Session::get('pc_builder_item_monitor')['id'];
        }

        $fanArray = [];
        if(Session::has('pc_builder_item_casing_fan')) {
            $fanArray = Session::get('pc_builder_item_ssd');
        }

        if(Session::has('pc_builder_item_keyboard')) {
            $model['keyboard_id'] = Session::get('pc_builder_item_keyboard')['id'];
        }

        if(Session::has('pc_builder_item_mouse')) {
            $model['mouse_id'] = Session::get('pc_builder_item_mouse')['id'];
        }

        if(Session::has('pc_builder_item_anti_virus')) {
            $model['anti_virus_id'] = Session::get('pc_builder_item_anti_virus')['id'];
        }

        if(Session::has('pc_builder_item_headphone')) {
            $model['head_phone_id'] = Session::get('pc_builder_item_headphone')['id'];
        }

        if(Session::has('pc_builder_item_ups')) {
            $model['ups_id'] = Session::get('pc_builder_item_ups')['id'];
        }

        $build = ComputerBuild::create($model);
        if($build) {
            if(count($fanArray) > 0) {
                foreach($fanArray as $fan) {
                    ComputerBuildFan::create([
                        'builder_id' => $build->id,
                        'fan_id' => $fan['id']
                    ]);
                }
            }

            if(count($ramArray) > 0) {
                foreach($ramArray as $ram) {
                    ComputerBuildRam::create([
                        'builder_id' => $build->id,
                        'ram_id' => $ram['id']
                    ]);
                }
            }

            if(count($ssdArray) > 0) {
                foreach($ssdArray as $ssd) {
                    ComputerBuildStorage::create([
                        'builder_id' => $build->id,
                        'storage_id' => $ssd['id']
                    ]);
                }
            }
        }

        return redirect()->route('pc-builder')->with('success', 'Added to Saved Pc');
    }

    public function index(Request $request)
    {
        $data=$this->orderRepository->userData();
        $models = $this->user->getUserWishList();
        return view('frontend.customer.dashboard',compact('data','models'));
    }

    public function myOrders()
    {
        $models=$this->orderRepository->userOrders();
        return view('frontend.customer.my-orders',compact('models'));
    }
    public function orderDetails($id)
    {

        $order = $this->orderRepository->details(decode($id));
        return view('frontend.customer.my-order_details', compact('order'));
    }
    public function orderInvoice($id, Request $request)
    {
        $order = $this->orderRepository->details(decode($id));
        return view('backend.order.invoice', compact('order','request'));

    }

    public function quotes()
    {
        $quotes=$this->productRepository->userQuotes();
        return view('frontend.customer.quotes',compact('quotes'));
    }

    public function profile()
    {
        $model = $this->user->getCustomerDetails();
        $currencies = $this->currency->getAllActiveCurrencies();

        return view('frontend.customer.profile', compact('model', 'currencies'));
    }

    public function updateProfile(Request $request)
    {
        return $this->user->updateProfile($request);
    }

    public function password()
    {
        return view('frontend.customer.password');
    }

    public function updatePassword(Request $request)
    {
        return $this->user->updatePassword($request);
    }

    public function addressBook()
    {
        return view('frontend.customer.address');
    }

    public function wishlist()
    {
        $models = $this->user->getUserWishList();
        return view('frontend.customer.wishlist', compact('models'));
    }

    public function destroyWishlist($id)
    {
        return $this->user->removeWishList($id);
    }

    public function star_points()
    {
        $models = UserPoint::where('user_id', Auth::guard('customer')->user()->id)->orderBy('id', 'DESC')->paginate(25);
        return view('frontend.customer.star_points', compact('models'));
    }

    public function myPoints()
    {
        $models = UserBroughtCoupon::where('user_id', Auth::guard('customer')->user()->id)->orderBy('id', 'DESC')->paginate(25);

        return view('frontend.customer.my_coupons', compact('models'));
    }

    public function saved_pc()
    {
        $models = ComputerBuild::where('user_id', Auth::guard('customer')->user()->id)->orderBy('id', 'DESC')->get();
        return view('frontend.customer.saved_pc', compact('models'));
    }

    public function viewPc($slug)
    {
        $id = decode($slug);

        $model = ComputerBuild::findOrFail($id);
        return view('frontend.customer_saved_pc', compact('model'));
    }

    public function removePc($slug)
    {
        $id = decode($slug);

        $model = ComputerBuild::findOrFail($id);
        if($model) {
            $model->delete();
        }

        return redirect()->back()->with('success', 'Record deleted successfully');
    }

    public function transactions()
    {
        return view('frontend.customer.transactions');
    }

    public function removeAccount()
    {
        return view('frontend.customer.remove-account');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        // Check if the password is correct
        if (!Hash::check($request->password, Auth::guard('customer')->user()->password)) {
            return response()->json([
                'status' => false,
                'message' => 'The password you entered is incorrect.'
            ]);
        }

        // Update the is_deleted column for the authenticated user
        $user = User::findOrFail(Auth::guard('customer')->user()->id);
        $user->is_deleted = 1;
        $user->save();

        Auth::guard('customer')->logout();

        // Redirect to a specified route (e.g., the homepage)
        return response()->json([
            'status' => true,
            'goto' => route('home'),
            'message' => 'Your account has been successfully removed.'
        ]);
    }
}
