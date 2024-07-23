<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
         DB::table('posts')->insert([
                'user_id'=>1,
                'title' => 'title',
                'comment' => 'comment',
                'image_url'=>' ',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
               
         ]);
    }
}
