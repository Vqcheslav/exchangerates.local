<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserRepository $userRepository;

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->userRepository     = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function register(
        string $email,
        string $password,
        bool $flush = true
    ): User {
        $user = new User();
        $user
            ->setEmail($email)
            ->setPassword(
                $this
                    ->userPasswordHasher
                    ->hashPassword($user, $password)
            );
        $this
            ->userRepository
            ->add($user, $flush);
        
        return $user;
    }
}