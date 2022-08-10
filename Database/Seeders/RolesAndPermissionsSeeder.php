<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Acl\Utils\AclSeederHelper;
use Modules\Blog\Enums\PostPermission;

class RolesAndPermissionsSeeder extends Seeder
{
    use AclSeederHelper;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Model::unguard();

        $this->acl(module: 'blog')
            ->attachEnum(PostPermission::class, PostPermission::All->value)
            ->create(moduleRoles: 'blogger');
    }
}
