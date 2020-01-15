<?php


use Illuminate\Database\Seeder;
use App\ModelAndRepository\Products\Product;
use App\ModelAndRepository\Categories\Category;

class CategoryProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class,2)->create()->each(function(Category $category){
            factory(Product::class,5)->create()->each(function(Product $product) use($category){
                   $category->products()->save($product);//中間テーブルに保存
            });
        });
    }
}
