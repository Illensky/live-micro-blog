<?php

namespace App\Model\Entity;

class Role extends AbstractEntity
{
    private string $roleName;
    private array $users;

    public function __construct ()
    {
        $this->users = [];
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->roleName;
    }

    /**
     * @param string $roleName
     * @return Role
     */
    public function setRoleName(string $roleName): self
    {
        $this->roleName = $roleName;
        return $this;
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @param array $users
     * @return Role
     */
    public function setUsers(array $users): self
    {
        $this->users = $users;
        return $this;
    }

}