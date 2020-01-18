<?php
namespace App\ModelAndRepository\Countries;



use Illuminate\Database\Eloquent\Model;
use App\ModelAndRepository\Prefectures\Prefecture;


class Country extends Model
{
    protected $fillable = [
        "name"
    ];

    public function prefectures(){
        return $this->hasMany(Prefecture::class);
    }
}
