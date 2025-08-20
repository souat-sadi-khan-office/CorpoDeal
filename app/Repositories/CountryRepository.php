<?php

namespace App\Repositories;

use App\CPU\Images;
use App\Models\Country;
use App\Repositories\Interface\CountryRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;

class CountryRepository implements CountryRepositoryInterface
{
    public function getAllCountries()
    {
        return Country::all();
    }
    
    public function getAllActiveCountry()
    {
        return Country::where('status', 1)->get();
    }

    public function dataTable()
    {
        $models = $this->getAllCountries();
            return DataTables::of($models)
                ->addIndexColumn()
                ->editColumn('zone', function ($model) {
                    return $model->zone->name;
                })
                ->editColumn('cost', function($model) {
                    return '$'. number_format(covert_to_defalut_currency($model->cost), 2);
                })
                ->editColumn('image', function ($model) {
                    return Images::show($model->image);
                })
                ->editColumn('status', function ($model) {
                    $checked = $model->status == 1 ? 'checked' : '';

                    if (auth()->guard('admin')->user()->hasPermissionTo('shipping-configuration.country') === false) {
                        return '<div class="form-check form-switch"><input data-url="' . route('admin.country.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                    } else {
                        $bg = $model->status == 1 ? 'success' : 'warning';
                        $active = $model->status == 1 ? 'Active' : 'Inactive';
                        return '<span class="badge bg-'. $bg .'">'. $active .'</span>';
                    }

                })
                ->addColumn('action', function ($model) {
                    return view('backend.countries.action', compact('model'));
                })
                ->rawColumns(['action', 'cost', 'image', 'status', 'zone'])
                ->make(true);
    }

    public function findCountriesByZoneId($zoneId)
    {
        return Country::where('zone_id', $zoneId)->where('status', 1)->orderBy('name', 'ASC')->get();
    }

    public function findCountryById($id)
    {
        return Country::findOrFail($id);
    }

    public function createCountry(array $data)
    {
        $country = Country::create($data);

        return $country;
    }

    public function updateCountry($id, array $data)
    {
        $country = Country::findOrFail($id);
        $country->update($data);

        return $country;
    }

    public function deleteCountry($id)
    {
        $country = Country::findOrFail($id);
        return $country->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $country = Country::find($id);

        if (!$country) {
            return response()->json(['success' => false, 'message' => 'Country not found.'], 404);
        }

        $country->status = $request->input('status');
        $country->save();

        return response()->json(['success' => true, 'message' => 'Country status updated successfully.']);
    }
}