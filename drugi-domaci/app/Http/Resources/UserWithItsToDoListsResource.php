<?php

namespace App\Http\Resources;

use App\Models\ToDoList;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWithItsToDoListsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $lists = ToDoList::get()->where('user_id', $this->resource->id);
        return [
            'id' => $this->resource->id,
            'username' => $this->resource->username,
            'email' => $this->resource->email,
            'to_do_lists' => ToDoListWithoutUserResource::collection($lists),
        ];
    }
}
