<?php

namespace Tests;

use Spatie\Permission\Models\Role;
use App\ModelAndRepository\Users\User;
use Spatie\Permission\Models\Permission;
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

        $user = $user ?: factory(User::class)->create();

        $this->actingAs($user);

        return $user;
    }
}
