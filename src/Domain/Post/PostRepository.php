<?php

namespace App\Domain\Post;

interface PostRepository 
{
    /**
     * Get all posts from database
     *
     * @return array
     */
    public function findAll() : array;

    /**
     * Find a post by ID field
     *
     * @param int $id
     * @return Post|null
     */
    public function findById($id) : ?Post;

    /**
     * Persist a Post to Database
     *
     * @param Post $post
     * @return Post
     */
    public function save(Post $post) : Post;
}

?>