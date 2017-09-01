<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();

        foreach (['校長室','教務處','學務處','總務處','輔導室','會計室','人事室'] as $name) {
            Category::create([
                'name' => $name,
            ]);
        }
    }
}
