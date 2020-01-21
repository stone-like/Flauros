<?php

namespace Tests;


use Spatie\Permission\Models\Role;
use App\ModelAndRepository\Users\User;
use Spatie\Permission\Models\Permission;
use App\ModelAndRepository\Products\Product;
use App\ModelAndRepository\Countries\Country;
use App\ModelAndRepository\Categories\Category;
use App\ModelAndRepository\Prefectures\Prefecture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\ModelAndRepository\Products\Repository\ProductRepository;
use App\ModelAndRepository\Addresses\Repository\AddressRepository;
use App\ModelAndRepository\ShoppingCarts\Repository\CartRepository;
use App\ModelAndRepository\Categories\Repository\CategoryRepository;
use App\ModelAndRepository\ProductImages\Repository\ProductImageRepository;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $category;//repositoryでnewするするときとかにdummy的に使う
    protected $product;

    public function setUp():void{
        parent::setUp();

        $this->category = factory(Category::class)->create();
        $this->product = factory(Product::class)->create();
        factory(Country::class,4)->create()->each(function($country){
            return factory(Prefecture::class,3)->create([
                "country_id" => $country->id
            ]);
         });
    
        $this->cateRepo = new CategoryRepository();
        $this->proRepo = new ProductRepository();
        $this->addressRepo = new AddressRepository();
        $this->cartRepo = new CartRepository();
        
        //roleとpermissionの設定
        $roles = [
            "admin",
            "staff",
            "user"
        ];
        foreach($roles as $role){
            Role::create(["name" => $role]);
        }

        $permissions = [
            "changeProduct",
            "changeUserAccount"
        ];
        foreach ($permissions as $permission) {
            Permission::create(["name" => $permission]);
        }

        $permissions = [
            "changeProduct",
            "changeUserAccount"
        ];
        $role = Role::findByName("admin");
        $role->givePermissionTo($permissions);
        $permissions = [
            "changeProduct"
        ];
        $role = Role::findByName("staff");
        $role->givePermissionTo($permissions);
        //設定終わり
    }

    protected function signIn($user = null)
    {
        //signInの役割はなにがしかのユーザでsignInすること、
        //もしthreadがあってそのsignInしたユーザーでthreadを作りたいならsignInの返り値、もしくはsignInの中に入れたuserを使えばいい
       
        //一般ユーザーのroleはユーザー
        $user = $user ?: factory(User::class)->create()->assignRole("user");

        $this->actingAs($user);

        return $user;
    }
}
