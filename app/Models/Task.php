<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'tasks';

    /**
     * Status IDs as constants
     */
    public const STATUS_IN_QUEUE = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_CANCELED = 3;
    public const STATUS_PAUSED = 4;

    /**
     * @var string[]
     */
    protected $fillable = [
        'url',
        'parameters',
        'execution_date',
        'status_id',
        'status_description',
        'exception',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'exception',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'parameters' => 'array',
        'execution_date' => 'datetime',
    ];

    /**
     * Get the status name from the task_statuses table.
     *
     * @return string|null
     */
    public function getStatusNameAttribute(): ?string
    {
        return DB::table('task_statuses')
            ->where('id', $this->status_id)
            ->value('name');
    }

}
