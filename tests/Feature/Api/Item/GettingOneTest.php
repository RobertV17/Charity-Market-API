<?php

namespace Tests\Feature\Api\Item;

use Modules\Item\Models\Item;

/**
 * Class GettingOneTest
 * @package Tests\Feature\Api\Item
 */
class GettingOneTest extends ItemTestCase
{
    private $httpAuthHeaderWithToken;

    protected function setUp(): void
    {
        parent::setUp();
        $user = $this->createFakeUser();
        $authToken = $this->createAuthTokenForUser($user);
        $this->httpAuthHeaderWithToken = ['Authorization' => 'Bearer '.$authToken];
    }

    /** @test */
    public function request_schould_success_when_item_exists_and_id_valid(): void
    {
        $this->createItems(1);
        $item = Item::all()->first();

        $route = route('items.show', $item->id);

        $expectedDate = [
            'item' => $item->toArray()
        ];

        $this->getJson($route, $this->httpAuthHeaderWithToken)
            ->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse(null, $expectedDate));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_auth_token_no_provided(): void
    {
        $this->createItems(1);
        $item = Item::all()->first();

        $this->getJson(route('items.show', ['id' => $item->id]))
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_item_not_founded(): void
    {
        $this->createItems(1);
        $item = Item::all()->first();

        $route = route('items.show', $item->id + 3);

        $this->getJson($route, $this->httpAuthHeaderWithToken)
            ->assertStatus(404)
            ->assertExactJson($this->getFailResponse('Item not founded'));

        $this->clearDb();
    }

    /** @test */
    public function request_should_fail_when_item_id_not_valid(): void
    {
        $this->createItems(1);

        $route = route('items.show', 'qwerty');

        $this->getJson($route, $this->httpAuthHeaderWithToken)
            ->assertStatus(404)
            ->assertExactJson($this->getFailResponse('Item not founded'));

        $this->clearDb();
    }
}
