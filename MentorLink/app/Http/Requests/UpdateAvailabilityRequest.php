<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isMentor();
    }

    public function rules(): array
    {
        return [
            'day_of_week' => 'sometimes|integer|min:0|max:6',
            'start_time'  => 'sometimes|date_format:H:i',
            'end_time'    => 'sometimes|date_format:H:i|after:start_time',
        ];
    }
}
