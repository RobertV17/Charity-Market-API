<?php

namespace Tests\Feature\Api\Auth;

/**
 * Class LogoutTest
 * @package Tests\Feature\Api\Auth
 */
class LogoutTest extends AuthTestCase
{
    /** @test */
    public function request_should_success_when_data_is_valid(): void
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $this->getJson(route('auth.logout'), ['Authorization' => 'Bearer '.$token])
            ->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse('Logout was successful!'));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_auth_token_no_provided(): void
    {
        $this->getJson(route('auth.logout'))
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_auth_token_is_wrong(): void
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $this->getJson(route('auth.logout'), ['Authorization' => 'Bearer wrong1'.$token])
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
    }
}
