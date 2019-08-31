<?php 

namespace App\Infrastructure\Persistence\PDO;

use Tests\TestCase;
use App\Domain\Post\Post;
use App\Domain\User\User;
use App\Domain\Post\PostRepository;
use App\Domain\User\UserRepository;

class PDOPostRepositoryTest extends TestCase {

    /**
     * PDO Post Repository
     *
     * @var PostRepository
     */
    private $postRepository;

    /**
     * PDO User Repository
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Set up dependencies before run tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $container = $this->getAppInstance()->getContainer();

        $this->postRepository = new PDOPostRepository($container);
        $this->userRepository = new PDOUserRepository($container);
    }

    /**
     * Test find all posts from database
     *
     * @return void
     */
    public function testFindAllPosts() : void
    {
        $posts = $this->postRepository->findAll();

        $this->assertNotEmpty($posts);
    }

    /**
     * Test find a post by ID field
     *
     * @return void
     */
    public function testFindPostById() : void
    {
        $post = $this->postRepository->findAll()[0];

        $newPost = $this->postRepository->findById($post->getId());

        $emptyPost = $this->postRepository->findById(0);

        $this->assertNotEmpty($newPost);
        $this->assertEquals($post->getTitle(), $newPost->getTitle());
        $this->assertEquals($post->getLikes(), $newPost->getLikes());
        $this->assertEmpty($emptyPost);
    }

    /**
     * Test persist a Post to Database
     *
     * @return void
     */
    public function testSavePost() : void
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

        /** CREATE POST */
        $newPost = new Post(
            $newUser->getUsername(),
            $faker->sentence(5, true),
            $faker->realText($faker->numberBetween(50, 200)),
            $faker->date("Y-m-d H:i:s", "now")
        );
        
        $this->postRepository->save($newPost);

        $this->assertNotEmpty($newPost);
        $this->assertNotEquals(0, $newPost->getId());

        $post = $this->postRepository->findById($newPost->getId());
        
        $this->assertNotEmpty($post);
        $this->assertEquals($newPost->getTitle(), $post->getTitle());
        $this->assertEquals($newPost->getContent(), $post->getContent());

        /** UPDATE POST */
        $title = $post->getTitle();
        $content = $post->getContent();

        $post->setTitle($faker->sentence(5, true));
        $post->setContent($faker->realText($faker->numberBetween(50, 200)));

        $post = $this->postRepository->save($post);

        $this->assertNotEmpty($post);

        $editPost = $this->postRepository->findById($post->getId());

        $this->assertNotEmpty($editPost);
        $this->assertEquals($post->getTitle(), $editPost->getTitle());
        $this->assertEquals($post->getContent(), $editPost->getContent());
        $this->assertNotEquals($title, $editPost->getTitle());
        $this->assertNotEquals($content, $editPost->getContent());
    }

    /**
     * Test remove a post from database
     *
     * @return void
     */
    public function testRemovePost() : void
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

        /** CREATE FAKE POST */
        $newPost = new Post(
            $newUser->getUsername(),
            $faker->sentence(5, true),
            $faker->realText($faker->numberBetween(50, 200)),
            $faker->date("Y-m-d H:i:s", "now")
        );
        
        $newPost = $this->postRepository->save($newPost);

        $this->assertNotEmpty($newPost);
        $this->assertNotEquals(0, $newPost->getId());

        /** ENSURE USER BELONGS TO DATABASE */
        $post = $this->postRepository->findById($newPost->getId());

        $this->assertNotEmpty($post);
        $this->assertEquals($newPost->getTitle(), $post->getTitle());

        /** REMOVE POST FROM DATABASE */
        $this->postRepository->remove($post->getId());

        $emptyPost = $this->postRepository->findById($newPost->getId());

        $this->assertEmpty($emptyPost);
    }
}

?>