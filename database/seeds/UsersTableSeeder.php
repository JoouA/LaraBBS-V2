<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取faker实例
        $faker = app(\Faker\Generator::class);

        // 头像假数据
        $avatars = [
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/s5ehp11z6s.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/Lhd1SHqu86.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/LOnMrqbHJn.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/xAuDMxteQy.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/NDnzMutoxX.png?imageView2/1/w/200/h/200',
        ];

        //生成数据集合
        $users = factory(User::class)
                    ->times(10)
                    ->make()
                    ->each(function ($user) use ($faker,$avatars)
        {
            // 从头像数组中随机取出一个并赋值
            $user->avatar = $faker->randomElement($avatars);
        });

        //让隐藏字段可见  如果不用makeVisible 就没有password和remember_token这两个数据
        $user_array = $users->makeVisible(['password','remember_token'])->toArray();

        DB::table('users')->insert($user_array);

        //设置第一个用户的数据，为了之后的数据全部回滚做准备
        $user = User::find(1);
        $user->name = 'jooua';
        $user->email = 'tangwtna@163.com';
        $user->password = bcrypt('123456');
        $user->mobile = '13858987011';
        $user->avatar = 'http://larabbs.work/uploads/images/avatars/201712/25/1_dfdf2d183dde73881dac8beccd4e4419.jpg';
        $user->save();

        //初始化角色1为站长
        $user->assignRole('Founder');


        // 角色2指定为管理员
        User::find(2)->assignRole('Maintainer');

    }
}
