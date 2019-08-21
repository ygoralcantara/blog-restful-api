<?php

namespace App\Domain\User;

use Tests\TestCase;

class UserValidatorTest extends TestCase {

    /**
     * Test User Params Validator
     *
     * @return void
     */
    public function testUserValidate() : void
    {
        /** CREATE USER INVALID */
        $user = new User(
            'Ygor.Alcantara',
            'Ygor12345',
            'qwerty',
            ''
        );

        $userValidator = new UserValidator($user);

        $check = $userValidator->validate();

        $this->assertFalse($check);

        $errors = $userValidator->getMessagesErrors();

        $this->assertNotEmpty($errors);
    }
    
}

?>