<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function register(string $email, string $password, bool $flush = true): User
    {
        $user = new User();
        $user
            ->setEmail($email)
            ->setPassword($this->userPasswordHasher->hashPassword($user, $password),);
        $this->userRepository->save($user, $flush);

        return $user;
    }
}