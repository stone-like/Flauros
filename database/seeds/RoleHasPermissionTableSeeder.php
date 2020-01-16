<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleHasPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            "changeProduct",
            "changeReview",
            "changeUserAccount"
        ];
        $role = Role::findByName("admin");
        $role->givePermissionTo($permissions);
        $permissions = [
            "changeProduct",
            "changeReview"
        ];
        $role = Role::findByName("staff");
        $role->givePermissionTo($permissions);
    }
}
