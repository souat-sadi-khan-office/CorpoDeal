<?php

namespace App\Repositories;

use DataTables;
use App\Models\Currency;
use App\Repositories\Interface\CurrencyRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    public function getAllCurrencies()
    {
        return Currency::all();
    }

    public function getAllActiveCurrencies()
    {
        return Currency::where('status', 1)->get();
    }

    public function dataTable()
    {
        $models = $this->getAllCurrencies();
        return Datatables::of($models)
            ->addIndexColumn()
            ->editColumn('country', function ($model) {
                return $model->country->name;
            })
            ->editColumn('status', function ($model) {
                $checked = $model->status == 1 ? 'checked' : '';
                return '<div class="form-check form-switch"><input data-url="' . route('admin.currency.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
            })
            ->addColumn('action', function ($model) {
                return view('backend.currency.action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'country'])
            ->make(true);
    }

    public function findCurrencyById($id)
    {
        return Currency::findOrFail($id);
    }

    public function createCurrency(array $data)
    {
        $zone = Currency::create($data);
        Cache::forget('currency_count_dashboard');
        return $zone;
    }

    public function updateCurrency($id, array $data)
    {
        $zone = Currency::findOrFail($id);
        $zone->update($data);
        Cache::forget('currency_count_dashboard');
        return $zone;
    }

    public function deleteCurrency($id)
    {
        $role = Currency::findOrFail($id);
        Cache::forget('currency_count_dashboard');

        return $role->delete();
    }

    public function updateStatus($request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $currency = Currency::find($id);

        if (!$currency) {
            return response()->json(['success' => false, 'message' => 'Currency not found.'], 404);
        }

        $currency->status = $request->input('status');
        $currency->save();
        Cache::forget('currency_count_dashboard');

        return response()->json(['success' => true, 'message' => 'Currency status updated successfully.']);
    }
}
