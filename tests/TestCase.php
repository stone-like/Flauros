<?php

namespace Tests;

use App\ModelAndRepository\Categories\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $category;//repositoryでnewするするときとかにdummy的に使う

    public function setUp():void{
        parent::setUp();

        $this->category = factory(Category::class)->create();
    }
}
