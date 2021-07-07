<?php

namespace Tests\Feature\Api\Item;

use Modules\Item\Models\Item;

/**
 * Class DeletionTest
 * @package Tests\Feature\Api\Item
 */
class DeletionTest extends ItemTestCase
{
    private $existingItem;
    private $httpAuthHeaderWithToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createItems(1);
        $item = $this->existingItem = Item::all()->first();

        $creator = $item->user;
        $authToken = $this->createAuthTokenForUser($creator);

        $this->existingItem = $item;
        $this->httpAuthHeaderWithToken = ['Authorization' => 'Bearer '.$authToken];
    }

    /** @test */
    public function request_should_success_when_data_is_valid(): void
    {
        $route = route('items.drop', $this->existingItem->id);
        $this->deleteJson($route, [], $this->httpAuthHeaderWithToken)
            ->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse('Item was deleted'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_no_auth_token_provided(): void
    {
        $route = route('items.drop', $this->existingItem->id);

        $this->deleteJson($route, [])
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_wrong_auth_token_provided(): void
    {
        $route = route('items.drop', $this->existingItem->id);

        $wrongHttpAuthHeaderWithToken = [
            'Authorization' => $this->httpAuthHeaderWithToken['Authorization'].'1'
        ];

        $this->deleteJson($route, [], $wrongHttpAuthHeaderWithToken)
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_user_is_not_the_seller_of_this_item(): void
    {
        $newUser = $this->createFakeUser();
        $newUserAuthToken = $this->createAuthTokenForUser($newUser);

        $httpAuthHeaderWhitToken = ['Authorization' => 'Bearer '.$newUserAuthToken];

        $route = route('items.drop', $this->existingItem->id);
        $this->deleteJson($route, [], $httpAuthHeaderWhitToken)
            ->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Access denied'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_specified_item_not_found(): void
    {
        $route = route('items.drop', $this->existingItem->id + 3);

        $this->deleteJson($route, [], $this->httpAuthHeaderWithToken)
            ->assertStatus(404)
            ->assertExactJson($this->getFailResponse('Item not founded'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_specified_item_id_in_url_not_valid(): void
    {
        $route = route('items.drop', 'item1');

        $this->deleteJson($route, [], $this->httpAuthHeaderWithToken)
            ->assertStatus(404)
            ->assertExactJson($this->getFailResponse('Item not founded'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }
}
