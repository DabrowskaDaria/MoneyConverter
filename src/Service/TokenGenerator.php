<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Ramsey\Uuid\Guid\Guid;
use Ramsey\Uuid\Uuid;

class TokenGenerator
{
    public function generateToken() : string
    {
        return  Uuid::uuid4()->toString();
    }

}