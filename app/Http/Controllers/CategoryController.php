<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\ModelAndRepository\Categories\Category;
use App\ModelAndRepository\Categories\Repository\CategoryRepository;
use App\ModelAndRepository\Categories\Requests\CreateCategoryRequest;
use App\ModelAndRepository\Categories\Requests\UpdateCategoryRequest;
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
    public function getChildCategory(int $id):array{
         //一応validationかけなくてもrepositoryでexceptionが出るようになってるが
         $category = $this->categoryRepo->findCategoryById($id);
         return [
             "category" => $category,
             //直属のchildのみ
             "children" => $category->children,
             "products" => $this->categoryRepo->getProducts($id)
         ];
    }
    //productを作るときにproductとどこのcategoryに登録するかを選ぶ、productの操作は特に行わないけど、categoryは少しやることがあるのでこっちでよさそう
    //ここからはsubAdmin以上しか使えない
    //repositoryパターン使うと直接injectionでmodelを受け取れなくなり、自分でfindByIDなのでとってくる必要があるので注意

    
    public function createCategory(CreateCategoryRequest $request):Category{
        //これ加工の役割は一旦modelでやるのもありだと思うんだけど・・・どうなんだろう
        //requestの加工
        $params = Category::makeRequest($request,"categories");
       
      
      
       return $this->categoryRepo->createCategory($params);
    }
    public function updateCategory(int $id,UpdateCategoryRequest $request):Category{
         //updateするときは一旦createしたやつから変えて全てを送るはずなのでnameは絶対入っている
         $params = Category::makeRequest($request,"categories");
         return $this->categoryRepo->updateCategory($id,$params);

    }
    //deleteCategory(子供も消す)←これは自動でやってくれるのでうれしい
    public function deleteCategory(int $id):bool{
        // $this->categoryRepo->syncProducts($id,[]);//これはondeleteでやってくれる
        return $this->categoryRepo->deleteCategory($id);
    }


}
