<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [
        'additional_data' => 'not_set'
    ];

    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getAdditionalDataAttribute($additional_data){
        if($additional_data == 'not_set'){
            return 0;
        }
        return json_decode($additional_data);
    }

    public function getPicAttribute(){
        if (file_exists( public_path() . '/uploads/profiles/' . $this->photo )){
            return asset("uploads/profiles/".$this->photo);
        }else{
            return asset("uploads/profiles/default.png");
        }
    }
}
