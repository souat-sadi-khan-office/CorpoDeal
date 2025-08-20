<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\Interface\ZoneRepositoryInterface;
use App\Repositories\Interface\CountryRepositoryInterface;
use App\Repositories\Interface\CityRepositoryInterface;
use App\Repositories\Interface\AddressControllerInterface;

class AddressController extends Controller
{

    private $user;
    private $zone;
    private $country;
    private $city;
    private $address;

    public function __construct(
        UserRepositoryInterface $user,
        ZoneRepositoryInterface $zone,
        CountryRepositoryInterface $country,
        CityRepositoryInterface $city,
        AddressControllerInterface $address 
    ) {
        $this->user = $user;
        $this->address = $address;
        $this->zone = $zone;
        $this->country = $country;
        $this->city = $city;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = $this->address->getAllByUser();
        return view('frontend.customer.address.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $zones = $this->zone->getAllActiveZones();
        $cities = $this->city->findCitiesByCountryId(6);
        return view('frontend.customer.address.create', compact('cities'));
    }

    public function getCountriesByZone(Request $request)
    {
        $zoneId = $request->zone_id;
        $countries = $this->country->findCountriesByZoneId($zoneId);
        
        return response()->json($countries);
    }
    
    public function getCitiesByCountry(Request $request)
    {
        $countryId = $request->country_id;
        $cities = $this->city->findCitiesByCountryId($countryId);
        
        return response()->json($cities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'zone_id'        => 'required',
            'country_id'     => 'required',
            'city_id'        => 'required',
            'area'           => 'required',
            'address'        => 'required',
            'postcode'       => 'required',
            'first_name'     => 'required|string|max:55',
            'last_name'      => 'required|string|max:55',
            'company'        => 'string|max:155',
            'address_line_2' => 'string',
            'is_default'     => 'required'
        ]);

        $this->address->createModel($data);

        return response()->json([
            'status' => true, 
            'goto' => route('account.address-book.index'),
            'message' => "New address is added successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = $this->address->findModelById($id);

        $zones = $this->zone->getAllActiveZones();
        $countries = $this->country->findCountriesByZoneId($model->zone_id);
        $cities = $this->city->findCitiesByCountryId($model->country_id);

        return view('frontend.customer.address.edit', compact('model', 'zones', 'countries', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'zone_id'        => 'required',
            'country_id'     => 'required',
            'city_id'        => 'required',
            'area'           => 'required',
            'address'        => 'required',
            'postcode'       => 'required',
            'first_name'     => 'required|string|max:55',
            'last_name'      => 'required|string|max:55',
            'company_name'   => 'string|max:155',
            'address_line_2' => '',
            'is_default'     => 'required'
        ]);

        $this->address->updateModel($id, $data);

        return response()->json([
            'status' => true, 
            'goto' => route('account.address-book.index'),
            'message' => "Address information updated successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->address->deleteModel($id);

        return response()->json([
            'status' => true,
            'load' => true,
            'message' => "Address is deleted successfully"
        ]);
    }
}