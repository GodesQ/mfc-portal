<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TitheResource extends JsonResource
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
            'mfc_user_id' => $this->mfc_user_id,
            'transaction_id' => (int) $this->transaction_id,
            'payment_mode' => $this->payment_mode,
            'amount' => number_format($this->amount, 2),
            'for_the_month_of' => $this->for_the_month_of,
            'status' => $this->status,
            'created_at' => Carbon::parse($this->created_at)->format('m-d-Y H:i:s'),
            'user' => $this->whenLoaded('user', function () {
                return UserResource::make($this->user);
            })
        ];
    }
}
