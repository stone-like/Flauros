<?php

namespace App\ModelAndRepository\Products\Repository;

use App\ModelAndRepository\Products\Product;



interface ProductRepositoryInterface{
    public function deleteProduct(int $id);
    public function detachCategories(int $id);
    public function createProduct(array $params):Product;
    public function findProductById(int $id):Product;
}