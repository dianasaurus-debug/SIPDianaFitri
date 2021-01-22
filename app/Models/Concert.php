<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Concert extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'idol_id');
    }

    public function scopeWithFilters($query, $idol_id, $tanggal)
    {
        if ($tanggal == 'all') {
            if ($idol_id != 'all') {
                return $query->where('idol_id', $idol_id);
            } else if ($idol_id == 'all') {
                return $query->where('idol_id', '!=', null);
            }
        }
        else if ($tanggal == 'tm') {
            if ($idol_id != 'all') {
                return $query->where('idol_id', $idol_id)
                    ->whereMonth('tanggal', '=', Carbon::now()->month);
            } else if ($idol_id == 'all') {
                return $query->whereMonth('tanggal', '=', Carbon::now()->month);
            }
        } else if ($tanggal == 'nm') {
            if ($idol_id != 'all') {
                return $query->where('idol_id', $idol_id)
                    ->whereMonth('tanggal', '=', Carbon::now()->addMonthNoOverflow(1)->month);
            } else if ($idol_id == 'all') {
                return $query->whereMonth('tanggal', '=', Carbon::now()->addMonthNoOverflow(1)->month);
            }
        } else if ($tanggal == 'ty') {
            if ($idol_id != 'all') {
                return $query->where('idol_id', $idol_id)
                    ->whereYear('tanggal', Carbon::now()->year);
            } else if ($idol_id == 'all') {
                return $query->whereYear('tanggal', Carbon::now()->year);
            }

        }
        else if ($tanggal == 'tw') {
            if ($idol_id != 'all') {
                return $query->where('idol_id', $idol_id)
                    ->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } else if ($idol_id == 'all') {
                return $query->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            }

        }
    }
    public function scopePrices($query, $priceRanges){
        if ($priceRanges) {
            return $query->whereBetween('price', $priceRanges);
        }
    }

}
