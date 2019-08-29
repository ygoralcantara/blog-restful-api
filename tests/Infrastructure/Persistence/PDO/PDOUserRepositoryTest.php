<?php

namespace App\Infrastructure\Persistence\PDO;

use Tests\TestCase;
use App\Domain\User\User;
use App\Domain\User\UserRepository;

class PDOUserRepositoryTest extends TestCase {

    /**
     * PDO User Repository
     *
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * Set up dependencies before run tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $container = $this->getAppInstance()->getContainer();

        $this->userRepository = new PDOUserRepository($container);
    }

    /**
     * Test find all users from database with Doctrine
     *
     * @return void
     */
    public function testFindAll() : void
    {
        $users = $this->userRepository->findAll();

        $this->assertNotEmpty($users);
    }

    /**
     * Test find a user by username field
     *
     * @return void
     */
    public function testFindByUsername() : void
    {
        $user = $this->userRepository->findAll()[0];

        $newUser = $this->userRepository->findByUsername($user->getUsername());

        $emptyUser = $this->userRepository->findByUsername('emptyuser12345');

        $this->assertNotEmpty($newUser);
        $this->assertEquals($user->getUsername(), $newUser->getUsername());
        $this->assertEmpty($emptyUser);
    }

    /**
     * Test find by criteria with sort and pagination
     *
     * @return void
     */
    public function testFindBy() : void
    {
        /** CREATE FAKERS USERS WITH SAME NAME */
        $faker = \Faker\Factory::create('pt_BR');

        $users = [];

        for ($i=0; $i < 10; $i++) { 
            $users[] = new User(
                $faker->userName,
                'Ygor',
                $faker->email,
                $faker->password(5, 30)
            );
        }

        $this->userRepository->saveAll($users);

        /** TEST FIND BY CRITERIA */
        $criteria = [
            'name' => 'Ygor',
        ];

        $order = [
            'username' => 'asc'
        ];

        $offset = 0;
        $limit = 5;

        $newUsers = $this->userRepository->findBy($criteria, $order, $limit, $offset);

        $this->assertNotEmpty($newUsers);
        $this->assertEquals(sizeof($newUsers), ($limit - $offset));
    }

    /**
     * Test count all users from database
     *
     * @return void
     */
    public function testCountAll() : void
    {
        $count = $this->userRepository->countAll();

        $users = $this->userRepository->findAll();

        $this->assertNotEmpty($count);
        $this->assertNotEmpty($users);

        $this->assertIsArray($users);
        $this->assertIsInt($count);

        $this->assertEquals(sizeof($users), $count);
    }

    /**
     * Save user to database
     *
     * @return void
     */
    public function testSave() : void
    {
        $faker = \Faker\Factory::create('pt_BR');

        /** CREATE USER */
        $newUser = new User(
            $faker->userName,
            $faker->name,
            $faker->email,
            $faker->password(5, 30)
        );
        
        $newUser = $this->userRepository->save($newUser);

        $this->assertNotEmpty($newUser);

        $user = $this->userRepository->findByUsername($newUser->getUsername());

        $this->assertEquals($newUser->getName(), $user->getName());
        $this->assertEquals($newUser->getEmail(), $user->getEmail());

        /** UPDATE USER */
        $email = $user->getEmail();

        $user->setEmail($faker->email);

        $user = $this->userRepository->save($user);

        $editUser = $this->userRepository->findByUsername($user->getUsername());

        $this->assertNotEmpty($editUser);
        $this->assertNotEquals($email, $editUser->getEmail());
        $this->assertEquals($user->getEmail(), $editUser->getEmail());
    }

        /**
     * Test save all users to database
     *
     * @return void
     */         
    public function testSaveAll() : void
    {
        /** CREATE FAKES USERS */
        $faker = \Faker\Factory::create('pt_BR');

        $users = [];

        for ($i=0; $i < 5; $i++) { 
            $users[] = new User(
                $faker->userName,
                $faker->name,
                $faker->email,
                $faker->password(5, 30)
            );
        }

        /** TEST SAVE ALL */
        $users = $this->userRepository->saveAll($users);

        $this->assertIsArray($users);
        $this->assertNotEmpty($users);

        foreach ($users as $user) {
            $searchUser = $this->userRepository->findByUsername($user->getUsername());

            $this->assertNotEmpty($searchUser);
            $this->assertEquals($user->getEmail(), $searchUser->getEmail());
            $this->assertEquals($user->getName(), $searchUser->getName());
        }
    }

        /**
     * Test remove a user from database
     *
     * @return void
     */
    public function testRemove() : void
    {
        /** CREATE FAKE USER */
        $faker = \Faker\Factory::create('pt_BR');

        $newUser = new User(
            $faker->userName,
            $faker->name,
            $faker->email,
            $faker->password(5, 30)
        );

        $newUser = $this->userRepository->save($newUser);

        $this->assertNotEmpty($newUser);

        /** ENSURE USER BELONGS TO DATABASE */
        $user = $this->userRepository->findByUsername($newUser->getUsername());

        $this->assertNotEmpty($newUser);
        $this->assertEquals($newUser->getEmail(), $user->getEmail());

        /** REMOVE USER FROM DATABASE */
        $this->userRepository->remove($user);

        $user = $this->userRepository->findByUsername($newUser->getUsername());

        $this->assertEmpty($user);
    }

    /**
     * Test remove all users from database
     *
     * @return void
     */
    public function testRemoveAll() : void
    {
        /** CREATE AND SAVE FAKES USERS */
        $faker = \Faker\Factory::create('pt_BR');

        $users = [];

        for ($i=0; $i < 5; $i++) { 
            $users[] = new User(
                $faker->userName,
                $faker->name,
                $faker->email,
                $faker->password(5, 30)
            );
        }

        $users = $this->userRepository->saveAll($users);

        $this->assertIsArray($users);
        $this->assertNotEmpty($users);

        /** REMOVE USERS AND TEST */
        $this->userRepository->removeAll($users);

        foreach ($users as $user) {
            $emptyUser = $this->userRepository->findByUsername($user->getUsername());

            $this->assertEmpty($emptyUser);
        }

    }

}

?>