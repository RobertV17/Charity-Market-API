<?php

namespace Modules\Auth\Services;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Dto\LoginDto;
use Modules\User\Models\User;

class AuthService
{
    /**
     * @param User $user
     * @return string
     */
    public function createTokenForUser(User $user): string
    {
        return $user->createToken($user->login)->plainTextToken;
    }

    /**
     * @param User $user
     * @param LoginDto $dto
     *
     * @return string
     * @throws Exception
     */
    public function authenticate(User $user, LoginDto $dto): string
    {
        if(!Hash::check($dto->password, $user->password)) {
            throw new AuthenticationException('The specified data is incorrect');
        }

        return $this->createTokenForUser($user);
    }

    /**
     * @param User $user
     */
    public function dropUserTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
