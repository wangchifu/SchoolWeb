<?php

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::truncate();

        $faker = \Faker\Factory::create('zh_TW');
        $total = 20;
        foreach (range(1, $total) as $id) {

            $filename = $faker->image(storage_path('app/public/posts'), 640, 480, 'cats', false);

            Post::create([
                'title' => $faker->realText(rand(12, 30)),
                'content' => $faker->realText(rand(500, 1000)),
                'file' => 'posts/'.$filename,
                'page_view' => rand(0, 75),
                'category_id' => rand(1, 2),
                'user_id' => rand(1, 2),
                'created_at' => Carbon::now()->subDays($total - $id),
                'updated_at' => Carbon::now()->subDays($total - $id)->addHours(rand(0, 12))->addMinutes(rand(0, 30)),
            ]);
        }
    }
}
