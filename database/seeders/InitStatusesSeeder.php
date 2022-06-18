<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'id' => 1,
                'name' => 'A FAIRE'
            ],
            [
                'id' => 2,
                'name' => 'EN COURS'
            ],
            [
                'id' => 3,
                'name' => 'FINI'
            ],
        ];


        foreach ($statuses as $status) {
            DB::table('statuses')->updateOrInsert(
                ['id' => $status['id']],
                $status
            );
        }
    }
}
