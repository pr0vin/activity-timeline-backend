<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            //
            'title' => 'required',
            'content' => 'required',
            'fiscal_year_id' => 'required',
            'ad_date' => 'required',
            // 'status' => 'nullable',
            'date' => 'required',
            'time' => 'nullable',
            'assignTo' => 'nullable',

        ];
    }
}
