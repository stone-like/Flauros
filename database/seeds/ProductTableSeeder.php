<?php


use Illuminate\Database\Seeder;
use App\ModelAndRepository\Products\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Product::class)->create();
    }
}
