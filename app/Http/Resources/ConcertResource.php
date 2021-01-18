<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConcertResource extends JsonResource
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
            'judul' => $this->judul,
            'tempat' => $this->tempat,
            'tanggal' => $this->tanggal,
            'duration' => $this->duration,
            'gambar' => $this->gambar,
            'idol_id' => $this->idol_id,
            'jumlah_seat' => $this->jumlah_seat,
            'idol' => $this->category->nama_idol,
            'price' => $this->price
        ];
    }
}
