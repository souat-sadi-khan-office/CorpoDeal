<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Models\User;
use App\Models\UserPoint;
use App\Repositories\Interface\CustomerRepositoryInterface;
use App\Repositories\Interface\CurrencyRepositoryInterface;
use App\Repositories\Interface\ZoneRepositoryInterface;
use App\Repositories\Interface\CountryRepositoryInterface;
use App\Repositories\Interface\CityRepositoryInterface;
use App\Repositories\Interface\CartRepositoryInterface;

class CustomerController extends Controller
{
    private $customerRepository;
    private $currencyRepository;
    private $zoneRepository;
    private $countryRepository;
    private $cityRepository;
    private $cartRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CurrencyRepositoryInterface $currencyRepository,
        ZoneRepositoryInterface $zoneRepository,
        CountryRepositoryInterface $countryRepository,
        CityRepositoryInterface $cityRepository,
        CartRepositoryInterface $cartRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->currencyRepository = $currencyRepository;
        $this->zoneRepository = $zoneRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->cartRepository = $cartRepository;
    }

    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {

            return $this->customerRepository->dataTable();
        }

        return view('backend.customers.index');
    }

    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer.create') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $currencies = $this->currencyRepository->getAllActiveCurrencies();
        return view('backend.customers.create', compact('currencies'));
    }

    public function store(CustomerRequest $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer.create') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->customerRepository->createCustomer($request);
    }

    public function show($id)
    {
        
        if (auth()->guard('admin')->user()->hasPermissionTo('customer.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->customerRepository->findCustomerById($id);
        return view('backend.customers.show', compact('model'));
    }

    public function view($id = null, $action = null)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer.view') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = null;
        if(isset($id)) {
            $model = $this->customerRepository->findCustomerById($id);
        }

        return view('backend.customers.view', compact('model', 'action'));
    }

    public function updatePoints(Request $request, $id) 
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer.send-gift-points-to-customer') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $points = $request->points;
        $method = $request->method;
        $note = $request->note;

        $customer = User::find($id);
        if(!$customer) {
            return response()->json([
                'status' => false, 
                'message' => "Customer not found"
            ]);
        }

        $point = UserPoint::create([
            'user_id' => $id,
            'quantity' => 1,
            'points' => $points,
            'method' => $method,
            'notes' => $note
        ]);

        if($point) {
            if($method == 'plus') {
                $customer->points += $points;
            } else {
                $customer->points -= $points;
            }
            $customer->save();
        }

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Point successfully given."
        ]);
    }

    public function edit($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer.update') === false) {
            return redirect()->route('admin.dashboard')->with('error', 'You don\'t have permission.');
        }

        $currencies = $this->currencyRepository->getAllActiveCurrencies();
        $model = $this->customerRepository->findCustomerById($id);
        return view('backend.customers.edit', compact('model', 'currencies'));
    }

    public function update(CustomerRequest $request, $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer.update') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        return $this->customerRepository->updateCustomer($id, $request);
    }

    public function destroy($id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('customer.delete') === false) {
            return response()->json([
                'status' => false, 
                'goto' => route('admin.dashboard'),
                'message' => "You don\'t have permission"
            ]);
        }

        $this->customerRepository->deleteCustomer($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Customer deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        return $this->customerRepository->updateStatus($request, $id);
    }
    
    public function updateQuestionStatus(Request $request, $id)
    {
        return $this->customerRepository->updateQuestionStatus($request, $id);
    }

    public function createPhone($id)
    {
        $model = $this->customerRepository->findCustomerById($id);

        return view('backend.customers.phone.create', compact('model'));
    }

    public function storePhone(Request $request)
    {
        return $this->customerRepository->storePhone($request);
    }

    public function editPhone($id)
    {
        $model = $this->customerRepository->findCustomerPhoneById($id);

        return view('backend.customers.phone.edit', compact('model'));
    }

    public function updatePhone(Request $request, $id)
    {
        return $this->customerRepository->updatePhone($request, $id);
    }

    public function destroyPhone($id)
    {
        $this->customerRepository->destroyPhone($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Address deleted successfully"
        ]);
    }

    public function createAddress($id)
    {
        $model = $this->customerRepository->findCustomerById($id);
        $currencies = $this->currencyRepository->getAllActiveCurrencies();
        $zones = $this->zoneRepository->getAllActiveZones();

        return view('backend.customers.address.create', compact('currencies', 'model', 'zones'));
    }

    public function storeAddress(Request $request)
    {
        return $this->customerRepository->storeAddress($request);
    }

    public function editAddress($id)
    {
        $model = $this->customerRepository->findCustomerAddressById($id);
        $currencies = $this->currencyRepository->getAllActiveCurrencies();
        $zones = $this->zoneRepository->getAllActiveZones();
        $countries = $this->countryRepository->findCountriesByZoneId($model->zone_id);
        $cities = $this->cityRepository->findCitiesByCountryId($model->country_id);

        return view('backend.customers.address.edit', compact('currencies', 'model', 'zones', 'countries', 'cities'));
    }

    public function updateAddress(Request $request, $id)
    {
        return $this->customerRepository->updateAddress($request, $id);
    }

    public function destroyAddress($id)
    {
        $this->customerRepository->destroyAddress($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Address deleted successfully"
        ]);
    }

    public function showCart($id)
    {
        $models = $this->cartRepository->getCartItemsByCartId($id);

        return view('backend.customers.cart.show', compact('models'));
    }

    public function destroyWishList($id)
    {
        $this->customerRepository->destroyCustomerWishList($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Wish List Item deleted successfully"
        ]);
    }

    public function destroyCart($id)
    {
        $this->cartRepository->destroyCart($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Cart Item deleted successfully"
        ]);
    }

    public function destroyRating($id)
    {
        $this->customerRepository->destroyRating($id);

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Cart Item deleted successfully"
        ]);
    }
}