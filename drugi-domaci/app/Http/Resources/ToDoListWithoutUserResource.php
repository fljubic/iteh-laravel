<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Resources\Json\JsonResource;

class ToDoListWithoutUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tasks = Task::get()->where('to_do_list_id', $this->resource->id);
        return [
            'id' => $this->resource->id,
            'category' => new CategoryResource($this->resource->category),
            'title' => $this->resource->title,
            'created_at' => $this->resource->created_at,
            'tasks' => TaskResource::collection($tasks)
        ];
    }
}
