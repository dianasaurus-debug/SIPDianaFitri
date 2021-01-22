<?php

namespace App\Exports;

use App\Http\Resources\ConcertResource;
use App\Models\Concert;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConcertsExports implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $idol_id, $tanggal, $priceRanges = [];

    function __construct($idol_id, $tanggal, $priceRanges) {
        $this->idol_id = $idol_id;
        $this->tanggal = $tanggal;
        $this->priceRanges = $priceRanges;
    }

//    /**
//    * @return \Illuminate\Support\Collection
//    */
    public function collection()
    {
        $concerts = Concert::withFilters(
            $this->idol_id,
            $this->tanggal,
        )->prices($this->priceRanges)->get();
        return ConcertResource::collection($concerts);
    }

    public function headings(): array
    {
        return [
            'id',
            'Judul',
            'Tempat',
            'Tanggal',
            'Durasi (Menit)',
            'Nama Gambar',
            'Category ID',
            'Jumlah Seat',
            'Category Name' ,
            'Harga (IDR)'
        ];
    }
}
