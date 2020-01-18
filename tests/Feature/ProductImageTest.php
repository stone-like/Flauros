<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\ModelAndRepository\Users\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorized_user_can_upload_images(){
       $user = factory(User::class)->create();
       $images = [
        $file1 = UploadedFile::fake()->image('cover.jpg', 600, 600),
        $file2 = UploadedFile::fake()->image('cover.jpg', 600, 600),
        $file3 = UploadedFile::fake()->image('cover.jpg', 600, 600)
     ];
       $product = [
           "name"=>"dummy",    
           "category_id" => $this->category->id,
           "quantity" => 3,
           "price" =>600,
           "images" => $images
       ];
       
       $user->assignRole("staff");
       $this->signIn($user);//staffでログイン

       $postedProduct = $this->post("/api/products",$product);
        
        $proImgs = $this->proRepo->getImages($postedProduct["id"]);

        $this->assertDatabaseHas("product_images",["image" => ("products/".$file1->hashName())]);
        $this->assertDatabaseHas("product_images",["image" => ("products/".$file2->hashName())]);
        $this->assertDatabaseHas("product_images",["image" => ("products/".$file3->hashName())]);
        foreach($proImgs as $img){

            Storage::disk("public")->assertExists($img->image);
        }
    } 

    /** @test */
    public function can_not_upload_non_image(){
   
        $user = factory(User::class)->create();
        $images = [
         $file1 = UploadedFile::fake()->image('cover.jpg', 600, 600),
         $file2 = UploadedFile::fake()->image('cover.jpg', 600, 600),
         $file3 = "not image"
      ];
        $product = [
            "name"=>"dummy",    
            "category_id" => $this->category->id,
            "quantity" => 3,
            "price" =>600,
            "images" => $images
        ];
        
        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン
        
        //配列の場合はassertSessionHasErrorsInでやるとよい
        $this->post("/api/products",$product)->assertSessionHasErrorsIn("images");
     } 
    
}
