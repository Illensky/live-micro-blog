<?php

namespace App\Model\Manager;

use App\Model\Entity\User;
use App\Model\DBSingleton;

class UserManager
{
    public function getUserById (int $id): User
    {
        $user = new User;

        return $user;
    }
}