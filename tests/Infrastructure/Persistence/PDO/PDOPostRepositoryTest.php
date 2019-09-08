<?php 

namespace App\Infrastructure\Persistence\PDO;

use Tests\TestCase;
use App\Domain\Tag\Tag;
use App\Domain\Post\Post;
use App\Domain\User\User;
use App\Domain\Tag\TagRepository;
use App\Domain\Post\PostRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\PDO\PDOTagRepository;
use App\Infrastructure\Persistence\PDO\PDOPostRepository;
use App\Infrastructure\Persistence\PDO\PDOUserRepository;

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
     * PDO Tag Repository
     *
     * @var TagRepository
     */
    private $tagRepository;

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
        $this->tagRepository = new PDOTagRepository($container);
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

    /**
     * Test Increase Like or Dislikes to Post
     *
     * @return void
     */
    public function testLikePost() : void
    {
        $faker = \Faker\Factory::create('pt_BR');

        /** CREATE USER */
        $user = new User(
            $faker->userName,
            $faker->name,
            $faker->email,
            $faker->password(5, 30)
        );
        
        $user = $this->userRepository->save($user);

        $this->assertNotEmpty($user);

        /** CREATE FAKE POST */
        $post = new Post(
            $user->getUsername(),
            $faker->sentence(5, true),
            $faker->realText($faker->numberBetween(50, 200)),
            $faker->date("Y-m-d H:i:s", "now")
        );
        
        $post = $this->postRepository->save($post);

        $this->assertNotEmpty($post);

        /** LIKE POST */
        $likes = $post->getLikes();
        $dislikes = $post->getDislikes();

        $likes++;

        $post = $this->postRepository->likePost($post, true, $user->getUsername());

        $countLikes = $this->postRepository->countLikes($post, true);
        $countDislikes = $this->postRepository->countLikes($post, false);

        $this->assertNotEmpty($post);
        $this->assertEquals($likes, $post->getLikes());
        $this->assertEquals($likes, $countLikes);
        $this->assertEquals($dislikes, $countDislikes);

        $newPost = $this->postRepository->findById($post->getId());

        $this->assertNotEmpty($newPost);
        $this->assertEquals($likes, $newPost->getLikes());

        /** DISLIKE POST */

        $likes--;
        $dislikes++;

        $post = $this->postRepository->likePost($post, false, $user->getUsername());

        $countLikes = $this->postRepository->countLikes($post, true);
        $countDislikes = $this->postRepository->countLikes($post, false);

        $this->assertNotEmpty($post);
        $this->assertEquals($likes, $countLikes);
        $this->assertEquals($dislikes, $countDislikes);
        $this->assertEquals($likes, $post->getLikes());
        $this->assertEquals($dislikes, $post->getDislikes());

        $newPost = $this->postRepository->findById($post->getId());

        $this->assertNotEmpty($newPost);
        $this->assertEquals($likes, $newPost->getLikes());
        $this->assertEquals($dislikes, $newPost->getDislikes());

        /** REMOVE LIKE */
        $check = $this->postRepository->checkLike($newPost->getId(), $user->getUsername());

        $this->assertIsBool($check);
        $this->assertEquals(true, $check);

        $this->postRepository->removeLike($newPost, $user->getUsername());

        $check = $this->postRepository->checkLike($newPost->getId(), $user->getUsername());

        $this->assertIsBool($check);
        $this->assertEquals(false, $check);
    }

    /**
     * Test add and remove a tag from Post
     *
     * @return void
     */
    public function testTag() : void
    {
        $faker = \Faker\Factory::create('pt_BR');

        $user = $this->userRepository->findAll()[0];

        $this->assertNotEmpty($user);

        /** CREATE FAKE POST */
        $post = new Post(
            $user->getUsername(),
            $faker->sentence(5, true),
            $faker->realText($faker->numberBetween(50, 200)),
            $faker->date("Y-m-d H:i:s", "now")
        );
        
        $post = $this->postRepository->save($post);

        $this->assertNotEmpty($post);
        $this->assertNotEquals(0, $post->getId());

        $tag = new Tag($faker->unique()->word);

        $tag = $this->tagRepository->save($tag);

        $newTag = $this->tagRepository->findById($tag->getId());

        $this->assertNotEmpty($tag);
        $this->assertNotEquals(0, $tag->getId());
        $this->assertNotEmpty($newTag);
        $this->assertEquals($tag->getId(), $newTag->getId());
        
        /** ADD TAG TO POST */
        $post = $this->postRepository->addTag($post, $tag);

        $newPost = $this->postRepository->findById($post->getId());

        $this->assertNotEmpty($newPost);
        $this->assertContains($tag->getName(), $post->getTags());
        $this->assertContains($tag->getName(), $newPost->getTags());

        /** REMOVE TAG TO POST */
        $post = $this->postRepository->removeTag($post, $tag);

        $newPost = $this->postRepository->findById($post->getId());

        $this->assertNotEmpty($post);
        $this->assertNotContains($tag->getName(), $post->getTags());
        $this->assertNotContains($tag->getName(), $newPost->getTags());

        /** DELETE */
        $this->postRepository->remove($post->getId());
        $this->tagRepository->remove($tag);
    }

}

?>