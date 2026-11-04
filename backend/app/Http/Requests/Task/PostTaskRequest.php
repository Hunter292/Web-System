<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostTaskRequest extends FormRequest
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
            'date'=>['required',Rule::date()->format('Y-m-d')],
            'deadline'=>['required',Rule::date()->format('Y-m-d')],
            'priority'=>'required|boolean',
            'description'=>'required|string',
            'client_nip'=>'required|string',
            'victims'=>'required|array',
        ];
    }
}
