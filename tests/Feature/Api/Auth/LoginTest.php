<?php


namespace Tests\Feature\Api\Auth;

use Tests\BaseTest;

class LoginTest extends BaseTest
{
    /** @test */
    public function login_user_wiht_valid_data()
    {
        // 1) Проверить что респонс, что создался новый 2о1 валидный токен
        $user = $this->createFakeUser();

        $data = [
            "email"    => $user->email,
//            @todo-robert мне не нравится что пароль для  fake юзера указывается так
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


        $this->clearDb();
    }
}
