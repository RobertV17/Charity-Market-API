<?php


namespace Modules\User\Factories;


use Modules\User\Models\User;

class UserFactory
{
    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $user->save();
    }

    /**
     * @return User
     */
    public function create(): User
    {
        return new User;
    }
}
