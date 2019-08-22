<?php

namespace Tests\Domain\Post;

use App\Domain\Post\Post;
use Tests\TestCase;

class PostTest extends TestCase {

    /**
     * Test Post Json Serialize
     *
     * @return void
     */
    public function testPostJsonSerialize() : void
    {
        $faker = \Faker\Factory::create('pt_BR');

        $status = $faker->boolean(70);

        $data = [
            'post_id'       => $faker->randomDigit,
            'title'         => $faker->sentence(5, true),
            'content'       => $faker->realText($faker->numberBetween(50, 200)),
            'status'        => $status,
            'likes'         => $faker->randomNumber(2, true),
            'dislikes'      => $faker->randomNumber(1, true),
            'created_at'    => $faker->date("Y-m-d H:i:s", "now"),
            'published_at'  => ($status ? $faker->date("Y-m-d H:i:s", "now") : null),
            'username'      => $faker->userName
        ];

        $post = new Post(
            $data['post_id'],
            $data['username'],
            $data['title'],
            $data['content'],
            $data['status'],
            $data['likes'],
            $data['dislikes'],
            $data['created_at'],
            $data['published_at']
        );

        $data['status'] = ($status ? 'published' : 'in revision');

        $expectedPayload = json_encode($data);

        $this->assertNotEmpty($post);
        $this->assertEquals($expectedPayload, json_encode($post));
    }

}

?>