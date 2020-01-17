<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;

use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use App\ModelAndRepository\Users\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use App\ModelAndRepository\Categories\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ModelAndRepository\Categories\Repository\CategoryRepository;

class CategoryTest extends TestCase
{

    use RefreshDatabase;
    
    /** @test */
    public function user_can_get_root_category(){
        [$category1,$category2] = factory(Category::class,2)->create();
        
        $name = "dummy;";
        $category1->children()->create(["name"=> $name,"slug"=>Str::slug($name)]);
        //rootは二つなので二つほしい
        //controllerからのreturnはIlluminate\Http\JsonResponseとなってしまうのでjson()に変換してあげないとダメ
        $response = $this->get("/api/categories");
        $this->assertCount(3,$response->json());
    }
    
    //testだとよっぽど重要じゃない限りvalidationTestはいらないのかな？
    /** @test */
    public function authorized_user_can_create_category(){
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $name = "dummy";
        //slugは入力時に補完、parent_idがない場合はnullを
        $category = [
            "name"=>$name,
            "image"=>$file = UploadedFile::fake()->image('category.jpg',500,500)
        ];
        //ログインしてなかったり、必要な権利を持ってなかったら403
        

        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン
        $this->post("/api/categories",$category);
        //ここではpostできたかだけ
        $this->assertDatabaseHas("categories",["name" => $name]);
        //ちょっと確かめることが多すぎるから手間だけどテストを分割したほうがいいか？(validationとかExceptionとかいろいろあるし・・・)
    }

     /** @test */
     public function unauthorized_user_can_not__create_category(){
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $name = "dummy";
        //slugは入力時に補完、parent_idがない場合はnullを
        $category = [
            "name"=>$name,
            "image"=>$file = UploadedFile::fake()->image('category.jpg',500,500)
        ];
        //ログインしてなかったり、必要な権利を持ってなかったら403
        $this->post("/api/categories",$category)->assertStatus(403);
       
    }
    
    /** @test */
    public function can_not_send_not_image(){
        $user = factory(User::class)->create();
        $name = "dummy";
        //slugは入力時に補完、parent_idがない場合はnullを
        $category = [
            "name"=>$name,
            "image"=>"not image"
        ];
    
        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン

        //validationはsessionError
        $this->post("/api/categories",$category)->assertSessionHasErrors("image");    
    }

      /** @test */
      public function image_can_be_stored(){
        $user = factory(User::class)->create();
        $name = "dummy";
        //slugは入力時に補完、parent_idがない場合はnullを
        $category = [
            "name"=>$name,
            "image"=>$file = UploadedFile::fake()->image('category.jpg',500,500)
        ];
    
        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン
        $postedCategory =  $this->post("/api/categories",$category);
        //validationはsessionError
        $this->assertEquals(asset("categories/".$file->hashName()),asset($postedCategory["image"]));

        //diskにもあるか？
        Storage::disk("public")->assertExists("categories/".$file->hashName());
    }
    /** @test */
    public function can_not_send_not_duplicated_category_name(){
        $user = factory(User::class)->create();
        $name = $this->category->name;
        //slugは入力時に補完、parent_idがない場合はnullを
        $category = [
            "name"=>$name,
            "image"=>$file = UploadedFile::fake()->image('category.jpg',500,500)
        ];
        
        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン

        //validationはsessionError
        $this->post("/api/categories",$category)->assertSessionHasErrors("name");    
    }
    /** @test */
    public function authorized_user_can_send_parent_id(){
        $user = factory(User::class)->create();
        $name = "dummy";
        //slugは入力時に補完、parent_idがない場合はnullを
        $category = [
            "name"=>$name,
            "image"=>$file = UploadedFile::fake()->image('category.jpg',500,500),
            "parent_id"=>$this->category->id
        ];
        
        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン
        
        //validationはsessionError
        $postedCategory = $this->post("/api/categories",$category);
        $this->assertEquals($postedCategory["parent_id"],$this->category->id);    
    }
    /** @test */
    public function can_not_send_non_exist_parent_id(){
        $user = factory(User::class)->create();
        $name = $this->category->name;
        //slugは入力時に補完、parent_idがない場合はnullを
        $category = [
            "name"=>$name,
            "image"=>$file = UploadedFile::fake()->image('category.jpg',500,500),
            "parent_id" => 9999
        ];
        
        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン

        //validationはsessionError
        $this->post("/api/categories",$category)->assertSessionHasErrors("parent_id");    
    }

    /** @test */
    public function authorized_user_can_update_category(){
        $differentCategory = factory(Category::class)->create();
        $user = factory(User::class)->create();
        //slugは入力時に補完、parent_idがない場合はnullを
        $input = [
            "name"=>"dummy",
            "image"=>$file = UploadedFile::fake()->image('category.jpg',500,500),
            "parent_id" => $this->category->id
        ];
        
        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン

        //validationはsessionError
        $postedCategory = $this->post("/api/categories",$input);    
         
        $input = 
        ["name"=>"dummy2",
        "image"=>$file2 = UploadedFile::fake()->image('category.jpg',500,500),
        "parent_id" => $differentCategory->id];
   
        //update開始
        $this->patch("/api/categories/".$postedCategory["id"],$input);

        $category = Category::where("id",$postedCategory["id"])->first();
        
       
            $this->assertEquals($category->name,$input["name"]);
            $this->assertEquals($category->parent_id,$input["parent_id"]);
            $this->assertEquals(asset($category->image),asset("categories/".$file2->hashName())); 
            $aaa = Category::where("id",$category->parent_id)->first();
            //親のほうもしっかり更新されているか？
            $childId = $differentCategory->fresh()->descendants()->first()->id;

            
            $this->assertEquals($childId,$category->id);
 
    }

     /** @test */
     public function authorized_user_can_delete_category(){
        $user = factory(User::class)->create();
        //slugは入力時に補完、parent_idがない場合はnullを
        $input = [
            "name"=>"dummy",
            "image"=>$file = UploadedFile::fake()->image('category.jpg',500,500),
            "parent_id" => $this->category->id
        ];
        
        $user->assignRole("staff");
        $this->signIn($user);//staffでログイン

        $postedCategory = $this->post("/api/categories",$input);  
   
        //delete開始
        $this->delete("/api/categories/".$postedCategory["id"]);

        $this->assertDatabaseMissing("categories",["name" => $postedCategory["name"]]);
        $this->assertCount(0,$this->category->fresh()->descendants()->get());
    }

     /** @test */
     public function can_get_category_with_product_and_children(){
        $category2 = factory(Category::class)->create([
            "parent_id" => $this->category->id
        ]);
        $category3 = factory(Category::class)->create([
            "parent_id" => $category2->id
        ]);
        $this->cateRepo->attachProducts($category3->id,[$this->product->id]);
        

        //categoryIdからcategoryModelと、そのchildren、productが返ってくるようにしたい
        $categoryData = $this->get("/api/categories/".$category2->id);
       
        $this->assertEquals($category2->id,$categoryData["category"]["id"]);
        $this->assertEquals($category2->children->toArray(),$categoryData["children"]);
        
        foreach (array_map(null,$this->cateRepo->getProducts($category2->id)->toArray(),$categoryData["products"]) as [$val1,$val2]){
            $this->assertEquals($val1["id"],$val2["id"]);
        }
    }

}
