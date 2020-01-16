<?php

namespace App\ModelAndRepository\Categories\Repository;

use App\Exceptions\CategoryNotFoundException;
use Illuminate\Support\Collection;
use App\ModelAndRepository\Categories\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryRepository implements CategoryRepositoryInterface{
    private $model;
    public function __construct(Category $category){
        $this->model = $category;
    }
    public function getRootCategory():Collection{
        //ここでEloquent使う、別にどんな手段を取ろうがcontrollerには全く関係ないのでEloquentを使ってもいい
        return Category::where("parent_id",NULL)->get();
    }

    public function createCategory(array $params):Category{
        //validationはrequestで完了している
        $category = Category::create($params);
        if(isset($params["parent_id"])){
            $parent = $this->findCategoryById($params["parent_id"]);
            $category->parent()->associate($parent);

        }
        $category->save();
        return $category;
    }

    public function updateCategory(array $params):Category{
        //validationはrequestで完了している
        $old = Category::where("id",$this->model->parent_id)->first();
       
        //ここで結果がおかしくなったのはfreshしないから、子のupdateに乗じて親も更新されるので親自体を陽にupdateしなくてもfreshをしないとだめ

        $this->model->update($params);   
      

        if(isset($params["parent_id"])){
            $parent = $this->findCategoryById($params["parent_id"]);
           
            
            $this->model->parent()->associate($parent);
            
        }
        $this->model->save();
       

        return $this->model;
    }

    public function deleteCategory():bool{

        return $this->model->delete();
    }

    public function findCategoryById(int $id):Category{
    //一応validationしてるから変なidは来ないはずだけど
    try {
        return Category::where("id",$id)->firstOrFail();
    }catch(ModelNotFoundException $e){
        throw new CategoryNotFoundException($e->getMessage());
    }
       
    }
}