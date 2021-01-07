<?php

namespace App\Observers;

use App\Models\Task;
use Hashids\Hashids;
use Illuminate\Support\Str;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @param \App\Models\Task  $task
     *
     * @return void
     */
    public function creating(Task $task)
    {
        $hashId = new Hashids(Str::slug($task->description), 10);
        $task->hash = $hashId->encode(strtotime(now()));
    }
}
