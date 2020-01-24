<?php

namespace App\Providers;

use App\Buyer;
use App\Policies\BuyerPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SellerPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use App\Product;
use App\Seller;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Buyer::class => BuyerPolicy::class,
        Product::class => ProductPolicy::class,
        Seller::class => SellerPolicy::class,
        Transaction::class => TransactionPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-action', function ($user) {
            return $user->isAdmin();
        });

        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        // Passport::enableImplicitGrant();

        Passport::tokensCan([
            'purchase-product' => 'Create a new transaction for a specific product',
            'manage-products' =>  'Create, reade, update, and delete products (CRUD)',
            'manage-account' => 'Read your account data, id, name, email, if verified, and if admin (cannot read password). Modify your account data (email, and password). Cannot delete your account',
            'read-general' => 'Read general information like purchasing categories, purchased products, selling products, selling categories, your transactions (purchases and sales)',
        ]);
    }
}
