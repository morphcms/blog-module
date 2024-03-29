<?php

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class BlogDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->callOnce(RolesAndPermissionsSeeder::class);

        if (app()->environment('local')) {
            $this->call(SampleDataTableSeeder::class);
        }
    }
}
