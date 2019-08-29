<?php

namespace App\Infrastructure\Persistence\PDO;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use InvalidArgumentException;
use PDO;
use PDOException;
use Psr\Container\ContainerInterface;

class PDOUserRepository implements UserRepository {

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
     * Get all Users from Database
     *
     * @return array
     */
    public function findAll() : array
    {
        $sql = "SELECT * FROM users ORDER BY username ASC";

        try {
            $result = $this->conn->query($sql)->fetchAll();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }

        $users = [];

        foreach ($result as $row) {
            $users[] = new User(
                $row['username'],
                $row['name'],
                $row['email'],
                $row['password']
            );
        }

        return $users;
    }

    /**
     * Find a User by username field
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername($username) : ?User
    {
        $sql = "SELECT * FROM users WHERE username = :username";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                'username' => $username,
            ]);

            $result = $stmt->fetch();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }

        return (empty($result)) ? null : new User(
            $result['username'],
            $result['name'],
            $result['email'],
            $result['password']
        );
    }

    /**
     * Find users by criteria sort data and using pagination
     *
     * @param null|array $criteria
     * @param null|array $order
     * @param integer $limit
     * @param integer $offset
     * @return array
     */
    public function findBy($criteria, $order, $limit = 10, $offset = 0) : array
    {
        $sql = "SELECT * FROM users";

        if (!empty($criteria)) {
            $i = 0;
            
            foreach ($criteria as $column => $value) {
                if ($i == 0) {
                    $sql .= " WHERE ";

                } else {
                    $sql .= " AND ";
                }
                
                $sql .= $column . " = '" . $value . "'";

                $i++;
            }
        }

        if (isset($order)) {
            $sql .= " ORDER BY ";

            $i = 0;
            foreach ($order as $column => $sort) {
                if ($i > 0) {
                    $sql .= ", ";
                }
                
                $sql .= $column . " " . $sort;

                $i++;
            }
        }

        if (isset($limit)) {
            $sql .= " LIMIT " . $limit;
        }

        if (isset($offset)) {
            $sql .= " OFFSET " . $offset;
        }

        try {
            $result = $this->conn->query($sql)->fetchAll();

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }

        $users = [];

        foreach ($result as $row) {
            $users[] = new User(
                $row['username'],
                $row['name'],
                $row['email'],
                $row['password'],
            );
        }

        return $users;
    }

    /**
     * Count all users from database
     *
     * @return integer
     */
    public function countAll() : int
    {
        $sql = "SELECT COUNT(*) AS num FROM users";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }

        return intval($result['num']);
    }

    /**
     * Persist a User to database
     *
     * @param User $user
     * @return User
     */
    public function save(User $user) : User
    {
        $checkUser = $this->findByUsername($user->getUsername());

        if (isset($checkUser)) {
            $sql = "UPDATE users SET username = :username, name = :name, email = :email, password = :password WHERE username = :username"; 
        } 
        else {
            $sql = "INSERT INTO users (username, name, email, password) VALUES (:username, :name, :email, :password)";
        }

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                ':username' => $user->getUsername(),
                ':name'     => $user->getName(),
                ':email'    => $user->getEmail(),
                ':password' => $user->getPassword(),
            ]);

        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }

        return $user;
    }

    /**
     * Persist various Users to database
     *
     * @param array $users
     * @return array
     */
    public function saveAll($users = []) : array
    {
        foreach ($users as $user) {
            $user = $this->save($user);
        }

        return $users;
    }

    /**
     * Remove a User from database
     *
     * @param User $user
     * @return void
     */
    public function remove($user) : void
    {
        $sql = "DELETE FROM users WHERE username = :username";

        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                ':username' => $user->getUsername(),
            ]);
            
        } catch (PDOException $e) {
            throw new InvalidArgumentException($e->getMessage(), 500);
        }
    }

    /**
     * Remove various Users from database
     *
     * @param array
     * @return void
     */
    public function removeAll($users = []) : void
    {
        foreach ($users as $user) {
            $this->remove($user);
        }
    }
}

?>