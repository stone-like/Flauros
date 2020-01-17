<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Str;
use App\ModelAndRepository\Products\Product;
use App\ModelAndRepository\Categories\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ModelAndRepository\Products\Repository\ProductRepository;
use App\ModelAndRepository\Categories\Repository\CategoryRepository;



class CategoryProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function category_attach_product(){
        $diffProduct = factory(Product::class)->create();
        $category = factory(Category::class)->create();
        $name = "dummy";
        $child = $category->children()->create(["name"=> $name,"slug"=>Str::slug($name),"parent_id"=>$category->id]);
        $name2 = "dummy2";
        $grandSon = $child->children()->create(["name"=> $name2,"slug"=>Str::slug($name2),"parent_id"=>$child->id]);

        
        $this->cateRepo->syncProducts($grandSon->id,[$this->product->id,$diffProduct->id]);

        $this->assertCount(2,$this->cateRepo->getProducts($grandSon->id));
    }

     /** @test */
     public function category_detach_product(){
        $diffProduct = factory(Product::class)->create();
        $category = factory(Category::class)->create();
        $name = "dummy";
        $child = $category->children()->create(["name"=> $name,"slug"=>Str::slug($name),"parent_id"=>$category->id]);
        $name2 = "dummy2";
        $grandSon = $child->children()->create(["name"=> $name2,"slug"=>Str::slug($name2),"parent_id"=>$child->id]);
        
        $this->cateRepo->syncProducts($grandSon->id,[$this->product->id,$diffProduct->id]);
        
        $this->cateRepo->detachProducts($grandSon->id,[]);
        $this->assertCount(0,$this->cateRepo->getProducts($grandSon->id));
    }

    /** @test */
    public function can_add_child_category_from_grandson_category(){
        $category2 = factory(Category::class)->create([
            "parent_id" => $this->category->id
        ]);
        $category3 = factory(Category::class)->create([
            "parent_id" => $category2->id
        ]);
        
        $this->cateRepo->attachProducts($category3->id,[$this->product->id]);
        //product登録の際にcat3とその祖先(root以外)に登録すればいいか？
        //いやrootにも登録していいけどrootからはproductの情報とってこなければいいだけ
        $this->assertCount(1,$this->cateRepo->getProducts($category2->id));
        $this->assertCount(1,$this->cateRepo->getProducts($category3->id));  

    }
    /** @test */
    public function can_delete_child_category_from_grandson_category(){
        $category2 = factory(Category::class)->create([
            "parent_id" => $this->category->id
        ]);
        $category3 = factory(Category::class)->create([
            "parent_id" => $category2->id
        ]);
        
        $this->cateRepo->attachProducts($category3->id,[$this->product->id]);
        //product登録の際にcat3とその祖先(root以外)に登録すればいいか？
        //いやrootにも登録していいけどrootからはproductの情報とってこなければいいだけ
        
        //cat3とcat2に登録されているのでcat3だけでなくcat2もdeleteしたい
        //deleteするときはcatを指定するんじゃなくて全てのcatからproductをdeleteすればいいからproductからdeleteすればよさそう
        //product->categories()->detach()って感じ
        
        $this->proRepo->deleteProduct($this->product->id);//中間テーブルにonDeleteをしっかり設定しておけばOK,productをのこしておきたければdetachで

        
        $this->assertCount(0,$this->cateRepo->getProducts($category2->id));
        $this->assertCount(0,$this->cateRepo->getProducts($category3->id));  

    }

     
}
