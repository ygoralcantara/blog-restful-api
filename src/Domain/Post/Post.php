<?php

namespace App\Domain\Post;

use JsonSerializable;

class Post implements JsonSerializable{

    /**
     * Post ID
     *
     * @var int
     */
    private $id;

    /**
     * Post Title
     *
     * @var string
     */
    private $title;

    /**
     * Post content
     *
     * @var string
     */
    private $content;

    /**
     * Define if post is published or not
     *
     * @var bool
     */
    private $status;

    /**
     * Amount of post likes
     *
     * @var int
     */
    private $likes;

    /**
     * Amount of post dislikes
     *
     * @var int
     */
    private $dislikes;

    /**
     * Post creation date
     *
     * @var string
     */
    private $created_at;

    /**
     * Post published date
     *
     * @var null|string
     */
    private $published_at;

    /**
     * Post creator
     *
     * @var string
     */
    private $username;

    /**
     * Post constructor
     *
     * @param int $id
     * @param string $username
     * @param string $title
     * @param string $content
     * @param bool $status
     * @param int $likes
     * @param int $dislikes
     * @param string $created_at
     * @param string|null $published_at
     */
    public function __construct(
        $username, 
        $title, 
        $content, 
        $created_at, 
        $id = 0,
        $status = false, 
        $likes = 0, 
        $dislikes = 0, 
        $published_at = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->title = $title;
        $this->content = $content;
        $this->status = $status;
        $this->likes = $likes;
        $this->dislikes = $dislikes;
        $this->created_at = $created_at;
        $this->published_at = $published_at;
    }

    /**
     * Get post ID
     *
     * @return int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set post ID
     *
     * @param int $id Post ID
     *
     * @return int
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this->id;
    }

    /**
     * Get post Title
     *
     * @return  string
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set post Title
     *
     * @param  string  $title  Post Title
     *
     * @return  string
     */ 
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this->title;
    }

    /**
     * Get post content
     *
     * @return  string
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set post content
     *
     * @param  string  $content  Post content
     *
     * @return  string
     */ 
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this->content;
    }

    /**
     * Get define if post is published or not
     *
     * @return  bool
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set define if post is published or not
     *
     * @param  bool  $status  Define if post is published or not
     *
     * @return  bool
     */ 
    public function setStatus(bool $status)
    {
        $this->status = $status;

        return $this->status;
    }

    /**
     * Get amount of post likes
     *
     * @return  int
     */ 
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set amount of post likes
     *
     * @param  int  $likes  Amount of post likes
     *
     * @return  int
     */ 
    public function setLikes(int $likes)
    {
        $this->likes = $likes;

        return $this->likes;
    }

    /**
     * Get amount of post dislikes
     *
     * @return  int
     */ 
    public function getDislikes()
    {
        return $this->dislikes;
    }

    /**
     * Set amount of post dislikes
     *
     * @param  int  $dislikes  Amount of post dislikes
     *
     * @return  int
     */ 
    public function setDislikes(int $dislikes)
    {
        $this->dislikes = $dislikes;

        return $this->dislikes;
    }

    /**
     * Get post creation date
     *
     * @return  string
     */ 
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set post creation date
     *
     * @param  string  $created_at  Post creation date
     *
     * @return string
     */ 
    public function setCreatedAt(string $created_at)
    {
        $this->created_at = $created_at;

        return $this->created_at;
    }

    /**
     * Get post published date
     *
     * @return null|string
     */ 
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * Set post published date
     *
     * @param string  $published_at  Post published date
     *
     * @return  string
     */ 
    public function setPublishedAt(string $published_at)
    {
        $this->published_at = $published_at;

        return $this->published_at;
    }

    /**
     * Get post creator
     *
     * @return  string
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set post creator
     *
     * @param  string  $username  Post creator
     *
     * @return string
     */ 
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this->username;
    }

    /**
     * Post Json
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'post_id'       => $this->id,
            'title'         => $this->title,
            'content'       => $this->content,
            'status'        => ($this->status ? 'published' : 'in revision'),
            'likes'         => $this->likes,
            'dislikes'      => $this->dislikes,
            'created_at'    => $this->created_at,
            'published_at'  => $this->published_at,
            'username'      => $this->username
        ];
    }
}

?>