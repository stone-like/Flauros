<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\ModelAndRepository\Products\Repository\ProductRepository;
use App\ModelAndRepository\Addresses\Repository\AddressRepository;
use App\ModelAndRepository\ShoppingCarts\Repository\CartRepository;
use App\ModelAndRepository\Categories\Repository\CategoryRepository;
use App\ModelAndRepository\Products\Repository\ProductRepositoryInterface;
use App\ModelAndRepository\Addresses\Repository\AddressRepositoryInterface;
use App\ModelAndRepository\ShoppingCarts\Repository\CartRepositoryInterface;
use App\ModelAndRepository\Categories\Repository\CategoryRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
        $this->app->bind(
            AddressRepositoryInterface::class,
            AddressRepository::class
        );
        $this->app->bind(
            CartRepositoryInterface::class,
            CartRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
