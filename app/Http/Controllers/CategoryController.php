<?php

namespace App\Http\Controllers;




use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\ModelAndRepository\Categories\Category;
use App\ModelAndRepository\Categories\Repository\CategoryRepositoryInterface;


class CategoryController extends Controller
{

    private $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepository){
        $this->categoryRepo = $categoryRepository;
    }
    //getRootCategory
    public function getRootCategory():Collection{
         $rootCategories = $this->categoryRepo->getRootCategory();
         return $rootCategories;
    }
    //getCategory,middleかlastか判定する,withでProductとってくればいいかな？
    public function getChildCategory(Category $category){
         //lastか判定機能をつける、last出なければそのcategoryの子供たちを返してあげる
         //repositoryパターンはさむならここでmethodInjectionのかわりにcategoryをとってきてあげなければならない

         
    }
    //productを作るときにproductとどこのcategoryに登録するかを選ぶ、productの操作は特に行わないけど、categoryは少しやることがあるのでこっちでよさそう
    //ここからはsubAdmin以上しか使えない
    //repositoryパターン使うと直接injectionでmodelを受け取れなくなり、自分でfindByIDなのでとってくる必要があるので注意

    //attachProduct
    public function attachProduct(){
        //やること　auth性テェック、pivotテーブルにproductとcategoryを登録

    }
    //detachProduct
    public function detachProduct(){

    }
    //deleteCategory(子供も消す)
    public function deleteCategory(){

    }


}
