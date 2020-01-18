<?php
namespace App\ModelAndRepository\Prefectures;




use Illuminate\Database\Eloquent\Model;
use App\ModelAndRepository\Countries\Country;

class Prefecture extends Model
{
    protected $fillable  = [
        "name",
        "country_id"
    ];

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
