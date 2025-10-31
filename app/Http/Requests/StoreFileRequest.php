<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'File harus diupload.',
            'file.file' => 'File yang diupload tidak valid.',
            'file.max' => 'Ukuran file maksimal 10MB.',
            'file.mimes' => 'File harus berupa PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
        ];
    }
}
