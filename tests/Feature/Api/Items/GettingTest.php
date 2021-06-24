<?php


namespace Tests\Feature\Api\Items;

use Modules\Item\Models\Category;
use Modules\Item\Models\Item;
use Modules\User\Models\User;
use Tests\BaseTest;

class GettingTest extends BaseTest
{
    /** @test */
    public function request_get_all_items_should_fail_when_auth_token_no_provided()
    {
        $this->getJson(route('items.all'))
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_auth_token_is_wrong()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $this->getJson(route('items.all'), ['Authorization' => 'Bearer wrong1'.$token])
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
    }

    /** @test */
    public function request_get_all_items_should_success()
    {
        // todo-robert требует доработок
//      Items Factory
        User::factory()->count(5)->create();
        Category::factory()->count(5)->create();
        Item::factory()->count(100)->create();

        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $data = [
            'Authorization' => 'Bearer '.$token,
            'page' => 1
        ];

        $this->getJson(route('items.all'), $data)
            ->assertStatus(200);

        $this->clearDb();
    }
}
