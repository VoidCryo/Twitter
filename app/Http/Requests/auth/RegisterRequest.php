<?php

namespace App\Http\Requests\auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|alpha_num:ascii|min:3',
            'email' => 'required|email|unique:users,email|regex:/^.+@Tenebris\.ix$/',
            'password' => 'required|alpha_num:ascii|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'nama wajib di isi',
            'email.required' => 'email wajib di isi',
            'password.required' => 'password wajib di isi',

            'name.alpha_num' => 'nama hanya bisa berisikan karakter huruf dan angka',
            'password.alpha_num' => 'nama hanya bisa berisikan karakter huruf dan angka',
            'email.email' => 'format email salah',
            'email.regex' => 'domain email salah harus pake @Tenebris.ix',

            'name.min' => 'nama harus lebih dari 3 karakter',
            'password.min' => 'password harus lebih dari 8 karakter',

            'email.unique' => 'email sudah di pakai',
            'password.confirmed' => 'anda salah memasukan ulang password'
        ];
    }
}
