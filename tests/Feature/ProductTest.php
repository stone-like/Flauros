<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\ModelAndRepository\Users\User;
use Illuminate\Foundation\Testing\WithFaker;
use App\ModelAndRepository\Categories\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ModelAndRepository\Categories\Repository\CategoryRepository;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorized_user_can_create_product(){
         $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $name = "dummy";
      
        $product = [
            "name"=>$name,
            "image"=>$file = UploadedFile::fake()->image('product.jpg',500,500),
            "category_id" => $this->category->id,
            "quantity" => 3,
            "price" =>600,
        ];
        
        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン

        $postedProduct = $this->post("/api/products",$product);
        $this->assertEquals($product["name"],$postedProduct["name"]);
    }

      /** @test */
      public function status_is_correctly_displayed(){

        $user = factory(User::class)->create();
        $name = "dummy";
        
        $product = [
            "name"=>$name,
            "image"=>$file = UploadedFile::fake()->image('product.jpg',500,500),
            "category_id" => $this->category->id,
            "quantity" => 3,
            "price" =>600,
        ];

        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン

        $postedProduct = $this->post("/api/products",$product);
        //setAttributeでstatusは埋めているのでcreateで返るより後、なのでfresh()がいる
        $this->assertEquals("3 items left",$postedProduct["status"]);
    }
     
    //違うrequestでも同じvalidationなら別にやらないでいいのかな？
     /** @test */
     public function non_existing_category_id_is_invalid(){

        $user = factory(User::class)->create();
        $name = "dummy";
       
        $product = [
            "name"=>$name,
            "image"=>$file = UploadedFile::fake()->image('product.jpg',500,500),
            "category_id" => 99999,
            "quantity" => 3,
            "price" =>600,
        ];

        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン

        $this->post("/api/products",$product)->assertSessionHasErrors("category_id");
       
    }

    /** @test */
    public function successfully_attach_to_child_from_grandson_category(){
        $category2 = factory(Category::class)->create([
            "parent_id" => $this->category->id
        ]);
        $category3 = factory(Category::class)->create([
            "parent_id" => $category2->id
        ]);
        $user = factory(User::class)->create();
        $name = "dummy";
        
        $product = [
            "name"=>$name,
            "image"=>$file = UploadedFile::fake()->image('product.jpg',500,500),
            "category_id" => $category3->id,
            "quantity" => 3,
            "price" =>600,
        ];

        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン

        $this->post("/api/products",$product);
        
    
        $this->assertCount(1,$this->cateRepo->getProducts($category2->id));
        $this->assertCount(1,$this->cateRepo->getProducts($category3->id));

       
    }
}
