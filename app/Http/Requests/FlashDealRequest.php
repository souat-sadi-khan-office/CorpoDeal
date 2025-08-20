<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlashDealRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'short_content' => 'nullable|string',
            'slug' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'starting_time' => 'required',
            'deadline_type' => 'required',
            'deadline_time' => 'required|string|max:255',
            'description' => 'required|string',
            'site_title' => 'required|string|max:255',
            'meta_title' => 'required|string|max:255',
            'meta_keyword' => 'required|string',
            'meta_description' => 'required|string',
            'meta_article_tag' => 'nullable|string',
            'meta_script_tag' => 'nullable|string'
        ];
    }
}
