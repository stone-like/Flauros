<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;



class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created(){
         $images = [
            $file1 = UploadedFile::fake()->image('cover.jpg', 600, 600),
            $file2 = UploadedFile::fake()->image('cover.jpg', 600, 600),
            $file3 = UploadedFile::fake()->image('cover.jpg', 600, 600)
         ];
         $this->proRepo->saveImages($this->product->id,$images);
         $proImgs = $this->proRepo->getImages($this->product->id);

        $this->assertDatabaseHas("product_images",["image" => ("products/".$file1->hashName())]);
        $this->assertDatabaseHas("product_images",["image" => ("products/".$file2->hashName())]);
        $this->assertDatabaseHas("product_images",["image" => ("products/".$file3->hashName())]);
        //storageにもしっかりアップロードされているか？
        foreach($proImgs as $img){

            Storage::disk("public")->assertExists($img->image);
        }
    }

     /** @test */
     public function successfully_delete_product_with_images(){
        $images = [
           $file1 = UploadedFile::fake()->image('cover.jpg', 600, 600),
           $file2 = UploadedFile::fake()->image('cover.jpg', 600, 600),
           $file3 = UploadedFile::fake()->image('cover.jpg', 600, 600)
        ];
        $this->proRepo->saveImages($this->product->id,$images);
        $proImgs = $this->proRepo->getImages($this->product->id);

       $this->proRepo->deleteProduct($this->product->id);
       $this->assertDatabaseMissing("product_images",["image" => ("products/".$file1->hashName())]);
       $this->assertDatabaseMissing("product_images",["image" => ("products/".$file2->hashName())]);
       $this->assertDatabaseMissing("product_images",["image" => ("products/".$file3->hashName())]);
       //diskの方はdeleteすべきか・・・？,今のところuploadでもdeleteでもそのままにしている
       
   }


}
