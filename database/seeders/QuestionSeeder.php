<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('questions')->insert([
                'user_id'=>1,
                'category_id'=>1,
                'title' => '教えてください。',
                'comment' => '命名はデータを基準に考えますか',
                'image'=>'',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
         ]);
    }
}
