<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PollResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'email' => $this->email ?? null,
            'expired' => isset($this->expires_at) && $this->expires_at < now(),
            'total_votes' => $this->whenCounted('votes'),
            'options' => PollOptionResource::collection($this->whenLoaded('options')),
            'expires_at' => $this->expires_at ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
