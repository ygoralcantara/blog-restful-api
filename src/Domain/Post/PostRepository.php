<?php

namespace App\Domain\Post;

use App\Domain\Tag\Tag;

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

    /**
     * Remove a Post from database
     *
     * @param int $id
     * @return void
     */
    public function remove($id) : void;

    /**
     * Count how much likes or dislikes belongs to Post
     *
     * @param Post $post
     * @param bool $like
     * @return integer
     */
    public function countLikes(Post $post, $like) : int;

    /**
     * Increase like of a Post
     *
     * @param Post $post
     * @param bool $like
     * @param string $username
     * @return Post
     */
    public function likePost(Post $post, $like, $username) : Post;

    /**
     * Remove Like of a Post
     *
     * @param Post $post
     * @param string $username
     * @return Post
     */
    public function removeLike(Post $post, $username) : Post;

    /**
     * Check if Like Exists
     *
     * @param int $post_id
     * @param string $username
     * @return boolean
     */
    public function checkLike($post_id, $username) : bool;

    /**
     * Add Tag to Post
     *
     * @param Post $post
     * @param Tag $tag
     * @return Post
     */
    public function addTag(Post $post, Tag $tag) : Post;

    /**
     * Remove Tag to Post
     *
     * @param Post $post
     * @param Tag $tag
     * @return Post
     */
    public function removeTag(Post $post, Tag $tag) : Post;
}

?>