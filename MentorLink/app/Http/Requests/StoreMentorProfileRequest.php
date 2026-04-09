<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMentorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isMentor();
    }

    public function rules(): array
    {
        return [
            'domains'     => 'required|array|min:1',
            'domains.*'   => 'string|max:100',
            'hourly_rate' => 'nullable|numeric|min:0',
            'bio'         => 'nullable|string|max:1000',
        ];
    }
}
