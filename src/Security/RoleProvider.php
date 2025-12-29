<?php

namespace App\Security;

use App\Enum\Role;

final class RoleProvider
{
    public string $ADMIN;
    public string $USER;

    public function __construct()
    {
        $this->ADMIN = Role::ADMIN->value;
        $this->USER  = Role::USER->value;
    }
}
