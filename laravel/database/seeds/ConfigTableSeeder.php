<?php

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Seed the `config` database table with system default values
     *
     * @return void
     */
    public function run()
    {
        $config = [
            [
                'token' => 'logging.filename',
                'value' => 'storage/logs/foldingcoin.log',
            ],
        ];

        DB::table(Config::getTableName())->insert($config);
    }
}
