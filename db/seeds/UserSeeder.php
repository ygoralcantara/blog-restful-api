<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = Faker\Factory::create('pt_BR');

        $data = [];

        for ($i=0; $i < 10; $i++) { 

            do {
                $username = $faker->userName;

                $check = $this->fetchRow("SELECT * FROM users WHERE username = '${username}'");

            } while (!empty($check));

            $data[] = [
                'username'  => $username,
                'name'      => $faker->name,
                'email'     => $faker->email,
                'password'  => hash('sha256', $faker->password(5, 30)),
            ];
        }

        $this->insert('users', $data);
    }
}