<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Support\Str;
use Modules\User\Models\User;
use Tests\BaseTest;

class RegistrationTest extends BaseTest
{
    public function validationTestsProvider()
    {
        return [
            // EMAIL
            "request_should_fail_when_no_email_is_provided" => [
                [
                    "login"    => "Best Tester",
                    "password" => "1234qwer"
                ],
                ['email' => ['The email field is required.']]
            ],

            "request_should_fail_when_email_is_not_string" => [
                [
                    "email"    => 3,
                    "login"    => "Best Tester",
                    "password" => "1234qwer"
                ],
                ['email' => ['The email must be a string.']]
            ],

            "request_should_fail_when_email_has_more_than_255_characters" => [
                [
                    "email"    => Str::random(256),
                    "login"    => "Best Tester",
                    "password" => "1234qwer"
                ],
                ['email' => ['The email may not be greater than 255 characters.']]
            ],
            // LOGIN
            "request_should_fail_when_no_login_is_provided"               => [
                [
                    "email"    => "tester@gmail.com",
                    "password" => "1234qwer"
                ],
                ['login' => ['The login field is required.']]
            ],

            "request_should_fail_when_login_is_not_string" => [
                [
                    "email"    => "tester@gmail.com",
                    "login"    => 3,
                    "password" => "1234qwer"
                ],
                ['login' => ['The login must be a string.']]
            ],

            "request_should_fail_when_login_has_more_than_150_characters" => [
                [
                    "email"    => "tester@gmail.com",
                    "login"    => Str::random(151),
                    "password" => "1234qwer"
                ],
                ['login' => ['The login may not be greater than 150 characters.']]
            ],
            // PASSWORD
            "request_should_fail_when_no_password_is_provided"               => [
                [
                    "email"    => "tester@gmail.com",
                    "login"    => "Best Tester"
                ],
                ['password' => ['The password field is required.']]
            ],

            "request_should_fail_when_password_is_not_string" => [
                [
                    "email"    => "tester@gmail.com",
                    "login"    => "Best Tester",
                    "password" => 3
                ],
                ['password' => ['The password must be a string.']]
            ],

            "request_should_fail_when_password_has_more_than_150_characters" => [
                [
                    "email"    => "tester@gmail.com",
                    "login"    => "Best Tester",
                    "password"    => Str::random(151),
                ],
                ['password' => ['The password may not be greater than 150 characters.']]
            ]
        ];
    }

    /**
     * @dataProvider validationTestsProvider
     * @test
     */
    public function request_should_fail_when($data, $validationError)
    {
        $response = $this->postJson(route('auth.registration'), $data);

        $response->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $validationError));

        $this->clearDb();
    }

    /** @test  **/
    public function request_should_fail_when_user_with_provided_email_already_exists()
    {
        $user = $this->createFakeUser();

        $data = [
            "email"    => $user->email,
            "login"    => "Best Tester",
            "password" => "1234qwer"
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

    /** @test  **/
    public function request_should_fail_when_user_with_provided_login_already_exists()
    {
        $user = $this->createFakeUser();

        $data = [
            "email"    => "tester@gmail.com",
            "login"    => $user->login,
            "password" => "1234qwer"
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

    /** @test  **/
    public function user_registration_with_valid_data()
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

}
