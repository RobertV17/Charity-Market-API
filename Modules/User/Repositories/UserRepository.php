<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;

class UserRepository
{
    /**
     * @param string $email
     * @return User
     */
    public function getByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }
}
