<?php

namespace App\Domain\Tag;

use App\Domain\Tag\Tag;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class TagValidator {

    /** 
     * @var array
     */
    private $errors;

    /** 
     * @var Tag
     */
    private $tag;

    /**
     * Tag Validator Constructor
     *
     * @param Tag $tag
     */
    public function __construct(Tag $tag)
    {
        $this->errors = [];
        $this->tag = $tag;
    }

    /**
     * Get the value of errors
     *
     * @return  array
     */ 
    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * Set the value of tag
     *
     * @param  Tag  $tag
     *
     * @return  self
     */ 
    public function setTag(Tag $tag)
    {
        $this->errors = [];

        $this->tag = $tag;

        return $this;
    }
    
    /**
     * Validate all fields
     *
     * @return boolean
     */
    public function validate() : bool
    {
        return $this->assertName($this->tag->getName());
    }

    /**
     * Validate Tag name field
     *
     * @param string $name
     * @return boolean
     */
    private function assertName($name) : bool
    {
        $validator = new Validator();

        $validator->alpha()->length(3, 50);

        try {
            $validator->assert($name);

        } catch (NestedValidationException $e) {
            $this->errors['name'] = $e->getMessage();

            return false;
        }
        return true;
    }
}

?>