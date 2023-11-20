<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Admin = Role::create(['name' => 'Admin']);
        $Customer = Role::create(['name' => 'Customer']);

        $AdminPermissions = [
            ['name' => 'Admin.Edit', 'guard_name' => 'api'],
            ['name' => 'Customer.Add', 'guard_name' => 'api'],
            ['name' => 'Customer.Edit', 'guard_name' => 'api'],
            ['name' => 'Customer.Delete', 'guard_name' => 'api'],
            ['name' => 'Customer.List', 'guard_name' => 'api'],
            ['name' => 'Order.Add', 'guard_name' => 'api'],
            ['name' => 'Order.Edit', 'guard_name' => 'api'],
            ['name' => 'Order.Delete', 'guard_name' => 'api'],
            ['name' => 'Order.List', 'guard_name' => 'api'],
            ['name' => 'Product.Add', 'guard_name' => 'api'],
            ['name' => 'Product.Edit', 'guard_name' => 'api'],
            ['name' => 'Product.Delete', 'guard_name' => 'api'],
            ['name' => 'Product.List', 'guard_name' => 'api'],
            ['name' => 'Factor.Add', 'guard_name' => 'api'],
            ['name' => 'Factor.Edit', 'guard_name' => 'api'],
            ['name' => 'Factor.Delete', 'guard_name' => 'api'],
            ['name' => 'Factor.List', 'guard_name' => 'api'],
            ['name' => 'Role.Add', 'guard_name' => 'api'],
            ['name' => 'Role.Edit', 'guard_name' => 'api'],
            ['name' => 'Role.Delete', 'guard_name' => 'api'],
            ['name' => 'Role.List', 'guard_name' => 'api'],
            ['name' => 'FinancialReport.show', 'guard_name' => 'api'],
            ['name' => 'FinancialReport.Filter', 'guard_name' => 'api'],
            ['name' => 'Article.Add', 'guard_name' => 'api'],
            ['name' => 'Article.Edit', 'guard_name' => 'api'],
            ['name' => 'Article.Delete', 'guard_name' => 'api'],
            ['name' => 'Article.List', 'guard_name' => 'api'],
            ['name' => 'Opinion.Add', 'guard_name' => 'api'],
            ['name' => 'Opinion.Delete', 'guard_name' => 'api'],
            ['name' => 'Opinion.List', 'guard_name' => 'api'],
            ['name' => 'Question.Add', 'guard_name' => 'api'],
            ['name' => 'Question.Edit', 'guard_name' => 'api'],
            ['name' => 'Question.Delete', 'guard_name' => 'api'],
            ['name' => 'Question.List', 'guard_name' => 'api'],
        ];


        $CustomerPermissions = [
            'Customer.Edit',
            'Customer.Delete',
            'Order.Add',
            'Order.Edit',
            'Order.Delete',
            'Factor.Add',
            'Factor.Edit',
            'Factor.Delete',
            'Opinion.Add',
            'Question.Add',
            'Article.List',
        ];


        Permission::insert($AdminPermissions);

        $Admin->syncPermissions(collect($AdminPermissions)->map(function (array $arr) {
            return $arr['name'];
        })->all());

        $Customer->syncPermissions($CustomerPermissions);

    }
}
