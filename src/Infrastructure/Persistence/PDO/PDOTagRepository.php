<?php

namespace App\Infrastructure\Persistence\PDO;

use App\Domain\Tag\Tag;
use PDO;
use App\Domain\Tag\TagRepository;
use InvalidArgumentException;
use PDOException;
use Psr\Container\ContainerInterface;

class PDOTagRepository implements TagRepository {

    /** 
     * @var PDO
     */
    private $conn;

    /**
     * PDOTagRepository Constructor
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
     * Find All Tags from Database
     *
     * @return array
     */
    public function findAll() : array 
    {
        $sql = "SELECT * FROM tags";

        try {
            $result = $this->conn->query($sql)->fetchAll();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        $tags = [];

        foreach ($result as $row) {
            $tags[] = new Tag($row['name'], $row['id']);
        }

        return $tags;
    }

    /**
     * Find Tag By name field
     *
     * @param string $name
     * @return null|Tag
     */
    public function findByName($name) : ?Tag
    {
        $sql = "SELECT * FROM tags WHERE name = :name";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);

            $stmt->execute();

            $result = $stmt->fetch();
        
        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        return (empty($result)) ? null : new Tag($result['name'], $result['id']);
    }

    /**
     * Find Tag By ID field
     *
     * @param int $id
     * @return null|Tag
     */
    public function findById($id) : ?Tag
    {
        $sql = "SELECT * FROM tags WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            $result = $stmt->fetch();
        
        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        return (empty($result)) ? null : new Tag($result['name'], $result['id']);
    }

    /**
     * Persist Tag to Database
     *
     * @param Tag $tag
     * @return Tag
     */
    public function save(Tag $tag) : Tag
    {
        $sql = "INSERT INTO tags (name) VALUES (:name)";

        $stmt = $this->conn->prepare($sql);

        try {
            $name = $tag->getName();

            $stmt->bindParam(':name', $name, PDO::PARAM_STR);

            $stmt->execute();

            $tag->setId(intval($this->conn->lastInsertId()));

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        return $tag;
    }

    /**
     * Remove Tag from Database
     *
     * @param Tag $tag
     * @return void
     */
    public function remove(Tag $tag) : void
    {
        $sql = "DELETE FROM tags WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        try {
            $id = $tag->getId();

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();
        
        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }
}

?>