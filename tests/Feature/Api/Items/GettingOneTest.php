<?php


namespace Tests\Feature\Api\Items;

use Illuminate\Routing\Middleware\ThrottleRequests;
use Modules\Item\Models\Item;
use \Tests\BaseTest;

class GettingOneTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    /** @test */
    public function request_schould_success_when_item_exists_and_id_valid()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $this->createItems(1);
        $item = Item::all()->first();

        $route = route('items.show', $item->id);
        $headers = ['Authorization' => 'Bearer '.$token];

        $expectedDate = [
            'item' => $item->toArray()
        ];

        $this->getJson($route, $headers)
            ->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse(null, $expectedDate));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_auth_token_no_provided()
    {
        $this->createItems(1);
        $item = Item::all()->first();

        $this->getJson(route('items.show', ['id' => $item->id]))
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_item_not_founded()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $this->createItems(1);
        $item = Item::all()->first();

        $route = route('items.show', $item->id + 3);
        $headers = ['Authorization' => 'Bearer '.$token];

        $this->getJson($route, $headers)
            ->assertStatus(404)
            ->assertExactJson($this->getFailResponse('Item not founded'));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_item_id_not_valid()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $this->createItems(1);

        $route = route('items.show', 'qwerty');
        $headers = ['Authorization' => 'Bearer '.$token];

        $this->getJson($route, $headers)
            ->assertStatus(404)
            ->assertExactJson($this->getFailResponse('Item not founded'));

        $this->clearDb();
    }
}
