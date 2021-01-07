<?php

namespace App\Http\Resources;

use App\Models\Label as ModelsLabel;
use App\Http\Resources\Todo as TodoResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Label extends JsonResource
{
    public $opts = [];
    /**
     * __construct
     *
     * @param  mixed $resource
     * @return void
     */
    public function __construct(ModelsLabel $resource, $opts = [ "relations" => [ 'todos'] ])
    {
        $this->resource = $resource;
        $this->opts = $opts;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $conditionals = [
            'todos' => TodoResource::collection($this->todos),
        ];

        return array_merge(
            parent::toArray($request),
            $conditionals
        );
    }
}
