<?php

namespace Database\Seeders;

use App\Enums\EnumAction;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Media;
use App\Models\Order;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Product;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'user-view', 'action' => EnumAction::VIEW->value, 'model' => User::class],
            ['name' => 'user-create', 'action' => EnumAction::CREATE->value, 'model' => User::class],
            ['name' => 'user-edit', 'action' => EnumAction::EDIT->value, 'model' => User::class],
            ['name' => 'user-delete', 'action' => EnumAction::DELETE->value, 'model' => User::class],

            ['name' => 'admin-view', 'action' => EnumAction::VIEW->value, 'model' => Admin::class],
            ['name' => 'admin-create', 'action' => EnumAction::CREATE->value, 'model' => Admin::class],
            ['name' => 'admin-edit', 'action' => EnumAction::EDIT->value, 'model' => Admin::class],
            ['name' => 'admin-delete', 'action' => EnumAction::DELETE->value, 'model' => Admin::class],

            ['name' => 'role-view', 'action' => EnumAction::VIEW->value, 'model' => Role::class],
            ['name' => 'role-create', 'action' => EnumAction::CREATE->value, 'model' => Role::class],
            ['name' => 'role-edit', 'action' => EnumAction::EDIT->value, 'model' => Role::class],
            ['name' => 'role-delete', 'action' => EnumAction::DELETE->value, 'model' => Role::class],

            ['name' => 'post-view', 'action' => EnumAction::VIEW->value, 'model' => Post::class],
            ['name' => 'post-create', 'action' => EnumAction::CREATE->value, 'model' => Post::class],
            ['name' => 'post-edit', 'action' => EnumAction::EDIT->value, 'model' => Post::class],
            ['name' => 'post-delete', 'action' => EnumAction::DELETE->value, 'model' => Post::class],

            ['name' => 'category-view', 'action' => EnumAction::VIEW->value, 'model' => Category::class],
            ['name' => 'category-create', 'action' => EnumAction::CREATE->value, 'model' => Category::class],
            ['name' => 'category-edit', 'action' => EnumAction::EDIT->value, 'model' => Category::class],
            ['name' => 'category-delete', 'action' => EnumAction::DELETE->value, 'model' => Category::class],

            ['name' => 'tag-view', 'action' => EnumAction::VIEW->value, 'model' => Tag::class],
            ['name' => 'tag-create', 'action' => EnumAction::CREATE->value, 'model' => Tag::class],
            ['name' => 'tag-edit', 'action' => EnumAction::EDIT->value, 'model' => Tag::class],
            ['name' => 'tag-delete', 'action' => EnumAction::DELETE->value, 'model' => Tag::class],

            ['name' => 'media-view', 'action' => EnumAction::VIEW->value, 'model' => Media::class],
            ['name' => 'media-create', 'action' => EnumAction::CREATE->value, 'model' => Media::class],
            ['name' => 'media-edit', 'action' => EnumAction::EDIT->value, 'model' => Media::class],
            ['name' => 'media-delete', 'action' => EnumAction::DELETE->value, 'model' => Media::class],

            ['name' => 'product-view', 'action' => EnumAction::VIEW->value, 'model' => Product::class],
            ['name' => 'product-create', 'action' => EnumAction::CREATE->value, 'model' => Product::class],
            ['name' => 'product-edit', 'action' => EnumAction::EDIT->value, 'model' => Product::class],
            ['name' => 'product-delete', 'action' => EnumAction::DELETE->value, 'model' => Product::class],

            ['name' => 'order-view', 'action' => EnumAction::VIEW->value, 'model' => Order::class],
            ['name' => 'order-create', 'action' => EnumAction::CREATE->value, 'model' => Order::class],
            ['name' => 'order-edit', 'action' => EnumAction::EDIT->value, 'model' => Order::class],
            ['name' => 'order-delete', 'action' => EnumAction::DELETE->value, 'model' => Order::class],

            ['name' => 'setting-view', 'action' => EnumAction::VIEW->value, 'model' => Setting::class],
            ['name' => 'setting-create', 'action' => EnumAction::CREATE->value, 'model' => Setting::class],
            ['name' => 'setting-edit', 'action' => EnumAction::EDIT->value, 'model' => Setting::class],
            ['name' => 'setting-delete', 'action' => EnumAction::DELETE->value, 'model' => Setting::class],
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(
                attributes: ['name' => $permission['name']],
                values: $permission
            );
        }
    }
}
