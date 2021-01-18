<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'concert_id' => $this->concert_id,
            'user_id' => $this->user_id,
            'concert' => $this->concert->judul,
            'user' => $this->user->name,
            'harga' => $this->concert->price,
            'serial_number' => $this->serial_number,
            'seat_number' => $this->seat_number,
            'waktu_beli' => $this->created_at
        ];
    }
}
