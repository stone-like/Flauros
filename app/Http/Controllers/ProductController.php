<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateProductRequest;
use App\ModelAndRepository\Products\Product;
use App\ModelAndRepository\Categories\Repository\CategoryRepository;
use App\ModelAndRepository\Products\Repository\ProductRepositoryInterface;
use App\ModelAndRepository\Categories\Repository\CategoryRepositoryInterface;

class ProductController extends Controller
{
    private $productRepo;
    private $cateRepo;

    public function __construct(ProductRepositoryInterface $productRepository,CategoryRepositoryInterface $categoryRepository){
        $this->productRepo = $productRepository;
        $this->cateRepo = $categoryRepository;

    }
     
    public function createProduct(CreateProductRequest $request):Product{
        
       $params = Product::makeRequest($request,"products");
       
       $product =$this->productRepo->createProduct($params);
        //ここでcategoryをattach
        $this->cateRepo->attachProducts($params["category_id"],[$product->id]);
        return $product;
    }
    public function updateProduct():Product{
        
    }
    public function deleteProduct():bool{
        
    }
}
