<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoyageRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            
            'vessel_id' => 'required',
            'code' => '',
            'start' => 'required|date',
            'end' => 'date|after:start',
            'status' => '',
            'revenues' => '',
            'expenses' => '',
            'profit' => ''

        ];
    }
}
