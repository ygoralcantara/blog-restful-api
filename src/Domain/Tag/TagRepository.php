<?php

namespace App\Domain\Tag;

interface TagRepository {

    /**
     * Find All Tags from Database
     *
     * @return array
     */
    public function findAll() : array;

    /**
     * Find Tag By name field
     *
     * @param string $name
     * @return null|Tag
     */
    public function findByName($name) : ?Tag;

    /**
     * Find Tag By ID field
     *
     * @param int $id
     * @return null|Tag
     */
    public function findById($id) : ?Tag;

    /**
     * Persist Tag to Database
     *
     * @param Tag $tag
     * @return Tag
     */
    public function save(Tag $tag) : Tag;

    /**
     * Remove Tag from Database
     *
     * @param Tag $tag
     * @return void
     */
    public function remove(Tag $tag) : void;
}

?>