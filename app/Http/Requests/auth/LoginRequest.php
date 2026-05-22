<?php

namespace App\Http\Requests\auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|regex:/^.+@Tenebris\.ix$/',
            'password' => 'required|alpha_num:ascii|min:8'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'email wajib di isi',
            'password.required' => 'password wajib di isi',

            'email.email' => 'format email salah',
            'email.regex' => 'domain email salah harus pake @Tenebris.ix',
            'password.alpha_num' => 'password hanya biaa berisi karakter huruf dan angka',

            'password.min' => 'password harus lebih dari 8 karakter'
        ];
    }
}
