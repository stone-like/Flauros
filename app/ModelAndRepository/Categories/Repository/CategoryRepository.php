<?php

namespace App\ModelAndRepository\Categories\Repository;

use App\Exceptions\CategoryNotFoundException;
use Illuminate\Support\Collection;
use App\ModelAndRepository\Categories\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryRepository implements CategoryRepositoryInterface{
   
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

    public function updateCategory(int $id,array $params):Category{
        $category = $this->findCategoryById($id);
        //validationはrequestで完了している
       
        //ここで結果がおかしくなったのはfreshしないから、子のupdateに乗じて親も更新されるので親自体を陽にupdateしなくてもfreshをしないとだめ

        $category->update($params);   
      

        if(isset($params["parent_id"])){
            $parent = $this->findCategoryById($params["parent_id"]);
           
            
            $category->parent()->associate($parent);
            
        }
        $category->save();
       

        return $category;
    }

    public function deleteCategory(int $id):bool{
        $category = $this->findCategoryById($id);
        return $category->delete();
    }

    public function getProducts(int $id):Collection{
        $category = $this->findCategoryById($id);
        return $category->products;
    }

    public function syncProducts(int $id,array $params){
        $category = $this->findCategoryById($id);
       $category->products()->sync($params);
    }
    public function attachProducts(int $id,array $params){
        //rootじゃなければ祖先までどんどん登録していく
        $category = $this->findCategoryById($id);
        if  ($category->isRoot()){ 
           return;
        }
        
        $category->products()->attach($params);
        //祖先にも入れていく
        foreach($category->ancestors as $category){
            $this->attachProducts($category->id,$params);
        }
        
    }

    public function detachProducts(int $id,array $params){
        $category = $this->findCategoryById($id);
        if (count($params) == 0){
            $category->products()->detach();
        }else{
            $category->products()->detach($params);
        }
        
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