<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Modules\User\Models\User;
use Tests\BaseTest;

class AuthTest extends BaseTest
{
    use WithFaker;

    //        $this->withoutExceptionHandling();
    public function getSuccessResponse($message, $data = [])
    {
        return [
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ];
    }

    public function getFailResponse($message, $data = [])
    {
        return [
            'status'  => 'fail',
            'message' => $message,
            'data'    => $data
        ];
    }

    protected function createFakeUser(): User
    {
        $user = new User();
        $user->login = 'user_0';
        $user->email = 'user_0@gmail.com';
        $user->password = '1234qwer';
        $user->save();

        return $user;
    }

    public function test_user_registration_with_valid_data()
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

        $tokenIsExist = DB::table('personal_access_tokens')
            ->where('tokenable_id', $user->id)
            ->exists();
        $this->assertEquals(true, $tokenIsExist);

        $this->clearDb();
    }

    public function test_request_should_fail_when_no_email_is_provided()
    {
        $data = [
            "login"    => "Best Tester",
            "password" => "1234qwer"
        ];

        $response = $this->postJson(route('auth.registration'), $data);
        $expectedData = [
            'email' => [
                'The email field is required.'
            ]
        ];

        $response->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $expectedData));

        $this->clearDb();
    }

    public function test_request_should_fail_when_email_is_not_string()
    {
        $data = [
            "email"    => 3,
            "login"    => "Best Tester",
            "password" => "1234qwer"
        ];

        $response = $this->postJson(route('auth.registration'), $data);
        $expectedData = [
            'email' => [
                'The email must be a string.'
            ]
        ];

        $response->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $expectedData));

        $this->clearDb();
    }

    public function test_request_should_fail_when_email_has_more_than_255_characters()
    {
        $data = [
            "email"    => $this->faker->regexify('[A-Za-z0-9]{260}'),
            "login"    => "Bes3t Tester",
            "password" => "12334qwer"
        ];

        $response = $this->postJson(route('auth.registration'), $data);
        $expectedData = [
            'email' => [
                'The email may not be greater than 255 characters.'
            ]
        ];

        $response->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $expectedData));

        $this->clearDb();
    }

    public function test_request_should_fail_when_user_with_provided_email_already_exists()
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


//    LOGIN

    public function test_request_should_fail_when_no_login_is_provided()
    {
        $data = [
            "email"    => "tester@gmail.com",
            "password" => "1234qwer"
        ];

        $response = $this->postJson(route('auth.registration'), $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'status'  => 'fail',
                'message' => 'Incorrect data',
                'data'    => [
                    'login' => [
                        'The login field is required.'
                    ]
                ]
            ]);

        $this->clearDb();
    }

    public function test_request_should_fail_when_login_is_not_string()
    {
        $data = [
            "email"    => "tester@gmail.com",
            "login"    => 3,
            "password" => "1234qwer"
        ];

        $response = $this->postJson(route('auth.registration'), $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'status'  => 'fail',
                'message' => 'Incorrect data',
                'data'    => [
                    'login' => [
                        'The login must be a string.'
                    ]
                ]
            ]);

        $this->clearDb();
    }

    public function test_request_should_fail_when_login_has_more_than_150_characters()
    {
        $data = [
            "email"    => "tester@gmail.com",
            "login"    => $this->faker->regexify('[A-Za-z0-9]{151}'),
            "password" => "12334qwer"
        ];

        $response = $this->postJson(route('auth.registration'), $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'status'  => 'fail',
                'message' => 'Incorrect data',
                'data'    => [
                    'login' => [
                        'The login may not be greater than 150 characters.'
                    ]
                ]
            ]);

        $this->clearDb();
    }

    public function test_request_should_fail_when_user_with_provided_login_already_exists()
    {
        $user = new User();
        $user->login = 'user_0';
        $user->email = 'user_0@gmail.com';
        $user->password = '1234qwer';
        $user->save();

        $data = [
            "email"    => "tester@gmail.com",
            "login"    => $user->login,
            "password" => "1234qwer"
        ];

        $response = $this->postJson(route('auth.registration'), $data);

        $response->assertStatus(403)
            ->assertExactJson([
                'status'  => 'fail',
                'message' => 'Incorrect data',
                'data'    => [
                    'login' => [
                        'The login has already been taken.'
                    ]
                ]
            ]);

        $this->clearDb();
    }

}
