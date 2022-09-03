<?php

namespace App\Providers;

use App\Repository\Interface\User\AuthRepositoryInterface;
use App\Repository\User\AuthRepository;
use App\Repository\Interface\Vendor\AuthRepositoryInterface as VendorAuthRepoInterface;
use App\Repository\Vendor\AuthRepository as VendorAuthRepo;
use App\Repository\Interface\Vendor\ProductRepositoryInterface;
use App\Repository\Vendor\ProductRepository;
use App\Repository\Interface\Vendor\VendorRepositoryInterface;
use App\Repository\Vendor\VendorRepository;
use App\Repository\Interface\User\UserRepositoryInterface;
use App\Repository\Interface\User\ProductRepositoryInterface as UserProductRepositoryInterface;
use App\Repository\User\UserRepository;
use App\Repository\User\ProductRepository as UserProductRepository;
use App\Repository\Rider\OrderRepository;
use App\Repository\Interface\Rider\OrderRepositoryInterface;
use App\Repository\Rider\AuthRepository as RiderAuthRepository;
use App\Repository\Interface\Rider\AuthRepositoryInterface as RiderAuthRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(VendorAuthRepoInterface::class, VendorAuthRepo::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserProductRepositoryInterface::class, UserProductRepository::class);
        $this->app->bind(RiderAuthRepositoryInterface::class, RiderAuthRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //


    }
}
