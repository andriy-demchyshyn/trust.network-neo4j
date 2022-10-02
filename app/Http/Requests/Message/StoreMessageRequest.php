<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
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
        return [
            'text' => 'required|string',
            'topics' => 'required|array',
            'from_person_id' => 'required|string',
            'min_trust_level' => 'required|integer|min:1|max:10',
        ];
    }
}
