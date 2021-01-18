<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function category()
    {
        return $this->belongsTo(TicketCategory::class,'idol_id');
    }

}
