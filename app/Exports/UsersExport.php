<?php

namespace App\Exports;

use App\Http\Resources\UserResource;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected $role, $created_at;

    function __construct($role, $created_at) {
        $this->role = $role;
        $this->created_at = $created_at;
    }

//    /**
//    * @return \Illuminate\Support\Collection
//    */
    public function collection()
    {
        $users = User::withFilters(
            $this->role,
            $this->created_at
        )->get();
        return UserResource::collection($users);
    }

    public function headings(): array
    {
        return [
            'id',
            'Name',
            'E-Mail',
            'Username',
            'Role',
            'Role Id',
            'Nomor HP',
            'Tanggal Dibuat'
        ];
    }

}
