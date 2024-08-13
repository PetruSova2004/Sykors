<?php

namespace App\Rules;

use App\Models\Task;
use Illuminate\Contracts\Validation\Rule;

class ValidTaskStatus implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $statusValues = [
            Task::STATUS_IN_QUEUE,
            Task::STATUS_COMPLETED,
            Task::STATUS_CANCELED,
            Task::STATUS_PAUSED,
        ];

        return in_array($value, $statusValues);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The selected status is invalid.';
    }
}
