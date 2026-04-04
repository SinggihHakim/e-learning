<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Menentukan otoritas untuk permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mengembalikan aturan rincian (validation rules) untuk menyimpan pertanyaan kuis.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,essay',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:type,multiple_choice|array',
            'options.*.text' => 'required_with:options|string',
            'correct_option' => 'required_if:type,multiple_choice',
        ];
    }
}
