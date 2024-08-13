<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:tasks,id',
            'status' => 'required|in:completed,in_queue,canceled,paused',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => 'The task ID is required.',
            'id.integer' => 'The task ID must be an integer.',
            'id.exists' => 'The specified task does not exist.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be one of the following values: completed, in_queue, canceled, paused.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        $response = response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);

        throw new HttpResponseException($response);
    }

    /**
     * Get the status ID corresponding to the provided status.
     *
     * @return int|null
     */
    public function getStatusId(): ?int
    {
        $statusMap = [
            'completed' => Task::STATUS_COMPLETED,
            'in_queue' => Task::STATUS_IN_QUEUE,
            'canceled' => Task::STATUS_CANCELED,
            'paused' => Task::STATUS_PAUSED,
        ];

        return $statusMap[$this->input('status')] ?? null;
    }

}
