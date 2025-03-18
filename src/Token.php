<?php

namespace App;

use App\Entity\User;
use App\Repository\UserRepository;
use Ramsey\Uuid\Guid\Guid;
use Ramsey\Uuid\Uuid;

class Token
{
    public function generateToken(User $user,UserRepository $userRepository) : string
    {
        $token = Uuid::uuid4()->toString();
        $user->setToken($token);
        $user->setTokenExpiresAt(new \DateTime('+5 minutes'));
        $userRepository->save($user);
        return $token;
    }

    public function generateActivationToken(User $user,UserRepository $userRepository) : string
    {
        $token = Uuid::uuid4() -> toString();
        $user->setToken($token);
        $user->setTokenExpiresAt(new \DateTime('+10 minutes'));
        $userRepository->save($user);
        return $token;
    }
}