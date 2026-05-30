<?php

namespace App\Http\Requests\post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReplyRequest extends FormRequest
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
            'content' => 'required|string|max:256',
            'media'   => 'nullable|array|max:4',
            'media.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
                'content.required' => 'post tidak boleh kosong',
                'content.max'      => 'post maksimal 256 karakter',
                'media.max'        => 'maksimal 4 file media',
                'media.*.image'    => 'file harus berupa gambar',
                'media.*.max'      => 'ukuran gambar maksimal 2MB',
            ];
    }
}
