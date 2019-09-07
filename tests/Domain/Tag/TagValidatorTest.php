<?php

namespace App\Domain\Tag;

use Tests\TestCase;

class TagValidatorTest extends TestCase {

    /**
     * Test Tag Params Validator
     *
     * @return void
     */
    public function testTagValidate() : void
    {
        $faker = \Faker\Factory::create('pt_BR');

        $name = $faker->words(200, true) . "50";

        $tag = new Tag($name);

        $tagValidator = new TagValidator($tag);

        $check = $tagValidator->validate();

        $errors = $tagValidator->getErrors();

        $this->assertFalse($check);
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['name']);
    }
}

?>