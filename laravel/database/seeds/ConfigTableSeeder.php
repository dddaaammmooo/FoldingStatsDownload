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
            [
                'token' => 'download.url',
                'value' => 'http://fah-web.stanford.edu/daily_user_summary.txt.bz2',
            ],
            [
                'token' => 'download.timeout',
                'value' => '300',
            ],
            [
                'token' => 'storage.path',
                'value' => 'storage',
            ],
        ];

        DB::table(Config::getTableName())->insert($config);
    }
}
