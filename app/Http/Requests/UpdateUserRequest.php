<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $userId],
            'role' => ['required', 'in:admin,teacher,student'],
        ];

        if ($this->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        return $rules;
    }
}
