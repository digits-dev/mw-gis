<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CmsMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::submasterMenu();
    }

    public function submasterMenu() {
        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Submaster',
            ],
            [
                'name'              => 'Submaster',
                'type'              => 'URL',
                'path'              => '#',
                'color'             => 'normal',
                'icon'              => 'fa fa-list',
                'parent_id'         => 0,
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 100
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Store',
            ],
            [
                'name'              => 'Store',
                'type'              => 'Route',
                'path'              => 'AdminCompanyStoresControllerGetIndex',
                'color'             => 'normal',
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => DB::table('cms_menus')->where('name','Submaster')->value('id'),
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 1
            ]
        );

        DB::table('cms_menus')->updateOrInsert(
            [
                'name'              => 'Store Access',
            ],
            [
                'name'              => 'Store Access',
                'type'              => 'Route',
                'path'              => 'AdminPrivilegeStoreAccessesControllerGetIndex',
                'color'             => 'normal',
                'icon'              => 'fa fa-circle-o',
                'parent_id'         => DB::table('cms_menus')->where('name','Submaster')->value('id'),
                'is_active'         => 1,
                'is_dashboard'      => 0,
                'id_cms_privileges' => 1,
                'sorting'           => 2
            ]
        );
    }
}
