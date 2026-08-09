<?php

namespace App\Http\Requests\Activation;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DefineAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mobile_number' => ['required', 'string', 'max:20'],
            // Checked against `users` here (step 1) rather than left to fail as a
            // raw DB unique-constraint error when the User row is actually
            // created in step 3 — this must be a clean validation message.
            'official_email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'terms_accepted' => ['accepted'],
        ];
    }
}
