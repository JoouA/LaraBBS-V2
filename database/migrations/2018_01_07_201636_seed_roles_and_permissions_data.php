<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //清除缓存
        app()['cache']->forget('spatie.permission.cache');

        //创建权限
        Permission::create(['name' => 'manage_contents']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'manage_settings']);

        //创建站长角色,并赋予权限
        $founder =  Role::create(['name' => 'Founder']);
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTo('manage_settings');

        // 创建管理员角色，并赋予权限
        $maintainer = Role::create(['name' => 'Maintainer']);
        $maintainer->givePermissionTo('manage_contents');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //清理缓存
        app()['cache']->forget('spatie.permission.cache');

        //清空所有表的数据
        $table_names = config('permission.table_names');

        Model::unguard();
        DB::table($table_names['role_has_permissions'])->delete();
        DB::table($table_names['model_has_roles'])->delete();
        DB::table($table_names['model_has_permissions'])->delete();
        DB::table($table_names['roles'])->delete();
        DB::table($table_names['permissions'])->delete();
        Model::reguard();

    }
}
