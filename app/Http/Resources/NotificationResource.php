<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "type" => $this->type,
            "notifiable_type" => $this->notifiable_type,
            "notifiable_id" => $this->notifiable_id,
            "data" => json_decode($this->data),
            "read_at" => $this->read_at,
        ];
    }
}
