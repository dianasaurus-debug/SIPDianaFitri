<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'nomor_hp',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
    public function hasRole($roles)
    {
        $this->have_role = $this->getUserRole();

        if(is_array($roles)){
            foreach($roles as $need_role){
                if($this->cekUserRole($need_role)) {
                    return true;
                }
            }
        } else{
            return $this->cekUserRole($roles);
        }
        return false;
    }
    private function getUserRole()
    {
        return $this->role()->getResults();
    }

    private function cekUserRole($role)
    {
        return (strtolower($role)==strtolower($this->have_role->nama)) ? true : false;
    }
    public function scopeWithFilters($query, $role, $created_at)
    {
        $yesterday = date("Y-m-d", strtotime( '-1 days' ) );
        if($created_at=='all'){
            if($role==1||$role==2){
                return $query->where('role_id', $role);
            }
            else if($role=='all'){
                return $query->whereIn('role_id', [1,2]);
            }
        }
        else if($created_at=='7d'){
            if($role==1||$role==2){
                return $query->where('role_id', $role)
                    ->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay());
            }
            else if($role=='all') {
                return $query->whereIn('role_id', [1,2])
                    ->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay());
            }
        }
        else if($created_at=='yd'){
            if($role==1||$role==2){
                return $query->where('role_id', $role)
                    ->whereDate('created_at', $yesterday);
            }
            else if($role=='all') {
                return $query->whereIn('role_id', [1,2])
                    ->whereDate('created_at', $yesterday);
            }
        }
        else if($created_at=='2d'){
            if($role==1||$role==2){
                return $query->where('role_id', $role)
                    ->whereDate('created_at', Carbon::today());
            }
            else if($role=='all') {
                return $query->whereIn('role_id', [1,2])
                    ->whereDate('created_at', Carbon::today());
            }
        }


    }
}
