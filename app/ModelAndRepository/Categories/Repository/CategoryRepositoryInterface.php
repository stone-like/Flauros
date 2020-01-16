<?php

namespace App\ModelAndRepository\Categories\Repository;

use Illuminate\Support\Collection;
use App\ModelAndRepository\Categories\Category;

interface CategoryRepositoryInterface 
{
    public function getRootCategory():Collection;
    public function createCategory(array $params):Category;
    public function updateCategory(array $params):Category;
    public function deleteCategory():bool;
    public function findCategoryById(int $id):Category;
    // public function findCategoryBySlug(string $slug):Category;
    // public function attachProduct();
    // public function detachProduct();
}