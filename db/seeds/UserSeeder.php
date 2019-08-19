<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = Faker\Factory::create('pt_BR');

        $data = [];

        for ($i=0; $i < 10; $i++) { 
            $data[] = [
                'username'  => $faker->userName,
                'name'      => $faker->name,
                'email'     => $faker->email,
                'password'  => hash('sha256', $faker->password(5, 30)),
            ];
        }

        $this->insert('users', $data);
    }
}