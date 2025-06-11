<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mfc_id_number' => $this->mfc_id_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'contact_number_verified_at' => $this->contact_number_verified_at,
            'username' => $this->username,
            'avatar' => URL::asset('uploads') . '/avatars/' . $this->avatar,
            'country_code' => $this->country_code,
            'contact_number' => $this->contact_number,
            'section_id' => $this->section_id,
            'role_id' => (string) $this->role_id,
            'area' => $this->area,
            'chapter' => $this->chapter,
            'gender' => $this->gender,
            'status' => $this->status,
            'user_details' => $this->whenLoaded('user_details', function () {
                return UserDetailResource::make($this->user_details);
            }),
            'section' => $this->whenLoaded('section', function () {
                return SectionResource::make($this->section);
            })
        ];
    }
}
