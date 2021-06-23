<?php


namespace Tests\Feature\Api\Auth;

use Tests\BaseTest;

class LogoutTest extends BaseTest
{
    /** @test */
    public function request_should_fail_when_auth_token_no_provided()
    {
        $this->getJson(route('auth.logout'))
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_auth_token_is_wrong()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $this->getJson(route('auth.logout'), ['Authorization' => 'Bearer wrong1'.$token])
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
    }

    /** @test */
    public function logout_authenticated_user()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $this->getJson(route('auth.logout'), ['Authorization' => 'Bearer '.$token])
            ->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse('Logout was successful!'));

        $this->clearDb();
    }
}
