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
            ['name' => 'Admin.Dashboard', 'guard_name' => 'api'],
            ['name' => 'User.Update', 'guard_name' => 'api'],
            ['name' => 'Customer.Edit', 'guard_name' => 'api'],
            ['name' => 'Customer.Delete', 'guard_name' => 'api'],
            ['name' => 'Customer.List', 'guard_name' => 'api'],
            ['name' => 'Customer.Show', 'guard_name' => 'api'],
            ['name' => 'User.BlockOrActive', 'guard_name' => 'api'],
            ['name' => 'User.Operation', 'guard_name' => 'api'],
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
            ['name' => 'UploadImage', 'guard_name' => 'api'],
            ['name' => 'UploadMainImage', 'guard_name' => 'api'],
            ['name' => 'UploadVideo', 'guard_name' => 'api'],
            ['name' => 'Payment.List', 'guard_name' => 'api'],
            ['name' => 'Payment.Filter', 'guard_name' => 'api'],
            ['name' => 'Card.UserTickets', 'guard_name' => 'api'],
            ['name' => 'Card.AllTickets', 'guard_name' => 'api'],
            ['name' => 'Card.DownloadPdf', 'guard_name' => 'api'],
            ['name' => 'Card.FilterCard', 'guard_name' => 'api'],
            ['name' => 'Article.Create', 'guard_name' => 'api'],
            ['name' => 'Article.Show', 'guard_name' => 'api'],
            ['name' => 'Article.List', 'guard_name' => 'api'],
            ['name' => 'Article.Edit', 'guard_name' => 'api'],
            ['name' => 'Article.Delete', 'guard_name' => 'api'],
            ['name' => 'Comment.Create', 'guard_name' => 'api'],
            ['name' => 'Comment.Activate', 'guard_name' => 'api'],
            ['name' => 'Comment.List', 'guard_name' => 'api'],
            ['name' => 'Comment.Delete', 'guard_name' => 'api'],
            ['name' => 'Comment.Show', 'guard_name' => 'api'],
            ['name' => 'Comment.Answer', 'guard_name' => 'api'],
            ['name' => 'Contact.Edit', 'guard_name' => 'api'],
            ['name' => 'Contact.List', 'guard_name' => 'api'],
            ['name' => 'Contact.Delete', 'guard_name' => 'api'],
            ['name' => 'Contact.Show', 'guard_name' => 'api'],
            ['name' => 'Contact.Answer', 'guard_name' => 'api'],
            ['name' => 'Question.Create', 'guard_name' => 'api'],
            ['name' => 'Question.Edit', 'guard_name' => 'api'],
            ['name' => 'Question.List', 'guard_name' => 'api'],
            ['name' => 'Question.Delete', 'guard_name' => 'api'],
            ['name' => 'Question.Show', 'guard_name' => 'api'],

        ];

        $CustomerPermissions = [
            'User.Update',
            'User.Operation',
            'GetInformation',
            'Product.List',
            'Product.Show',
            'Order.Create',
            'Order.Delete',
            'Order.Edit',
            'Order.Show',
            'Order.List',
            'Article.Show',
            'Article.List',
            'Card.UserTickets',
            'Question.Show',
            'Question.List',
            'Comment.Create',
            'Comment.Show',
        ];


        Permission::insert($AdminPermissions);

        $Admin->syncPermissions(collect($AdminPermissions)->map(function (array $arr) {
            return $arr['name'];
        })->all());

        $Customer->syncPermissions($CustomerPermissions);

    }
}
