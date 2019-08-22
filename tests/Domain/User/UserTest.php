<?php

namespace Tests\Domain;

use App\Domain\User\User;
use Tests\TestCase;

class UserTest extends TestCase {

    /**
     * Test Json Serialize
     *
     * @return void
     */
    public function testUserJsonSerialize() : void
    {
        $faker = \Faker\Factory::create('pt_BR');

        $data = [
            'username'  => $faker->username,
            'name'      => $faker->name,
            'email'     => $faker->email,
        ];

        $user = new User($data['username'], $data['name'], $data['email'], $faker->password(5, 30));

        $expectedPayload = json_encode($data);

        $this->assertNotEmpty($user);
        $this->assertEquals($expectedPayload, json_encode($user));
    }
}

?>