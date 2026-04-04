<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeSubmissionRequest extends FormRequest
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
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
        ];
    }
}
