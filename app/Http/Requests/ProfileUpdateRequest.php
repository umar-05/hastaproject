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
        $user = $this->user();

        return [
            // Required Fields
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:customer,email,'.$user->matricNum.',matricNum'],
            'phoneNum' => ['required', 'numeric'],
            'faculty' => ['required', 'string', 'max:255'],
            'icNum' => ['required', 'string', 'max:20'],

            // Optional / Nullable Fields (Address)
            'collegeAddress' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'postcode' => ['nullable', 'numeric'],
            'state' => ['nullable', 'string', 'max:100'],

            // Emergency Contact
            'eme_name' => ['nullable', 'string', 'max:255'],
            'emephoneNum' => ['nullable', 'numeric'],
            'emerelation' => ['nullable', 'string', 'max:50'],

            // Banking
            'bankName' => ['nullable', 'string', 'max:100'],
            'accountNum' => ['nullable', 'numeric'],
        ];
    }
}
