<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssignmentRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan untuk memproses permintaan logika formulir ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mengembalikan aturan-aturan validasi formulir (form validation rules).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'component_id' => 'nullable|exists:grade_components,id',
        ];
    }
}
