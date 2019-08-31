<?php

namespace App\Domain\Post;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class PostValidator {

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var Post
     */
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Validate Post
     *
     * @return boolean
     */
    public function validate() : bool
    {
        $check = [];

        $check['title'] = $this->assertTitle($this->post->getTitle());
        $check['content'] = $this->assertContent($this->post->getContent());
        $check['status'] = $this->assertStatus($this->post->getStatus());
        $check['likes'] = $this->assertLikes($this->post->getLikes());
        $check['dislikes'] = $this->assertDislikes($this->post->getDislikes());
        $check['created_on'] = $this->assertCreatedOn($this->post->getCreatedAt());
        $check['published_at'] = $this->assertPublishedOn($this->post->getPublishedAt());
        $check['username'] = $this->assertUsername($this->post->getUsername());

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
     * Validate Title
     *
     * @param string $title
     * @return boolean
     */
    private function assertTitle($title) : bool
    {
        $validator = new Validator();

        $validator->alpha()->length(5, 100);

        try {
            $validator->assert($title);

        } catch (NestedValidationException $e) {
            $this->errors['title'] = $e->getMessage();

            return false;
        }
        return true;
    }

    /**
     * Validate Content
     *
     * @param string $content
     * @return boolean
     */
    private function assertContent($content) : bool
    {
        $validator = new Validator();

        $validator->length(null, 500);

        return $this->assert($validator, $content, 'content');
    }

    /**
     * Validate Status
     *
     * @param bool $status
     * @return boolean
     */
    private function assertStatus($status) : bool
    {
        $validator = new Validator();

        $validator->boolType();

        return $this->assert($validator, $status, 'status');
    }

    /**
     * Validate Likes
     *
     * @param int $likes
     * @return boolean
     */
    private function assertLikes($likes) : bool
    {
        $validator = new Validator();
        
        $validator->intType();

        return $this->assert($validator, $likes, 'likes');
    }

    /**
     * Validate Dislikes
     *
     * @param int $dislikes
     * @return boolean
     */
    private function assertDislikes($dislikes) : bool
    {
        $validator = new Validator();
        
        $validator->intType();

        return $this->assert($validator, $dislikes, 'dislikes');
    }

    /**
     * Validate Create On Timestamp
     *
     * @param string $created_on
     * @return boolean
     */
    private function assertCreatedOn($created_on) : bool
    {
        $validator = new Validator();

        $validator->date('Y-m-d H:i:s');

        return $this->assert($validator, $created_on, 'created_on');
    }

    /**
     * Validate Published at Timestamp
     *
     * @param string|null $published_at
     * @return boolean
     */
    private function assertPublishedOn($published_at) : bool
    {
        if (!isset($published_at))
            return true;

        $validator = new Validator();

        $validator->date('Y-m-d H:i:s');

        return $this->assert($validator, $published_at, 'created_on');
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