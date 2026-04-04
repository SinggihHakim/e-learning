<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeAttemptRequest extends FormRequest
{
    /**
     * Menentukan otoritas untuk permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mengembalikan aturan rincian (validation rules) untuk menyimpan nilai kuis siswa.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'points' => 'required|array',
            'points.*' => 'nullable|integer|min:0',
        ];
    }
}
