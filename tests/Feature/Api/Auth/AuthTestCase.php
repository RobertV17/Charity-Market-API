<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Support\Facades\DB;
use Tests\Feature\Api\ApiTestCase;

/**
 * Class AuthTestCase
 * @package Tests\Feature\Api\Auth
 */
class AuthTestCase extends ApiTestCase
{
    /**
     * @param $user
     *
     * @return bool
     */
    protected function checkExistsAuthTokenByUser($user): bool
    {
        return DB::table('personal_access_tokens')
            ->where('tokenable_id', $user->id)
            ->exists();
    }
}
