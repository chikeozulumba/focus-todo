<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Task extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'description' => $this->description,
            'schedule' => $this->schedule ? Carbon::parse($this->schedule)->isoFormat('MMMM Do YYYY, h:mm:ss a') : null,
            'schedule_friendly' => $this->schedule ? Carbon::parse($this->schedule)->diffForHumans() : null,
            'id' => $this->hash,
            'todo' => new Todo($this->todo, [ "relations" => [] ]),
            'date_added' => Carbon::parse($this->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'),
            'last_modified' => Carbon::parse($this->updated_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'),
            'completed_at' => $this->completed_at ? Carbon::parse($this->completed_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') : null,
        ];
    }
}
