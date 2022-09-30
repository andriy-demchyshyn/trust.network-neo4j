<?php

namespace App\Http\Requests\People;

use Illuminate\Foundation\Http\FormRequest;

class DestroyPersonRequest extends FormRequest
{
    /**
     * Check if current user is authorized to make this request
     * 
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Validation rules
     * 
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
