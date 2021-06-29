<?php


namespace Tests\Feature\Api\Items;

use Illuminate\Routing\Middleware\ThrottleRequests;
use Modules\Item\Models\Item;
use Tests\BaseTest;

class GettingAllTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    /** @test */
    public function request_should_success()
    {
        $this->createItems(25);
        $expectedData = Item::all()->take(10)->toArray();

        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $route = route('items.all',['page' => 1]);
        $headers = ['Authorization' => 'Bearer '.$token];

        $this->getJson($route, $headers)
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
    public function request_should_success_when_non_existent_page_provided()
    {
        $this->createItems(25);

        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $route = route('items.all', ['page' => 120]);
        $headers = ['Authorization' => 'Bearer '.$token];

        $this->getJson($route, $headers)
            ->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse(null, [
                'current_page'   => 120,
                'data'           => [],
                'first_page_url' => route('items.all', ['page' => 1]),
                'from'           => null,
                'last_page'      => 3,
                'last_page_url'  => route('items.all', ['page' => 3]),
                'links'          => [
                    [
                        'url'    => route('items.all', ['page' => 119]),
                        'label'  => '&laquo; Previous',
                        'active' => false
                    ],
                    [
                        'url'    => route('items.all', ['page' => 1]),
                        'label'  => 1,
                        'active' => false
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
                        'url'    => null,
                        'label'  => 'Next &raquo;',
                        'active' => false
                    ]
                ],
                'next_page_url'  => null,
                'path'           => route('items.all'),
                'per_page'       => 10,
                'prev_page_url'  => route('items.all', ['page' => 119]),
                'to'             => null,
                'total'          => 25
            ]));

        $this->clearDb();
    }

    /**
     * Если не получилось распознать указанную в запросе страницу(page),
     * то дефолтный пагинатор Laravel использует 1ую страницу
     * @test
     */
    public function request_should_success_when_page_in_incorrect_form_provided()
    {
        $this->createItems(25);
        $expectedData = Item::all()->take(10)->toArray();

        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $route = route('items.all', ['page' => 'page1or2maybe3']);
        $headers = ['Authorization' => 'Bearer '.$token];

        $this->getJson($route, $headers)
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

    /**
     * Если не получилось распознать указанную в запросе страницу(page),
     * то дефолтный пагинатор Laravel использует 1ую страницу
     * @test
     */
    public function request_should_success_when_page_no_provided()
    {
        $this->createItems(25);
        $expectedData = Item::all()->take(10)->toArray();

        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $headers = ['Authorization' => 'Bearer '.$token];

        $this->getJson(route('items.all'), $headers)
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
    public function request_should_success_when_no_items_in_db()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $headers = ['Authorization' => 'Bearer '.$token];
        $route = route('items.all', ['page' => 1]);

        $this->getJson($route, $headers)
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

    /** @test */
    public function request_should_success_when_non_existent_page_provided_and_no_items_in_db()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $route = route('items.all', ['page' => 120]);
        $headers = ['Authorization' => 'Bearer '.$token];

        $this->getJson($route, $headers)
            ->assertStatus(200)
            ->assertExactJson($this->getSuccessResponse(null, [
                'current_page'   => 120,
                'data'           => [],
                'first_page_url' => route('items.all', ['page' => 1]),
                'from'           => null,
                'last_page'      => 1,
                'last_page_url'  => route('items.all', ['page' => 1]),
                'links'          => [
                    [
                        'url'    => route('items.all', ['page' => 119]),
                        'label'  => '&laquo; Previous',
                        'active' => false
                    ],
                    [
                        'url'    => route('items.all', ['page' => 1]),
                        'label'  => 1,
                        'active' => false
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
                'prev_page_url'  => route('items.all', ['page' => 119]),
                'to'             => null,
                'total'          => 0
            ]));

        $this->clearDb();
    }

    /**
     * Если не получилось распознать указанную в запросе страницу(page),
     * то дефолтный пагинатор Laravel использует 1ую страницу
     * @test
     */
    public function request_should_success_when_page_in_incorrect_form_provided_and_no_items_in_db()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $route = route('items.all', ['page' => 'page1or2maybe3']);
        $headers = ['Authorization' => 'Bearer '.$token];

        $this->getJson($route, $headers)
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

    /**
     * Если не получилось распознать указанную в запросе страницу(page),
     * то дефолтный пагинатор Laravel использует 1ую страницу
     * @test
     */
    public function request_should_success_when_page_no_provided_and_no_items_in_db()
    {
        $user = $this->createFakeUser();
        $token = $this->createAuthTokenForUser($user);

        $headers = ['Authorization' => 'Bearer '.$token];

        $this->getJson(route('items.all'), $headers)
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

    /** @test */
    public function request_should_fail_when_auth_token_no_provided()
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
}
