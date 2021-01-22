<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TicketCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function concerts()
    {
        return $this->hasMany(Concert::class);
    }

    public function scopeWithFilters($query, $created_at)
    {
        $yesterday = date("Y-m-d", strtotime('-1 days'));
        if ($created_at == 'all') {
            return $query->where('created_at', '!=', null);
        } else if ($created_at == '7d') {
            return $query->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay());
        } else if ($created_at == 'yd') {
            return $query->whereDate('created_at', $yesterday);
        } else if ($created_at == '2d') {
            return $query->whereDate('created_at', Carbon::today());
        }
    }
}
