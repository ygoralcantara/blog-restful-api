<?php

namespace App\Infrastructure\Persistence\PDO;

use App\Domain\Post\Post;
use PDO;
use PDOException;
use InvalidArgumentException;
use App\Domain\Post\PostRepository;
use App\Domain\Tag\Tag;
use Psr\Container\ContainerInterface;

class PDOPostRepository implements PostRepository {

    /**
     * PDO Connection
     *
     * @var PDO
     */
    private $conn;

    /**
     * PDOPostRepository Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        if ($container->has('pdo-conn')) {
            $this->conn = $container->get('pdo-conn');
        }
        else {
            throw new InvalidArgumentException("PDO Connection doesn't exists in Container", 500);
        }
    }

    /**
     * Get all posts from database
     *
     * @return array
     */
    public function findAll() : array
    {
        $sql_tags = "SELECT
                        t.name
                        FROM posts_tags AS pt
                        INNER JOIN tags AS t ON pt.tag_id = t.id
                        WHERE pt.post_id = p.id";

        $sql = "SELECT 
                    p.*,
                    (SELECT count(*) FROM posts_like AS pl WHERE pl.post_id = p.id AND pl.is_like = true) AS likes,
                    (SELECT count(*) FROM posts_like AS pl WHERE pl.post_id = p.id AND pl.is_like = false) AS dislikes,
                    ARRAY( ${sql_tags} ) AS tags
                    FROM posts AS p 
                    ORDER BY p.title ASC";

        try {
            $result = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }

        $posts = [];

        foreach ($result as $row) {
            
            if ($row['tags'] !== "{}") {
                $tags = explode(',', str_replace(['{', '}'], '', $row['tags']));
            }
            else {
                $tags = [];
            }

            $posts[] = new Post(
                $row['username'],
                $row['title'],
                $row['content'],
                $row['created_at'],
                $row['id'],
                $row['status'],
                $row['likes'],
                $row['dislikes'],
                $row['published_at'],
                $tags
            );
        }

        return $posts;
    }

    /**
     * Find a post by ID field
     *
     * @param int $id
     * @return Post|null
     */
    public function findById($id) : ?Post
    {
        $sql_tags = "SELECT
                        t.name
                        FROM posts_tags AS pt
                        INNER JOIN tags AS t ON pt.tag_id = t.id
                        WHERE pt.post_id = p.id";

        $sql = "SELECT
                    p.*,
                    (SELECT count(*) FROM posts_like AS pl WHERE pl.post_id = p.id AND pl.is_like = true) AS likes,
                    (SELECT count(*) FROM posts_like AS pl WHERE pl.post_id = p.id AND pl.is_like = false) AS dislikes,
                    ARRAY( ${sql_tags} ) AS tags
                    FROM posts AS p 
                    WHERE p.id = :id";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute(['id' => $id]);

            $result = $stmt->fetch();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }

        if (empty($result))
            return null;

        if ($result['tags'] !== "{}") {
            $tags = explode(',', str_replace(['{', '}'], '', $result['tags']));
        }
        else {
            $tags = [];
        }

