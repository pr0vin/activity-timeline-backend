<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFiscalYearRequest extends FormRequest
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
            'year' => 'required',
            // 'startDate' => 'required',
            // 'endDate' => 'required',
            'status' => 'required',
            // 'ad_endDate' => 'required',
            // 'ad_startDate' => 'required',
        ];
    }
}
