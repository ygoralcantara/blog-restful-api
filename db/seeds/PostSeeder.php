<?php

use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = Faker\Factory::create('pt_BR');

        $user = [
            'username'  => $faker->userName,
            'name'      => $faker->name,
            'email'     => $faker->email,
            'password'  => hash('sha256', $faker->password(5, 30))
        ];

        $this->insert('users', $user);

        $data = [];

        for ($i=0; $i < 5; $i++) {
            $status = $faker->boolean(70);

            $data[] = [
                'title'         => $faker->sentence(5, true),
                'content'       => $faker->realText($faker->numberBetween(50, 200)),
                'status'        => $status,
                'likes'         => $faker->randomNumber(2, true),
                'dislikes'      => $faker->randomNumber(1, true),
                'created_at'    => $faker->date("Y-m-d H:i:s", "now"),
                'published_at'  => ($status ? $faker->date("Y-m-d H:i:s", "now") : null),
                'username'      => $user['username']
            ];
        }

        $this->insert('posts', $data);
    }
}