        return new Post(
            $result['username'],
            $result['title'],
            $result['content'],
            $result['created_at'],
            $result['id'],
            $result['status'],
            $result['likes'],
            $result['dislikes'],
            $result['published_at'],
            $tags
        );
    }

    /**
     * Persist a Post to Database
     *
     * @param Post $post
     * @return Post
     */
    public function save(Post $post) : Post
    {
        $checkPost = $this->findById($post->getId());

        $check = (isset($checkPost)) ? true : false; 

        if ($check) {
            $sql = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
        }
        else {
            $sql = "INSERT INTO posts (title, content, created_at, username) VALUES (:title, :content, :created_at, :username)";
        }

        $stmt = $this->conn->prepare($sql);

        try {
            if ($check) {
                $stmt->execute([
                    'title'     => $post->getTitle(),
                    'content'   => $post->getContent(),
                    'id'        => $post->getId(),
                ]);
            }
            else {
                $stmt->execute([
                    'title'         => $post->getTitle(),
                    'content'       => $post->getContent(),
                    'created_at'    => $post->getCreatedAt(),
                    'username'      => $post->getUsername(),
                ]);

                $post->setId(intval($this->conn->lastInsertId()));
            }

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }

        return $post;
    }

    /**
     * Remove a Post from database
     *
     * @param int $id
     * @return void
     */
    public function remove($id) : void
    {
        $sql = "DELETE FROM posts WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute(['id' => $id]);

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }
    }

    /**
     * Count how much likes or dislikes belongs to Post
     *
     * @param Post $post
     * @param bool $like
     * @return integer
     */
    public function countLikes(Post $post, $like) : int
    {
        $sql = "SELECT count(*) FROM posts_like AS pl
                    WHERE pl.post_id = :id AND pl.is_like = :like";

        $stmt = $this->conn->prepare($sql);

        try {
            $id = $post->getId();

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':like', $like, PDO::PARAM_BOOL);

            $stmt->execute();

            $row = $stmt->fetch();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        return intval($row['count']);
    }

    /**
     * Increase like of a Post
     *
     * @param Post $post
     * @param bool $like
     * @param string $username
     * @return Post
     */
    public function likePost(Post $post, $like, $username) : Post
    {
        $check = $this->checkLike($post->getId(), $username);
        
        if ($check) {
            $sql = "UPDATE posts_like SET is_like = :like
                        WHERE user_username = :username AND post_id = :id";    
        } 
        else {
            $sql = "INSERT INTO posts_like (is_like, post_id, user_username)
                        VALUES (:like, :id, :username)";
        }
        
        $stmt = $this->conn->prepare($sql);

        try {
            $id = $post->getId();

            $stmt->bindParam(':like', $like, PDO::PARAM_BOOL);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        $post->setLikes($this->countLikes($post, true));
        $post->setDislikes($this->countLikes($post, false));

        return $post;
    }

    /**
     * Remove Like of a Post
     *
     * @param Post $post
     * @param string $username
     * @return Post
     */
    public function removeLike(Post $post, $username) : Post
    {
        $sql = "DELETE FROM posts_like WHERE post_id = :post_id AND user_username = :user_username";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                'post_id'       => $post->getId(),
                'user_username' => $username
            ]);

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        $post->setLikes($this->countLikes($post, true));
        $post->setDislikes($this->countLikes($post, false));

        return $post;
    }

    /**
     * Check if Like Exists
     *
     * @param int $post_id
     * @param string $username
     * @return boolean
     */
    public function checkLike($post_id, $username) : bool
    {
        $sql = "SELECT count(*) FROM posts_like AS pl 
                    WHERE pl.post_id = :id AND pl.user_username = :username";
        
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->bindParam(':id', $post_id, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);

            $stmt->execute();

            $row = $stmt->fetch();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        return (intval($row['count']) == 0) ? false : true;
    }

    /**
     * Add Tag to Post
     *
     * @param Post $post
     * @param Tag $tag
     * @return Post
     */
    public function addTag(Post $post, Tag $tag) : Post 
    {
        $sql = "INSERT INTO posts_tags (post_id, tag_id) VALUES (:post_id, :tag_id)";

        $stmt = $this->conn->prepare($sql);

        try {
            $post_id = $post->getId();
            $tag_id = $tag->getId();

            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);

            $stmt->execute();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        $post_tags = $post->getTags();

        $post_tags[] = $tag->getName();

        $post->setTags($post_tags);

        return $post;
    }

    /**
     * Remove Tag to Post
     *
     * @param Post $post
     * @param Tag $tag
     * @return Post
     */
    public function removeTag(Post $post, Tag $tag) : Post
    {
        $sql = "DELETE FROM posts_tags WHERE post_id = :post_id AND tag_id = :tag_id";

        $stmt = $this->conn->prepare($sql);

        try {
            $post_id = $post->getId();
            $tag_id = $tag->getId();

            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);

            $stmt->execute();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        $post_tags = $post->getTags();

        $index = array_search($tag->getName(), $post_tags);

        unset($post_tags[$index]);

        $post->setTags($post_tags);

        return $post;
    }

}

?>