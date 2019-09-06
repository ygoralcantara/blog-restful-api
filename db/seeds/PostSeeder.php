<?php

use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = Faker\Factory::create('pt_BR');

        /** CREATE FAKE USERS */
        $users = [];

        for ($i=0; $i < 5; $i++) { 
            $users[] = [
                'username'  => $faker->userName,
                'name'      => $faker->name,
                'email'     => $faker->email,
                'password'  => hash('sha256', $faker->password(5, 30))
            ];
        }

        $this->insert('users', $users);

        /** CREATE FAKE POSTS */
        $data = [];

        for ($i=0; $i < 10; $i++) {
            $status = $faker->boolean(70);

            $data[] = [
                'title'         => $faker->sentence(5, true),
                'content'       => $faker->realText($faker->numberBetween(50, 200)),
                'status'        => $status,
                'created_at'    => $faker->date("Y-m-d H:i:s", "now"),
                'published_at'  => ($status ? $faker->date("Y-m-d H:i:s", "now") : null),
                'username'      => $users[0]['username']
            ];
        }
        
        $this->insert('posts', $data);

        /** CREATE FAKES LIKES AND DISLIKES */
        $posts = $this->fetchAll("SELECT * FROM posts");

        $data = [];

        foreach ($users as $user) {
            foreach ($posts as $post) {
                
                $data[] = [
                    'post_id'       => $post['id'],
                    'user_username' => $user['username'],
                    'is_like'       => $faker->boolean(60)
                ];

            }
        }
        
        $this->insert('posts_like', $data);

        /** CREATE FAKES TAGS AND ATTACH TO POSTS */
        $data = [];

        for ($i=0; $i < 10; $i++) { 
            $data[] = [
                'name'  => $faker->word
            ];
        }

        $this->insert('tags', $data);

        $tags = $this->fetchAll("SELECT * FROM tags");

        $data = [];

        foreach ($posts as $post) {
            foreach ($tags as $tag) {
                
                $prob = $faker->boolean(70);

                if ($prob) {
                    $data[] = [
                        'post_id' => $post['id'],
                        'tag_id'  => $tag['id']
                    ];
                }
            }
        }

        $this->insert('posts_tags', $data);
    }
}
