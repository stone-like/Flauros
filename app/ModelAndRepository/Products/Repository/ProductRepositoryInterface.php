<?php

namespace App\ModelAndRepository\Products\Repository;

use App\ModelAndRepository\Products\Product;
use Illuminate\Database\Eloquent\Collection;



interface ProductRepositoryInterface{
    public function detachCategories(int $id);
    public function deleteProduct(int $id):bool;
    public function updateProduct(int $id,array $params):Product;
    public function createProduct(array $params):Product;
    public function findProductById(int $id):Product;
    public function saveImages(int $id,array $images);
    public function getImages(int $id):Collection;
}