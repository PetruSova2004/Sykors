<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusSeeder extends Seeder
{
    /**
     * Seed the task_statuses table.
     *
     * @return void
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'In Queue'],
            ['name' => 'Completed'],
            ['name' => 'Canceled'],
            ['name' => 'Paused'],
        ];

        DB::table('task_statuses')->insert($statuses);
    }
}
