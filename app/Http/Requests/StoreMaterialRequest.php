<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan untuk memproses permintaan logika formulir ini.
     */
    public function authorize(): bool
    {
        return true; // We rely on Controller/Policy authorization
    }

    /**
     * Mengembalikan aturan-aturan validasi formulir (form validation rules).
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id'  => 'required|exists:courses,id',
            'title'      => 'required|string|max:255',
            'type'       => 'required|in:pdf,ppt,doc,video,other',
            'file'       => 'nullable|file|max:10240',
            'video_link' => 'nullable|url',
        ];
    }
}
