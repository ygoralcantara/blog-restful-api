<?php

namespace App\Infrastructure\Persistence\PDO;

use App\Domain\Post\Post;
use PDO;
use PDOException;
use InvalidArgumentException;
use App\Domain\Post\PostRepository;
use Psr\Container\ContainerInterface;

class PDOPostRepository implements PostRepository {

    /**
     * PDO Connection
     *
     * @var PDO
     */
    private $conn;

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
        $sql = "SELECT * FROM posts ORDER BY title ASC";

        try {
            $result = $this->conn->query($sql)->fetchAll();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }

        $posts = [];

        foreach ($result as $row) {
            $posts[] = new Post(
                $row['username'],
                $row['title'],
                $row['content'],
                $row['created_at'],
                $row['id'],
                $row['status'],
                $row['likes'],
                $row['dislikes'],
                $row['published_at']
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
        $sql = "SELECT * FROM posts WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute(['id' => $id]);

            $result = $stmt->fetch();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }

        return (empty($result)) ? null : new Post(
            $result['username'],
            $result['title'],
            $result['content'],
            $result['created_at'],
            $result['id'],
            $result['status'],
            $result['likes'],
            $result['dislikes'],
            $result['published_at']
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

}

?>