<?php

namespace App\ModelAndRepository\Categories\Repository;

use Illuminate\Support\Collection;
use App\ModelAndRepository\Categories\Category;

interface CategoryRepositoryInterface 
{
    public function getRootCategory():Collection;
    public function createCategory(array $params):Category;
    public function updateCategory(int $id,array $params):Category;
    public function deleteCategory(int $id):bool;
    public function findCategoryById(int $id):Category;
    // public function findCategoryBySlug(string $slug):Category;
    public function attachProducts(int $id,array $params);
    public function detachProducts(int $id,array $params);
    public function syncProducts(int $id,array $params);
    public function getProducts(int $id):Collection;


}