<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ToDoListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'user' => new UserResource($this->resource->user),
            'category' => new CategoryResource($this->resource->category),
            'title' => $this->resource->title,
            'created_at' => $this->resource->created_at,
            'tasks' => TaskResource::collection($this->resource->tasks)
        ];
    }
}
