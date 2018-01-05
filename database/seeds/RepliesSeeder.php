<?php

use Illuminate\Database\Seeder;
use Faker\Generator;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class RepliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取faker实例
        $faker = app(Generator::class);


        $user_ids = User::all()->pluck('id')->toArray();

        $topic_ids = Topic::all()->pluck('id')->toArray();

        // 生成数据集合
        $replies = factory(Reply::class)
                            ->times(100)
                            ->make()->each(function ($reply) use ($faker,$user_ids,$topic_ids)
        {
            $reply->user_id = $faker->randomElement($user_ids);

            $reply->topic_id = $faker->randomElement($topic_ids);
        });


        // 将数据集合转换为数组，并插入到数据库中
        DB::table('replies')->insert($replies->toArray());
    }
}
