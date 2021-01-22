<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'nama_idol' => $this->nama_idol,
            'desc' => $this->desc,
            'foto' => $this->foto,
            'foto_path' => 'localhost:8000/images/idols/'.$this->foto,
        ];
    }
}
