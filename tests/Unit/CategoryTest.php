<?php

namespace Tests\Unit;


use Tests\TestCase;
use Illuminate\Support\Str;
use App\ModelAndRepository\Users\User;
use App\ModelAndRepository\Categories\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ModelAndRepository\Categories\Repository\CategoryRepository;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /*
     * A basic unit test example.
     *
     * @return void
     */
    /** @test */
    public function it_can_have_child()
    {
        $category = factory(Category::class)->create();
        $name = "dummy;";
        $child = $category->children()->create(["name"=> $name,"slug"=>Str::slug($name)]);

        $children = $category->ancestors()->get();

        foreach($children as $c){
        $this->assertEquals($child->name,$c->name);
        }
    }

    /**@test */
    public function it_can_get_parent(){
        $category = factory(Category::class)->create();
        $name = "dummy;";
        $child = $category->children()->create(["name"=> $name,"slug"=>Str::slug($name)]);
        $parents = $child->descendants()->get();

        foreach($parents as $p){
            $this->assertEquals($category->name,$p->name);
        }
    }
    //ユーザーも管理者もcategoryを検索する機会はないのでtestなし
    /** @test */
    public function it_can_be_updated(){
        $category = factory(Category::class)->create();
        $category->update(["name" => "dummy"]);
        $this->assertEquals($category->name,"dummy");
    }
    /** @test */
    public function it_can_be_deleted(){
        [$category1,$category2] = factory(Category::class,2)->create();
        $category1->delete();
        $this->assertDatabaseMissing("categories",["id"=>$category1->id]);
        $this->assertDatabaseHas("categories",["id"=>$category2->id]);

    }
    //基礎的なやつはテストしなくてもいい気がしてきた・・・hasChildとか基本的なのじゃないやつはやっておいたほうがいいと思うけど,テストするとしたらmodelかrepositoryに操作を押し込んでからとか？
    //unitとfeatureで内容が重複するtestはunitだけでいいかもしれない
    /** @test */
    public function it_can_get_all_categories(){
        [$category1,$category2] = factory(Category::class,2)->create();
        $name = "dummy";
        $category1->children()->create(["name"=> $name,"slug"=>Str::slug($name)]);
        //rootは二つなので二つほしい+dummyで作っておいたやつを足すと３
        
        $categoryRepo= new CategoryRepository($this->category);
        $rootCategories = $categoryRepo->getRootCategory();
        $this->assertCount(3,$rootCategories);
    }


    
}
