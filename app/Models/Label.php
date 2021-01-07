<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'display_title',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Each label can have many todos.
     *
     */
    public function todos()
    {
        return $this->belongsToMany(\App\Models\Todo::class, 'todos_labels');
    }
}
