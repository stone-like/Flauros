<?php

namespace App\ModelAndRepository\Categories\Repository;

use Illuminate\Support\Collection;
use App\ModelAndRepository\Categories\Category;

class CategoryRepository implements CategoryRepositoryInterface{
    private $model;
    public function __construct(Category $category){
        $this->model = $category;
    }
    public function getRootCategory():Collection{
        //ここでEloquent使う、別にどんな手段を取ろうがcontrollerには全く関係ないのでEloquentを使ってもいい
        return Category::where("parent_id",NULL)->get();
    }
}