<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'sku' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'low_stock_quantity' => 'required|numeric|min:0',
            'purchase_unit_price' => 'nullable|numeric|min:0',
            'purchase_total_price' => 'nullable|numeric|min:0',
            'currency_id' => 'required|integer|exists:currencies,id',
            'is_sellable' => 'required|boolean',
            'stock_types' => 'required|string|in:globally,zone_wise,country_wise,city_wise',
            'globally_stock_amount' => 'nullable|integer|min:0',
            
            // Country-wise stock fields
            'country_wise_id' => 'nullable|array',
            'country_wise_id.*' => 'nullable|integer|exists:countries,id',
            'country_id' => 'nullable|array',
            'country_id.*' => 'nullable|integer|exists:countries,id',
            'country_wise_quantity' => 'nullable|array',
            'country_wise_quantity.*' => 'nullable|integer|min:0',
            
            // City-wise stock fields
            'city_wise_id' => 'nullable|array',
            'city_wise_id.*' => 'nullable|integer|exists:cities,id',
        ];
    }
}
