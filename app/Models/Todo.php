<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'schedule',
        'priority',
        'completed_at',
        'archived',
        'hash',
        'user_id',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hash';
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class, 'todo_id');
    }
}
