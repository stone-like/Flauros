<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_can_be_deleted(){
        
        $name = "dummy";
        $product = [
            "name"=>$name,
            "slug"=>Str::slug($name),
            "category_id" => $this->category->id,
            "quantity" => 3,
            "price" =>600,
            "weight"=>500,

            "status" => "3 items left"
        ];
        $postedProduct = $this->proRepo->createProduct($product);
        $this->proRepo->deleteProduct($postedProduct->id);
        
        $this->assertDatabaseMissing("products",["name"=>$postedProduct->name]);
       

       
    }
}
