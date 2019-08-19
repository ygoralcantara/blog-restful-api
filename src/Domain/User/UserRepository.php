<?php
declare(strict_types=1);

namespace App\Domain\User;

interface UserRepository
{
    /**
     * Get all Users from Database
     *
     * @return array
     */
    public function findAll() : array;

    /**
     * Find a User by username field
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername($username) : ?User;

    /**
     * Find users by criteria sort data and using pagination
     *
     * @param null|array $criteria
     * @param null|array $order
     * @param integer $limit
     * @param integer $offset
     * @return array
     */
    public function findBy($criteria, $order, $limit = 10, $offset = 0) : array;

    /**
     * Count all users from database
     *
     * @return integer
     */
    public function countAll() : int;

    /**
     * Persist a User to database
     *
     * @param User $user
     * @return User
     */
    public function save(User $user) : User;

    /**
     * Persist various Users to database
     *
     * @param array $users
     * @return array
     */
    public function saveAll($users = []) : array;

    /**
     * Remove a User from database
     *
     * @param User $user
     * @return void
     */
    public function remove($user) : void;

    /**
     * Remove various Users from database
     *
     * @param array
     * @return void
     */
    public function removeAll($users = []) : void;
    
}
