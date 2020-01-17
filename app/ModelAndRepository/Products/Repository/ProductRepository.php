<?php

namespace App\ModelAndRepository\Products\Repository;

use App\Exceptions\ProductNotFoundException;
use App\ModelAndRepository\Products\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\ModelAndRepository\Products\Repository\ProductRepositoryInterface;

 class ProductRepository implements ProductRepositoryInterface{
   

    public function createProduct(array $params):Product{
        //ここでcategory_idは除いてあげる
        unset($params["category_id"]);
        return Product::create($params);
    }

    public function findProductById(int $id):Product{
        try {
            return Product::where("id",$id)->firstOrFail();
        }catch(ModelNotFoundException $e){
            throw new ProductNotFoundException($e->getMessage());
        }
    }

    public function deleteProduct(int $id){
        $product = $this->findProductById($id);
        return $product->delete();
    }

    public function detachCategories(int $id){
        $product = $this->findProductById($id);

        //基本的に一括でカテゴリーから削除なので引数は必要なさそう
        $product->categories()->detach();
    }
 }