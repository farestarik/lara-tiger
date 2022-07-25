<?php

namespace App\Models;

use App\Utilities\FilterBuilder;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;



    protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute($name)
    {
        return ucfirst($name);
    }

    public function getPicAttribute($pic){
        if($pic == "default.png"){
            return asset("pics/default.png");
        }
    }


    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Utilities\UsersFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);
        return $filter->apply();
    }


    public function profile(){
        return $this->hasOne(\App\Models\Profile::class);
    }

}
