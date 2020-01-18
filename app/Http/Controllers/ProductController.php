<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ModelAndRepository\Products\Requests\CreateProductRequest;
use App\ModelAndRepository\Products\Requests\UpdateProductRequest;
use App\ModelAndRepository\Products\Product;
use App\ModelAndRepository\Categories\Repository\CategoryRepository;
use App\ModelAndRepository\Products\Repository\ProductRepositoryInterface;
use App\ModelAndRepository\Categories\Repository\CategoryRepositoryInterface;

class ProductController extends Controller
{
    private $proRepo;
    private $cateRepo;

    public function __construct(ProductRepositoryInterface $productRepository,CategoryRepositoryInterface $categoryRepository){
        $this->proRepo = $productRepository;
        $this->cateRepo = $categoryRepository;

    }
     
    public function createProduct(CreateProductRequest $request):Product{
       //このparamsからはimagesが取り除かれている
       $params = Product::makeRequest($request,"products");
       
       $product =$this->proRepo->createProduct($params);
       //画像をupload,productには直接入らないのでimages抜きでcreateしても問題ない
       if($request->has("images")){
        $this->proRepo->saveImages($product->id,$request->images);
       }
        //ここでcategoryをattach
        $this->cateRepo->attachProducts($params["category_id"],[$product->id]);
        return $product;
    }
    public function updateProduct(int $id,UpdateProductRequest $request):Product{
        $params = Product::makeRequest($request,"products");
        
        $product =$this->proRepo->updateProduct($id,$params);
        if($request->has("images")){
            $this->proRepo->saveImages($product->id,$request->images);
           }
        //updateの場合はattach前に一旦detachしないと前のcategoryに属したまま,この時にdeleteじゃなくdetach使う
        $this->proRepo->detachCategories($product->id);
        $this->cateRepo->attachProducts($params["category_id"],[$product->id]);
        return $product;
    }
    public function deleteProduct(int $id):bool{
        return $this->proRepo->deleteProduct($id);
    }
}
