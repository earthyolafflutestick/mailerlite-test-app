<?php

namespace App\Http\Requests;

use App\MailerLite\Error;
use App\Services\MailerLiteService;
use Illuminate\Foundation\Http\FormRequest;

class SubscribersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->mergeIfMissing([
            'per_page' => 10,
            'cursor' => ''
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'per_page' => 'required|in:10,25,50',
            'cursor' => 'present',
        ];
    }
}
