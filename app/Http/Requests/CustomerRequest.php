<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'currency_id' => 'required|numeric',
            'status' => 'required',
            'email' => 'required|email|max:255',
        ];

        if ($this->isMethod('post')) {
            $rules['email'] .= '|unique:users,email';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['email'] .= '|unique:users,email,' . request()->id;
        }

        return $rules;
    }
}
