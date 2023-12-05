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
            ['name' => 'Role.Create', 'guard_name' => 'api'],
            ['name' => 'Role.List', 'guard_name' => 'api'],
            ['name' => 'Role.Edit', 'guard_name' => 'api'],
            ['name' => 'Role.Delete', 'guard_name' => 'api'],
            ['name' => 'GetInformation', 'guard_name' => 'api'],
            ['name' => 'Admin.UpdatePassword', 'guard_name' => 'api'],
            ['name' => 'Dashboard', 'guard_name' => 'api'],
            ['name' => 'Customer.Edit', 'guard_name' => 'api'],
            ['name' => 'Customer.Update', 'guard_name' => 'api'],
            ['name' => 'Customer.Delete', 'guard_name' => 'api'],
            ['name' => 'Customer.List', 'guard_name' => 'api'],
            ['name' => 'User.BlockOrActive', 'guard_name' => 'api'],
            ['name' => 'User.operation', 'guard_name' => 'api'],
            ['name' => 'Product.Create', 'guard_name' => 'api'],
            ['name' => 'Product.Edit', 'guard_name' => 'api'],
            ['name' => 'Product.Show', 'guard_name' => 'api'],
            ['name' => 'Product.List', 'guard_name' => 'api'],
            ['name' => 'Product.Delete', 'guard_name' => 'api'],
            ['name' => 'Order.Create', 'guard_name' => 'api'],
            ['name' => 'Order.Edit', 'guard_name' => 'api'],
            ['name' => 'Order.Show', 'guard_name' => 'api'],
            ['name' => 'Order.List', 'guard_name' => 'api'],
            ['name' => 'Order.Delete', 'guard_name' => 'api'],
            ['name' => 'Product.UploadImage', 'guard_name' => 'api'],
            ['name' => 'Product.UploadMainImage', 'guard_name' => 'api'],
            ['name' => 'Product.UploadVideo', 'guard_name' => 'api'],
            ['name' => 'PaymentList', 'guard_name' => 'api'],
            ['name' => 'PaymentFilter', 'guard_name' => 'api'],
            ['name' => 'Card.UserTickets', 'guard_name' => 'api'],
            ['name' => 'Card.AllTickets', 'guard_name' => 'api'],
            ['name' => 'Card.DownloadPdf', 'guard_name' => 'api'],
            ['name' => 'Card.FilterCard', 'guard_name' => 'api'],
            ['name' => 'Article.Create', 'guard_name' => 'api'],
            ['name' => 'Article.Show', 'guard_name' => 'api'],
            ['name' => 'Article.List', 'guard_name' => 'api'],
            ['name' => 'Article.Edit', 'guard_name' => 'api'],
            ['name' => 'Article.Delete', 'guard_name' => 'api'],
            ['name' => 'Article.UploadImage', 'guard_name' => 'api'],
            ['name' => 'Article.UploadVideo', 'guard_name' => 'api'],
        ];

        $CustomerPermissions = [
            'GetInformation',
            'Product.List',
            'Order.Create',
            'Order.Delete',
            'Order.Edit',
            'Order.Show',
            'Order.List',
            'Product.UploadImage',
            'Product.UploadMainImage',
            'Product.UploadVideo',
            'Card.UserTickets',
        ];


        Permission::insert($AdminPermissions);

        $Admin->syncPermissions(collect($AdminPermissions)->map(function (array $arr) {
            return $arr['name'];
        })->all());

        $Customer->syncPermissions($CustomerPermissions);

    }
}
