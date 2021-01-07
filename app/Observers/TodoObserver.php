<?php

namespace App\Observers;

use App\Models\Todo;
use Hashids\Hashids;
use Illuminate\Support\Str;

class TodoObserver
{
    /**
     * Handle the Todo "creating" event.
     *
     * @param \App\Models\Todo  $todo
     *
     * @return void
     */
    public function creating(Todo $todo)
    {
        $hashId = new Hashids(Str::slug($todo->title), 10);
        $todo->hash = $hashId->encode(strtotime(now()));

    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleted(Todo $todo)
    {}
}
