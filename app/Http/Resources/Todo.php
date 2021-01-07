<?php

namespace App\Http\Resources;

use App\Models\Todo as TodoModel;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Todo extends JsonResource
{
    public $opts = [];
    /**
     * __construct
     *
     * @param  mixed $resource
     * @return void
     */
    public function __construct(TodoModel $resource, $opts = [ "relations" => [ 'tasks'] ])
    {
        $this->resource = $resource;
        $this->opts = $opts;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $conditionals = [];
        if (in_array('tasks', $this->opts['relations'] ?? [])) {
            $conditionals['tasks'] = Task::collection($this->tasks);
        }
        if (in_array('labels', $this->opts['relations'] ?? [])) {
            $conditionals['labels'] = Label::collection($this->labels);
        }

        return
            array_merge(
                [
                    'title' => $this->title,
                    'description' => $this->description,
                    'priority' => $this->priority,
                    'schedule' => Carbon::parse($this->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'),
                    'schedule_friendly' => Carbon::parse($this->created_at)->diffForHumans(),
                    'id' => $this->hash,
                    'date_added' => Carbon::parse($this->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'),
                    'last_modified' => Carbon::parse($this->updated_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'),
                    'completed_at' => $this->completed_at ? Carbon::parse($this->completed_at)->isoFormat('MMMM Do YYYY, h:mm:ss a') : null,
                ],
                $conditionals
            );
    }
}
