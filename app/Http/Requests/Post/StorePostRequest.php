<?php

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'content' => 'required_without:media|max:256',
            'media'   => 'required_without:content|array|max:4',
            'media.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
                'content.required_without' => 'harus di isi apabila tidak mengirim gambar',
                'media.required_without' => 'setidaknya kirim 1 gambar apabila tidak mengisi content',
                'content.max'      => 'post maksimal 256 karakter',
                'media.max'        => 'maksimal 4 file media',
                'media.*.image'    => 'file harus berupa gambar',
                'media.*.max'      => 'ukuran gambar maksimal 2MB',
            ];
    }
}
