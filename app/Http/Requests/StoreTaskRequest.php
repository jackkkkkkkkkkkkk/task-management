<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'status'      => [
                'nullable',
                \Illuminate\Validation\Rule::in([
                    Task::STATUS_DOING,
                    Task::STATUS_POSTPONED,
                    Task::STATUS_DONE,
                ]),
            ],
            'priority'    => [
                'nullable',
                \Illuminate\Validation\Rule::in([
                    Task::PRIORITY_LOW,
                    Task::PRIORITY_MEDIUM,
                    Task::PRIORITY_UP,
                ]),
            ],
            'deadline'    => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
        ];
    }
}
