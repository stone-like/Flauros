<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\ModelAndRepository\Countries\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;



class AddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_country_and_prefecture(){
        $country = Country::where("id",1)->first();
        $prefecture = $country->prefectures()->get()->random();
        
        $data = [
            "zip"=>"333-4444",
            "address1"=>"Shinjyuku 5-18-6",
            "country_id"=>$country->id,
            "prefecture_id"=>$prefecture->id,
        ];
       $this->signIn();
       $address = $this->addressRepo->createAddress($data);
       
       $this->assertEquals($country->name,$address->country->name);
       $this->assertEquals($prefecture->name,$address->prefecture->name);
    }
}
