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
                'name'         => 'STS Confirmation',
                'icon'         => 'fa fa-circle-o',
                'path'         => 'store_transfer_confirmation',
                'table_name'   => 'pos_pull_headers',
                'controller'   => 'AdminStoreTransferConfirmationController',
                'is_protected' => 0,
                'is_active'    => 0
            ]
        ];

        foreach ($modules as $module) {
            DB::table('cms_moduls')->updateOrInsert(['name' => $module['name']], $module);
        }
    }
}
