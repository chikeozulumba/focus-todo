<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvertTaskToTodo extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'schedule' => 'required|date',
            'priority' => 'sometimes|numeric|min:1',
            'tasks' => 'nullable|array|min:1',
            'tasks.*.description' => 'required|string',
            'tasks.*.schedule' => 'required|date',
        ];
    }
}
