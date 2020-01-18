<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\ModelAndRepository\Countries\Country;

use App\ModelAndRepository\Prefectures\Prefecture;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function loggedIn_user_can_create_address(){
       
        $this->signIn();//一般ユーザーでlogin
        $data = [
            "zip"=>"333-4444",
            "address1"=>"Shinjyuku 5-18-6",
            "country_id"=>1,
            "prefecture_id"=>1,
        ];

        $postedAddress = $this->post("/api/addresses",$data);

        $this->assertEquals($postedAddress["address1"],$data["address1"]);
    }

    /** @test */
    public function guest_can_not_create_address(){
       
       
        $data = [
            "zip"=>"333-4444",
            "address1"=>"Shinjyuku 5-18-6",
            "country_id"=>1,
            "prefecture_id"=>1,
        ];

        $this->post("/api/addresses",$data)->assertStatus(403);     
    }

    /** @test */
    public function non_existing_country_is_invalid(){
       
       
        $this->signIn();//一般ユーザーでlogin
       
        $data = [
            "zip"=>"333-4444",
            "address1"=>"Shinjyuku 5-18-6",
            "country_id"=>999,
            "prefecture_id"=>1,
        ];

        $this->post("/api/addresses",$data)->assertSessionHasErrors("country_id");     
    }
    /** @test */
    public function non_existing_prefecture_is_invalid(){
       
       
        $this->signIn();//一般ユーザーでlogin
       
        $data = [
            "zip"=>"333-4444",
            "address1"=>"Shinjyuku 5-18-6",
            "country_id"=>1,
            "prefecture_id"=>999,
        ];

        $this->post("/api/addresses",$data)->assertSessionHasErrors("prefecture_id");     
    }

    /** @test */
    public function loggedIn_user_can_update_address(){
       
       
        $this->signIn();//一般ユーザーでlogin
       
        $data = [
            "zip"=>"333-4444",
            "address1"=>"Shinjyuku 5-18-6",
            "country_id"=>1,
            "prefecture_id"=>1,
        ];

        $postedAddress = $this->post("/api/addresses",$data);
        
        $data = [
            "zip"=>"333-4444",
            "address1"=>"Mirai 2-3-1",
            "country_id"=>2,
            "prefecture_id"=>12,
        ];

        $this->patch("/api/addresses/".$postedAddress["id"],$data);

        $this->assertDatabaseHas("addresses",["address1"=>$data["address1"]]);   
        $this->assertDatabaseHas("addresses",["country_id"=>$data["country_id"]]);        
        $this->assertDatabaseHas("addresses",["prefecture_id"=>$data["prefecture_id"]]);        

    }

     /** @test */
     public function loggedIn_user_can_delete_address(){
       
       
        $this->signIn();//一般ユーザーでlogin
       
        $data = [
            "zip"=>"333-4444",
            "address1"=>"Shinjyuku 5-18-6",
            "country_id"=>1,
            "prefecture_id"=>1,
        ];

        $postedAddress = $this->post("/api/addresses",$data);
        
        $this->delete("/api/addresses/".$postedAddress["id"]);

        

        $this->assertDatabaseMissing("addresses",["address1"=>$data["address1"]]);   
        $this->assertDatabaseMissing("addresses",["country_id"=>$data["country_id"]]);        
        $this->assertDatabaseMissing("addresses",["prefecture_id"=>$data["prefecture_id"]]);        

    }
}
