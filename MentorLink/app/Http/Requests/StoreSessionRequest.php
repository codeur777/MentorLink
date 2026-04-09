<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isMentee();
    }

    public function rules(): array
    {
        return [
            'mentor_id'    => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'duration_min' => 'required|integer|min:30|max:180',
        ];
    }

    public function messages(): array
    {
        return [
            'scheduled_at.after' => 'La session doit être planifiée dans le futur.',
            'mentor_id.exists'   => "Le mentor sélectionné n'existe pas.",
        ];
    }
}
