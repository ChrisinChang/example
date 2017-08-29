<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'link_name' => 'test',
                'link_title' => '測試google',
                'link_url' => 'http://google.com',
                'link_order' => 1,
            ],
            [
                'link_name' => 'test',
                'link_title' => '測試yahoo',
                'link_url' => 'http://tw.yahoo.com',
                'link_order' => 2,
            ]
        ];
        DB::table('links')->insert($data);
    }
}
