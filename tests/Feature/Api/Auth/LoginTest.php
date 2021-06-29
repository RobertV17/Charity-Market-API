<?php


namespace Tests\Feature\Api\Auth;

use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Modules\User\Models\User;
use Tests\BaseTest;

class LoginTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function validationTestsProvider(): array
    {
        return [
            // EMAIL
            "no_email_is_provided" => [
                [
                    "password" => "1234qwer"
                ],
                ['email' => ['The email field is required.']]
            ],

            "email_is_not_string" => [
                [
                    "email"    => 3,
                    "password" => "1234qwer"
                ],
                ['email' => ['The email must be a string.']]
            ],

            "email_has_more_than_255_characters" => [
                [
                    "email"    => Str::random(256),
                    "password" => "1234qwer"
                ],
                ['email' => ['The email may not be greater than 255 characters.']]
            ],
            // PASSWORD
            "no_password_is_provided"            => [
                [
                    "email" => "tester@gmail.com",
                ],
                ['password' => ['The password field is required.']]
            ],

            "request_should_fail_when_password_is_not_string" => [
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
     * @dataProvider validationTestsProvider
     * @test
     */
    public function request_should_fail_when($data, $validationError)
    {
        $response = $this->postJson(route('auth.login'), $data);

        $response->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $validationError));

        $this->clearDb();
    }

    /** @test */
    public function login_user_with_valid_data()
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

    /** @test */
    public function login_user_with_wrong_password()
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
    public function login_with_non_existent_email_in_db()
    {
        $data = [
            "email"    => 'non_existent_email_in_db@gmail.com',
            "password" => 'wrong_password1123'
        ];

        $this->postJson(route('auth.login'), $data)
            ->assertStatus(404)
            ->assertExactJson($this->getFailResponse('User not found'));

        $this->clearDb();
    }
}
