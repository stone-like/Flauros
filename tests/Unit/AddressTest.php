<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;



class AddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_country_and_prefecture(){
        $data = [
            "zip"=>"333-4444",
            "address1"=>"Shinjyuku 5-18-6",
            "country_id"=>1,
            "prefecture_id"=>1,
        ];
       $this->signIn();
       $address = $this->addressRepo->createAddress($data);
       dump($address);
    //    $this->assertEquals($address->)
    }
}
