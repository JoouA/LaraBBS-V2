<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 所有用户 ID 数组，如：[1,2,3,4]
        $user_ids = User::all()->pluck('id')->toArray();

        //所用category ID数组
        $category_ids = Category::all()->pluck('id')->toArray();

        // 这边实例faker是为了用randomElement
        $faker = app(\Faker\Generator::class);

        $topics = factory(Topic::class)
                        ->times(100)
                        ->make()
                        ->each(function ($topic) use ($user_ids,$category_ids,$faker)
        {
            $topic->user_id = $faker->randomElement($user_ids);
            $topic->category_id = $faker->randomElement($category_ids);

        })->toArray();

        DB::table('topics')->insert($topics);

    }
}
