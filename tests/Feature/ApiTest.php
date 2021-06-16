<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    public function test_user_registration()
    {
        $data = [
            "email" => "tester@gmail.com",
            "login" => "Best Tester",
            "password" => "qwer1234"
        ];


        $this->withoutExceptionHandling();
        $this->post(route('auth.registration'), $data)->assertStatus(200);

    }
}
