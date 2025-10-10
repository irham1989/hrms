<?php

namespace App\Http\Requests\Performance;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'evaluation_period_id' => ['required','integer','exists:evaluation_periods,id'],
            'kegiatan_sumbangan'   => ['nullable','string'],
            'latihan_dihadiri'     => ['nullable','string'],
            'latihan_diperlukan'   => ['nullable','string'],
        ];
    }
}
