<?php
namespace App\ModelAndRepository\Addresses;


use Illuminate\Database\Eloquent\Model;
use App\ModelAndRepository\Countries\Country;
use App\ModelAndRepository\Prefectures\Prefecture;

class Address extends Model
{
    protected $fillable = [
        "zip",
        "address1",
        "address2",
        "phoneNumber",
        "country_id",
        "user_id",
        "prefecture_id"
    ];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function prefecture(){
        return $this->belongsTo(Prefecture::class);
    }
}
