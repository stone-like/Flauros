<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;

use Illuminate\Foundation\Testing\WithFaker;
use App\ModelAndRepository\Categories\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
}
