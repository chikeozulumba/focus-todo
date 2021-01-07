<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash',
        'todo_id',
        'user_id',
        'description',
        'schedule',
        'completed_at',
    ];


    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function todo()
    {
        return $this->belongsTo(\App\Models\Todo::class);
    }
}
