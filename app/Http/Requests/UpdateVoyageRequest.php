<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class UpdateVoyageRequest extends FormRequest
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

        if($this->method() == 'PUT') {

            return [
            
                'vessel_id' => 'required',
                'code' => 'required',
                'start' => 'required|date',
                'end' => 'required|date|after:start',
                'status' => 'required',
                'revenues' => 'required',
                'expenses' => 'required',
                'profit' => 'required'
    
            ];

        } else {

            return [
            
                'vessel_id' => 'sometimes|required',
                'code' => 'sometimes|required',
                'start' => 'sometimes|required|date',
                'end' => 'sometimes|required|date|after:start',
                'status' => 'sometimes|required',
                'revenues' => 'sometimes|required',
                'expenses' => 'sometimes|required',
                'profit' => 'sometimes|required'
    
            ];

        }

    }
}
