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
        // todo-robert надо бы вынести это куда-то
        User::factory()->count(5)->create();
        Category::factory()->count(5)->create();
        Item::factory()->count(25)->create();

        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $data = [
            'Authorization' => 'Bearer '.$token,
            'page'          => 1
        ];

        $expectedData = Item::all()->take(10)->toArray();

        $this->getJson(route('items.all'), $data)
            ->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse(null, [
                'current_page'   => 1,
                'data'           => $expectedData,
                'first_page_url' => route('items.all', ['page' => 1]),
                'from'           => 1,
                'last_page'      => 3,
                'last_page_url'  => route('items.all', ['page' => 3]),
                'links'          => [
                    [
                        'url'    => null,
                        'label'  => '&laquo; Previous',
                        'active' => false
                    ],
                    [
                        'url'    => route('items.all', ['page' => 1]),
                        'label'  => 1,
                        'active' => true
                    ],
                    [
                        'url'    => route('items.all', ['page' => 2]),
                        'label'  => 2,
                        'active' => false
                    ],
                    [
                        'url'    => route('items.all', ['page' => 3]),
                        'label'  => 3,
                        'active' => false
                    ],
                    [
                        'url'    => route('items.all', ['page' => 2]),
                        'label'  => 'Next &raquo;',
                        'active' => false
                    ]
                ],
                'next_page_url'  => route('items.all', ['page' => 2]),
                'path'           => route('items.all'),
                'per_page'       => 10,
                'prev_page_url'  => null,
                'to'             => 10,
                'total'          => 25
            ]));

        $this->clearDb();
    }

    /** @test */
    public function request_get_all_items_should_success_when_no_items_in_db()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $data = [
            'Authorization' => 'Bearer '.$token,
            'page'          => 1
        ];

        $this->getJson(route('items.all'), $data)
            ->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse(null, [
                'current_page'   => 1,
                'data'           => [],
                'first_page_url' => route('items.all', ['page' => 1]),
                'from'           => null,
                'last_page'      => 1,
                'last_page_url'  => route('items.all', ['page' => 1]),
                'links'          => [
                    [
                        'url'    => null,
                        'label'  => '&laquo; Previous',
                        'active' => false
                    ],
                    [
                        'url'    => route('items.all', ['page' => 1]),
                        'label'  => 1,
                        'active' => true
                    ],
                    [
                        'url'    => null,
                        'label'  => 'Next &raquo;',
                        'active' => false
                    ]
                ],
                'next_page_url'  => null,
                'path'           => route('items.all'),
                'per_page'       => 10,
                'prev_page_url'  => null,
                'to'             => null,
                'total'          => 0
            ]));

        $this->clearDb();
    }
}
