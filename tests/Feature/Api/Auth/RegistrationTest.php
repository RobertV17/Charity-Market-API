<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Support\Str;
use Modules\User\Models\User;

/**
 * Class RegistrationTest
 * @package Tests\Feature\Api\Auth
 */
class RegistrationTest extends AuthTestCase
{
    /** @test  * */
    public function request_should_success_when_data_is_valid(): void
    {
        $data = [
            "email"    => "tester@gmail.com",
            "login"    => "Best Tester",
            "password" => "1234qwer"
        ];

        $response = $this->postJson(route('auth.registration'), $data);

        $user = User::all()->first();
        $exceptedData = [
            'user'  => $user->toArray(),
            'token' => $response->original['data']['token']
        ];

        $response->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse('Registration was successful!', $exceptedData));

        $tokenIsExist = $this->checkExistsAuthTokenByUser($user);
        $this->assertEquals(true, $tokenIsExist);

        $this->clearDb();
    }

    /** @dataProvider  */
    public function wrongRequestDataProvider(): array
    {
        return [
            // email
            "no_email_is_provided" => [
                [
                    "login"    => "Best Tester",
                    "password" => "1234qwer"
                ],
                ['email' => ['The email field is required.']]
            ],
            "email_is_not_string" => [
                [
                    "email"    => 3,
                    "login"    => "Best Tester",
                    "password" => "1234qwer"
                ],
                ['email' => ['The email must be a string.']]
            ],
            "email_has_more_than_255_characters" => [
                [
                    "email"    => Str::random(256),
                    "login"    => "Best Tester",
                    "password" => "1234qwer"
                ],
                ['email' => ['The email may not be greater than 255 characters.']]
            ],
            // login
            "no_login_is_provided"               => [
                [
                    "email"    => "tester@gmail.com",
                    "password" => "1234qwer"
                ],
                ['login' => ['The login field is required.']]
            ],
            "login_is_not_string" => [
                [
                    "email"    => "tester@gmail.com",
                    "login"    => 3,
                    "password" => "1234qwer"
                ],
                ['login' => ['The login must be a string.']]
            ],
            "login_has_more_than_150_characters" => [
                [
                    "email"    => "tester@gmail.com",
                    "login"    => Str::random(151),
                    "password" => "1234qwer"
                ],
                ['login' => ['The login may not be greater than 150 characters.']]
            ],
            // password
            "no_password_is_provided"            => [
                [
                    "email" => "tester@gmail.com",
                    "login" => "Best Tester"
                ],
                ['password' => ['The password field is required.']]
            ],
            "password_is_not_string" => [
                [
                    "email"    => "tester@gmail.com",
                    "login"    => "Best Tester",
                    "password" => 3
                ],
                ['password' => ['The password must be a string.']]
            ],
            "password_has_more_than_150_characters" => [
                [
                    "email"    => "tester@gmail.com",
                    "login"    => "Best Tester",
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
        $response = $this->postJson(route('auth.registration'), $data);

        $response->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $expectedValidationErrors));

        $this->clearDb();
    }

    /** @test  * */
    public function request_should_fail_when_user_with_provided_email_already_exists(): void
    {
        $user = $this->createFakeUser();

        $data = [
            "email"    => $user->email,
            "login"    => 'Best Tester',
            "password" => '1234qwer'
        ];

        $response = $this->postJson(route('auth.registration'), $data);
        $expectedData = [
            'email' => [
                'The email has already been taken.'
            ]
        ];

        $response->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $expectedData));

        $this->clearDb();
    }

    /** @test  * */
    public function request_should_fail_when_user_with_provided_login_already_exists(): void
    {
        $user = $this->createFakeUser();

        $data = [
            "email"    => "tester@gmail.com",
            "login"    => $user->login,
            "password" => '1234qwer'
        ];

        $response = $this->postJson(route('auth.registration'), $data);
        $expectedData = [
            'login' => [
                'The login has already been taken.'
            ]
        ];

        $response->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $expectedData));

        $this->clearDb();
    }
}
