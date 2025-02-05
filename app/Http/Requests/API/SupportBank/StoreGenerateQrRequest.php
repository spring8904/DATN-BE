<?php

namespace App\Http\Requests\API\SupportBank;

use Illuminate\Foundation\Http\FormRequest;

class StoreGenerateQrRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'accountNo' => 'required|numeric',
            'accountName' => 'required|string',
            'acqId' => 'required|numeric',
            'amount' => 'required|numeric',
            'addInfo' => 'required|string',
            'format' => 'required|string',
            'template' => 'required|string',
        ];
    }
}
