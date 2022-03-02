<?php

namespace App\Model\Manager;

use App\Model\DBSingleton;
use App\Model\Entity\Role;

class RoleManager
{
    private UserRoleManager $userRoleManager;

    public function __construct()
    {
        $this->userRoleManager = new UserRoleManager();
    }

    public function getAll(): array
    {
        $roles = [];
        $query = DBSingleton::PDO()->query("
            SELECT * FROM role
        ");
        if ($query) {
            foreach ($query->fetchAll() as $roleData) {
                $role = new Role();
                $role->setId($roleData['id']);
                $role->setRoleName($roleData['role_name']);
                $role->setUsers($this->userRoleManager->getUsersByRoleId($roleData['id']));
                $roles[] = $role;
            }
        }
        return $roles;
    }
}