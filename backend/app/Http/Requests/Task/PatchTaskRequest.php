<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatchTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date'=>['sometimes',Rule::date()->format('Y-m-d')],
            'deadline'=>['sometimes',Rule::date()->format('Y-m-d')],
            'priority'=>'sometimes|boolean',
            'description'=>'sometimes|string',
            'client_nip'=>'sometimes|string',
            'employee_id'=>'missing',
        ];
    }
}
