<?php

namespace App\Repositories;

use App\Models\City;
use App\Repositories\Interface\CityRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class CityRepository implements CityRepositoryInterface
{
    public function getAllCities()
    {
        return City::all();
    }
    
    public function getAllActiveCity()
    {
        return City::where('status', 1)->get();
    }

    public function findCitiesByCountryId($countryId)
    {
        return City::where('country_id', $countryId)->where('status', 1)->orderBy('name', 'ASC')->get();
    }

    public function dataTable()
    {
        $models = $this->getAllCities();
            return DataTables::of($models)
                ->addIndexColumn()
                ->editColumn('country', function ($model) {
                    return $model->country->name;
                })
                ->editColumn('cost', function($model) {
                    return format_price(covert_to_defalut_currency($model->cost), 2);
                })
                ->editColumn('status', function ($model) {
                    $checked = $model->status == 1 ? 'checked' : '';
                    return '<div class="form-check form-switch"><input data-url="' . route('admin.city.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                })
                ->addColumn('action', function ($model) {
                    return view('backend.cities.action', compact('model'));
                })
                ->rawColumns(['action', 'cost', 'status', 'zone'])
                ->make(true);
    }

    public function findCityById($id)
    {
        return City::findOrFail($id);
    }

    public function createCity(array $data)
    {
        $city = City::create($data);

        return $city;
    }

    public function updateCity($id, array $data)
    {
        $city = City::findOrFail($id);
        $city->update($data);

        return $city;
    }

    public function deleteCity($id)
    {
        $city = City::findOrFail($id);
        return $city->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $city = City::find($id);

        if (!$city) {
            return response()->json(['success' => false, 'message' => 'City not found.'], 404);
        }

        $city->status = $request->input('status');
        $city->save();

        return response()->json(['success' => true, 'message' => 'City status updated successfully.']);
    }
}