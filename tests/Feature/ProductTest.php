<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\ModelAndRepository\Users\User;
use App\ModelAndRepository\Products\Product;
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
            "category_id" => $this->category->id,
            "quantity" => 3,
            "price" =>600,
            "weight"=>500
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
           
            "category_id" => $this->category->id,
            "quantity" => 3,
            "price" =>600,
            "weight"=>500
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
           
            "category_id" => 99999,
            "quantity" => 3,
            "price" =>600,
            "weight"=>500
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
            "category_id" => $category3->id,
            "quantity" => 3,
            "price" =>600,
            "weight"=>500
        ];

        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン

        $this->post("/api/products",$product);
        
    
        $this->assertCount(1,$this->cateRepo->getProducts($category2->id));
        $this->assertCount(1,$this->cateRepo->getProducts($category3->id));

       
    }
    //validationがはいるupdateとcreateはunittestいらなさそうな気も・・・deleteはいいと思うけど
    /** @test */
    public function authorized_user_can_update_product(){
       
        // $this->withoutExceptionHandling();
       
       $user = factory(User::class)->create();
     
       $product = [
           "name"=>"dummy",
          
           "category_id" => $this->category->id,
           "quantity" => 3,
           "price" =>600,
           "weight"=>500
       ];
       
       $user->assignRole("staff");
       $this->signIn($user);//staffでログイン

       $postedProduct = $this->post("/api/products",$product);
    
       
       $product = [
        "name"=>"dummy2",
       
        "category_id" => $this->category->id,
        "quantity" => 0,
        "price" =>600,
        "weight"=>500
    ];
    
        $this->patch("/api/products/".$postedProduct["id"],$product);
        
        $targetProduct = $this->proRepo->findProductById($postedProduct["id"]);

        $this->assertEquals($targetProduct->name,$product["name"]);
        $this->assertEquals($targetProduct->status,"sold out"); 
   }
   
   /** @test */
   public function successfully_move_to_other_category(){
        $this->withoutExceptionHandling();

       $cat1Child = factory(Category::class)->create(["parent_id"=>$this->category->id]);
       $cat1Grandson = factory(Category::class)->create(["parent_id"=>$cat1Child->id]);

       $category2 = factory(Category::class)->create();
       $cat2Child = factory(Category::class)->create(["parent_id"=>$category2->id]);
       $cat2Grandson = factory(Category::class)->create(["parent_id"=>$cat2Child->id]);

       $user = factory(User::class)->create();
     
       $product = [
           "name"=>"dummy",
          
           "category_id" => $cat1Grandson->id,
           "quantity" => 3,
           "price" =>600,
           "weight"=>500
       ];
       
       $user->assignRole("staff");
       $this->signIn($user);//staffでログイン

       $postedProduct = $this->post("/api/products",$product);
    
       
       $product = [
        "name"=>"dummy2",
       
        "category_id" => $cat2Grandson->id,
        "quantity" => 0,
        "price" =>600,
        "weight"=>500
    ];
    
        $this->patch("/api/products/".$postedProduct["id"],$product);
        
        
        $this->assertCount(0,$this->cateRepo->getProducts($cat1Grandson->id));
        $this->assertCount(0,$this->cateRepo->getProducts($cat1Child->id));
        $this->assertCount(1,$this->cateRepo->getProducts($cat2Grandson->id));
        $this->assertCount(1,$this->cateRepo->getProducts($cat2Child->id));

   }

    /** @test */
    public function successfully_delete_with_category(){

       $cat1Child = factory(Category::class)->create(["parent_id"=>$this->category->id]);
       $cat1Grandson = factory(Category::class)->create(["parent_id"=>$cat1Child->id]);

    

       $user = factory(User::class)->create();
     
       $product = [
           "name"=>"dummy",
          
           "category_id" => $cat1Grandson->id,
           "quantity" => 3,
           "price" =>600,
           "weight"=>500
       ];
       
       $user->assignRole("staff");
       $this->signIn($user);//staffでログイン

       $postedProduct = $this->post("/api/products",$product);
        $this->delete("/api/products/".$postedProduct["id"]);
        
        $this->assertCount(0,$this->cateRepo->getProducts($cat1Child->id));
        $this->assertCount(0,$this->cateRepo->getProducts($cat1Grandson->id));

        

   }
}
