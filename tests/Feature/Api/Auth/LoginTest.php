<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Support\Str;
use Modules\User\Models\User;

/**
 * Class LoginTest
 * @package Tests\Feature\Api\Auth
 */
class LoginTest extends AuthTestCase
{
    /** @test */
    public function request_should_success_when_data_is_valid(): void
    {
        $userPassword = 'pas1234';
        $user = $this->createFakeUser($userPassword);

        $data = [
            "email"    => $user->email,
            "password" => $userPassword
        ];

        $response = $this->postJson(route('auth.login'), $data);

        $user = User::all()->first();
        $exceptedData = [
            'user'  => $user->toArray(),
            'token' => $response->original['data']['token']
        ];

        $response->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse('Login was successful!', $exceptedData));

        $tokenIsExist = $this->checkExistsAuthTokenByUser($user);
        $this->assertEquals(true, $tokenIsExist);

        $this->clearDb();
    }

    /** @dataProvider  */
    public function wrongRequestDataProvider(): array
    {
        return [
            // email
            "no_email_is_provided"                                           => [
                [
                    "password" => "1234qwer"
                ],
                ['email' => ['The email field is required.']]
            ],
            "email_is_not_string"                                            => [
                [
                    "email"    => 3,
                    "password" => "1234qwer"
                ],
                ['email' => ['The email must be a string.']]
            ],
            "email_has_more_than_255_characters"                             => [
                [
                    "email"    => Str::random(256),
                    "password" => "1234qwer"
                ],
                ['email' => ['The email may not be greater than 255 characters.']]
            ],
            // password
            "no_password_is_provided"                                        => [
                [
                    "email" => "tester@gmail.com",
                ],
                ['password' => ['The password field is required.']]
            ],
            "request_should_fail_when_password_is_not_string"                => [
                [
                    "email"    => "tester@gmail.com",
                    "password" => 3
                ],
                ['password' => ['The password must be a string.']]
            ],
            "request_should_fail_when_password_has_more_than_150_characters" => [
                [
                    "email"    => "tester@gmail.com",
                    "password" => Str::random(151),
                ],
                ['password' => ['The password may not be greater than 150 characters.']]
            ]
        ];
    }

    /**
     * @dataProvider wrongRequestDataProvider
     * @test
     */
    public function request_should_fail_when($data, $expectedValidationErrors): void
    {
        $response = $this->postJson(route('auth.login'), $data);

        $response->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $expectedValidationErrors));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_password_is_wrong(): void
    {
        $user = $this->createFakeUser();

        $data = [
            "email"    => $user->email,
            "password" => 'wrong_password1123'
        ];

        $this->postJson(route('auth.login'), $data)
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('The specified data is incorrect'));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_email_is_non_existent_in_db(): void
    {
        $data = [
            "email"    => 'nonexistentemailin_db@gmail.com',
            "password" => 'wrong_password1123'
        ];

        $this->postJson(route('auth.login'), $data)
            ->assertStatus(404)
            ->assertExactJson($this->getFailResponse('User not found'));

        $this->clearDb();
    }
}
