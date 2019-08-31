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
        $check = [];

        $check['username'] = $this->assertUsername($this->user->getUsername());
        $check['email'] = $this->assertEmail($this->user->getEmail());
        $check['name'] = $this->assertName($this->user->getName());
        $check['password'] = $this->assertPassword($this->user->getPassword());

        return (in_array(false, $check)) ? false : true;
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

    /**
     * Validate Username
     *
     * @param string $username
     * @return boolean
     */
    private function assertUsername($username) : bool
    {
        $validator = new Validator();

        $validator->alnum()->lowercase()->noWhitespace()->length(5, 20);

        return $this->assert($validator, $username, 'username');
    }

    /**
     * Validate Email
     *
     * @param string $email
     * @return boolean
     */
    private function assertEmail($email) : bool
    {
        $validator = new Validator();

        $validator->lowercase()->email()->max(50);

        return $this->assert($validator, $email, 'email');
    }

    /**
     * Validate Name
     *
     * @param string $name
     * @return boolean
     */
    private function assertName($name) : bool
    {
        $validator = new Validator();

        $validator->alpha()->max(100);

        return $this->assert($validator, $name, 'name');
    }

    /**
     * Validate Password
     *
     * @param string $password
     * @return boolean
     */
    private function assertPassword($password) : bool
    {
        $validator = new Validator();

        $validator->notEmpty();

        return $this->assert($validator, $password, 'password');
    }

    /**
     * @param Validator $validator
     * @param mixed $field
     * @param string $name
     * @return boolean
     */
    private function assert(Validator $validator, $field, $name) : bool
    {
        try {
            $validator->assert($field);

        } catch (NestedValidationException $e) {
            $this->errors[$name] = $e->getMessage();

            return false;
        }
        return true;
    }
}

?>