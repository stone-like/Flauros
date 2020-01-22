<?php

namespace App\ModelAndRepository\Products\Repository;

use App\Exceptions\ProductNotFoundException;
use App\ModelAndRepository\Products\Product;
use Illuminate\Database\Eloquent\Collection;
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
    //これとは別に検索用のserachこれは完全一致でいい
    public function findProductBySlug(string $slug):Product{
        try {
            return Product::where("slug",$slug)->firstOrFail();
        }catch(ModelNotFoundException $e){
            throw new ProductNotFoundException($e->getMessage());
        }
    }

    public function updateProduct(int $id,array $params):Product{
        $product = $this->findProductById($id);
        unset($params["category_id"]);
        $product->update($params);
        
        return $product;
    }

    public function deleteProduct(int $id):bool{
        $product = $this->findProductById($id);
        return $product->delete();
    }

    public function detachCategories(int $id){
        $product = $this->findProductById($id);

        //基本的に一括でカテゴリーから削除なので引数は必要なさそう
        $product->categories()->detach();
    }

    public function saveImages(int $id,array $images){
        //storageへはここでやるかやらないか・・・？,まぁexternalへの入出力だからいいのか？
        $product = $this->findProductById($id);

        foreach($images as $img){
          
           $image = $img->store("products",["disk" => "public"]);
           $productImage = ["product_id" => $id,"image" => $image];
           $product->productimages()->create($productImage);
        }
    }

    public function getImages(int $id):Collection{
        $product = $this->findProductById($id);
        return $product->productimages;
    }
 }