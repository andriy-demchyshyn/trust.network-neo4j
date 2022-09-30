<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class DestroyMessageRequest extends FormRequest
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
