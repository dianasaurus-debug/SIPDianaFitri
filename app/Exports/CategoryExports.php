<?php

namespace App\Exports;

use App\Http\Resources\CategoryResource;
use App\Models\TicketCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryExports implements FromCollection, WithHeadings
{
    protected $created_at;

    function __construct($created_at) {
        $this->created_at = $created_at;
    }
//    /**
//    * @return \Illuminate\Support\Collection
//    */
    public function collection()
    {
        $tickets = TicketCategory::withFilters(
            $this->created_at
        )->get();
        return CategoryResource::collection($tickets);
    }

    public function headings(): array
    {
        return [
            'id',
            'Nama Kategori',
            'Deskripsi',
            'Nama Image',
            'Link Image',
        ];
    }
}
