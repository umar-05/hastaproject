<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
    'name' => ['string', 'max:255'],
    'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
    'phoneNum' => ['nullable', 'string', 'max:20'],
    'icNum' => ['nullable', 'string', 'max:20'],
    'address' => ['nullable', 'string'],
    'city' => ['nullable', 'string', 'max:100'],
    'postcode' => ['nullable', 'string', 'max:10'],
    'state' => ['nullable', 'string', 'max:100'],
    'collegeAddress' => ['nullable', 'string'],
    'eme_name' => ['nullable', 'string', 'max:255'],
    'emephoneNum' => ['nullable', 'string', 'max:20'],
    'emerelation' => ['nullable', 'string', 'max:50'],
    'bankName' => ['nullable', 'string', 'max:100'],
    'accountNum' => ['nullable', 'string', 'max:50'],
];
    }
}
