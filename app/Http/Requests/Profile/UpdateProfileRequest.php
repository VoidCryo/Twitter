<?php

namespace App\Http\Requests\Profile;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'display_name' => 'nullable|string|max:50',
            'bio'          => 'nullable|string|max:160',
            'location'     => 'nullable|string|max:30',
            'birthday'     => 'nullable|date',
            'avatar'       => 'nullable|image|max:2048',
            'banner'       => 'nullable|image|max:4096',
        ];
    }
}
