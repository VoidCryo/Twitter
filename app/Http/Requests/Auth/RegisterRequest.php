<?php

namespace App\Http\Requests\Auth;

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
            'name'     => 'required|alpha_num:ascii|min:3|max:20|unique:users,name',
            'email'    => 'required|email|unique:users,email|regex:/^.+@Tenebris\.ix$/',
            'password' => 'required|alpha_num:ascii|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'username wajib di isi',
            'email.required'    => 'email wajib di isi',
            'password.required' => 'password wajib di isi',

            'name.alpha_num'    => 'username hanya bisa berisikan huruf dan angka',
            'password.alpha_num'=> 'password hanya bisa berisikan huruf dan angka',
            'email.email'       => 'format email salah',
            'email.regex'       => 'domain email salah harus pake @Tenebris.ix',

            'name.min'          => 'username minimal 3 karakter',
            'name.max'          => 'username maksimal 20 karakter',
            'password.min'      => 'password harus lebih dari 8 karakter',

            'email.unique'      => 'email sudah dipakai',
            'name.unique'       => 'username sudah dipakai',
            'password.confirmed'=> 'anda salah memasukan ulang password',
        ];
    }
}
