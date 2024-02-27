<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CmsModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            [
                'name'         => 'Create GIS STS',
                'icon'         => 'fa fa-file-text',
                'path'         => 'store_transfer_gis',
                'table_name'   => 'gis_pulls',
                'controller'   => 'AdminStoreTransferGisController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }
    }
}
