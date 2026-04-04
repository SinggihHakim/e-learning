<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
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
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date|after:now',
            'component_id' => 'nullable|exists:grade_components,id',
        ];
    }
}
