<?php

declare(strict_types = 1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

trait UserEntityManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     */
    public function persist(User $user): void
    {
        $this->entityManager->persist($user);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    /**
     * @param string $userId
     *
     * @return User|null
     */
    public function getUserById(string $userId): ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(UserRepository::class);

        try {
            /** @var User $user */
            $user = $userRepository->findOneById($userId);
        } catch (\Exception $e) {
            return null;
        }

        return $user;
    }
}