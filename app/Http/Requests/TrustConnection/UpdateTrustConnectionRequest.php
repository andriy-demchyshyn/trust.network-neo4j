<?php

namespace App\Http\Requests\TrustConnection;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrustConnectionRequest extends FormRequest
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
