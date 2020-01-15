<?php


use Illuminate\Database\Seeder;
use App\ModelAndRepository\Categories\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class)->create();

    }
}
