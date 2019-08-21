<?php

namespace App\Domain\User;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class UserValidator {

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Validate User
     *
     * @return boolean
     */
    public function validate() : bool
    {
        $username = $this->assertUsername($this->user->getUsername());
        $email = $this->assertEmail($this->user->getEmail());
        $name = $this->assertName($this->user->getName());
        $password = $this->assertPassword($this->user->getPassword());

        if (!$username || !$email || !$name || !$password) {
            return false;
        }

        return true;
    }

    /**
     * Get Messages Errors
     *
     * @return array
     */
    public function getMessagesErrors() : array
    {
        return $this->errors;
    }

    private function assertUsername($username) : bool
    {
        $validator = new Validator();

        $validator->alnum()->lowercase()->noWhitespace()->length(5, 20);

        try {
            $validator->assert($username);

        } catch (NestedValidationException $e) {
            $this->errors['username'] = $e->getMessages();

            return false;
        }

        return true;
    }

    private function assertEmail($email) : bool
    {
        $validator = new Validator();

        $validator->lowercase()->email()->max(50);

        try {
            $validator->assert($email);

        } catch (NestedValidationException $e) {
            $this->errors['email'] = $e->getMessages();

            return false;
        }

        return true;
    }

    private function assertName($name) : bool
    {
        $validator = new Validator();

        $validator->alpha()->max(100);

        try {
            $validator->assert($name);

        } catch (NestedValidationException $e) {
            $this->errors['name'] = $e->getMessages();

            return false;
        }

        return true;
    }

    private function assertPassword($password) : bool
    {
        $validator = new Validator();

        $validator->notEmpty();

        try {
            $validator->assert($password);

        } catch (NestedValidationException $e) {
            $this->errors['password'] = $e->getMessages();

            return false;
        }

        return true;
    }

}

?>