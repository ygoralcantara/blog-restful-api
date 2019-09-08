<?php 

namespace App\Infrastructure\Persistence\PDO;

use Tests\TestCase;
use App\Domain\Tag\TagRepository;
use App\Domain\Tag\Tag;

class PDOTagRepositoryTest extends TestCase
{

    /**
     * PDO Post Repository
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

        $this->tagRepository = new PDOTagRepository($container);
    }

    /**
     * Test Find all Tags from Database
     *
     * @return void
     */
    public function testFindAllTags() : void
    {
        $tags = $this->tagRepository->findAll();

        $this->assertNotEmpty($tags);
    }

    /**
     * Test Find Tag By name field from database
     *
     * @return void
     */
    public function testFindTagByName() : void
    {
        $tag = $this->tagRepository->findAll()[0];

        $newTag = $this->tagRepository->findByName($tag->getName());

        $this->assertNotEmpty($newTag);
        $this->assertEquals($tag->getName(), $newTag->getName());
    }

    /**
     * Test Find Tag By ID field from database
     *
     * @return void
     */
    public function testFindIdByName() : void
    {
        $tag = $this->tagRepository->findAll()[0];

        $newTag = $this->tagRepository->findById($tag->getId());

        $this->assertNotEmpty($newTag);
        $this->assertEquals($tag->getName(), $newTag->getName());
    }

    /**
     * Test Persist TAG to Database
     *
     * @return void
     */
    public function testSaveTag() : void
    {
        $faker = \Faker\Factory::create('pt_BR');

        $tag = new Tag($faker->unique()->word);

        $tag = $this->tagRepository->save($tag);

        $this->assertNotEmpty($tag);
        $this->assertNotEquals(0, $tag->getId());

        $newTag = $this->tagRepository->findById($tag->getId());

        $this->assertNotEmpty($newTag);
        $this->assertEquals($tag->getName(), $newTag->getName());

        $this->tagRepository->remove($tag);
    }

    /**
     * Test Remove TAG from Database
     *
     * @return void
     */
    public function testRemoveTag() : void
    {
        $faker = \Faker\Factory::create('pt_BR');

        $tag = new Tag($faker->word);

        $tag = $this->tagRepository->save($tag);

        $this->assertNotEmpty($tag);
        $this->assertNotEquals(0, $tag->getId());

        $this->tagRepository->remove($tag);

        $emptyTag = $this->tagRepository->findById($tag->getId());

        $this->assertEmpty($emptyTag);
    }
}

?>