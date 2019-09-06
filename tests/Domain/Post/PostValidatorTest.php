<?php

namespace App\Domain\Post;

use Tests\TestCase;

class PostValidatorTest extends TestCase {

    /**
     * Test Post Params Validator
     *
     * @return void
     */
    public function testPostValidate() : void
    {
        $faker = \Faker\Factory::create('pt_BR');

        $post = new Post(
            'Ygor.Alcantara',
            $faker->text(200),
            $faker->text(1000),
            $faker->date("Y-m-d", "now")
        );

        $postValidator = new PostValidator($post);
        
        $check = $postValidator->validate();

        $errors = $postValidator->getMessagesErrors();

        $this->assertFalse($check);
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['title']);
        $this->assertNotEmpty($errors['content']);
        $this->assertNotEmpty($errors['created_on']);
        $this->assertNotEmpty($errors['username']);
    }
}

?>