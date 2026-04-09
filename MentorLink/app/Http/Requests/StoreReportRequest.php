<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'reported_id' => 'required|exists:users,id|different:' . $this->user()->id,
            'reason'      => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ];
    }
}
