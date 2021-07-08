<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $role = Role::create(['name' => 'Administrator', 'guard_name' => 'admin']);
        $admin = Admin::create([
            'role_id' => $role->id,
            'adminable_id' => 1,
            'adminable_type' => 'App\Company',
            'username' => 'admin',
            'email' => Str::random(10) . '@gmail.com',
            'password' => Hash::make('123456'),
            'type' => 'transfer_company',
            'status' => 1]);
        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $admin->assignRole([$role->id]);
    }
}
