<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineUserRepository implements UserRepository {

    /**
     * Entity Manager
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Entity Repository
     *
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * DoctrineUserRepository construct
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->entityRepository = $em->getRepository(User::class);
    }

    /**
     * Get all Users from Database
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->em->createQueryBuilder()
                        ->select('u')
                        ->from(User::class, 'u')
                        ->orderBy('u.username', 'ASC')
                        ->getQuery()->getResult();
    }

    /**
     * Get User by username field
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername($username) : ?User
    {
        return $this->em->find(User::class, $username);
    }

    /**
     * Find users by criteria, sort data and using pagination
     *
     * @return array
     */
    public function findBy($criteria, $order, $limit = 10, $offset = 0) : array
    {
        return $this->entityRepository->findBy($criteria, $order, $limit, $offset);
    }

    /**
     * Count all users from database
     *
     * @return integer
     */
    public function countAll() : int
    {
        $result = $this->em->createQueryBuilder()->select('count(u.username)')
                        ->from(User::class, 'u')
                        ->getQuery()->getSingleScalarResult();

        return intval($result);
    }

    /**
     * Persist a User to database
     *
     * @param User $user
     * @return User
     */
    public function save(User $user) : User
    {
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * Persist various Users to database
     *
     * @param array $user
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
        $this->em->remove($user);
        $this->em->flush();
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