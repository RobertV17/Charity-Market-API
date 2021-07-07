<?php

namespace Tests\Feature\Api\Item;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Modules\Item\Models\Category;
use Modules\Item\Models\Item;

/**
 * Class UpdatingTest
 * @package Tests\Feature\Api\Item
 */
class UpdatingTest extends ItemTestCase
{
    private $existingItem;
    private $newCategory;
    private $newPreviewPhoto;
    private $httpAuthHeaderWithToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createItems(1);
        $item = $this->existingItem = Item::all()->first();

        $creator = $item->user;
        $authToken = $this->createAuthTokenForUser($creator);

        $this->existingItem = $item;
        $this->newPreviewPhoto = UploadedFile::fake()->image('photo1.jpg')->size(1000);
        $this->newCategory = Category::factory()->create();
        $this->httpAuthHeaderWithToken = ['Authorization' => 'Bearer '.$authToken];
    }

    /** @test */
    public function request_should_success_when_all_fields_exists_and_valid(): void
    {
        $data = [
            'title'  => 'New item title!',
            'cat_id' => $this->newCategory->id,
            'desc'   => 'Cool item 10/10.(edited)',
            'price'  => 9999.34,
            'photo'  => $this->newPreviewPhoto,
        ];

        $route = route('items.update', $this->existingItem->id);
        $response = $this->postJson($route, $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(200);

        $expectedData = Item::all()->first()->toArray();

        $response->assertExactJson($this->getSuccessResponse('Item was updated', [
            'item' => $expectedData
        ]));

        // todo-robert также добавить проверку валидности ссылки на фото
        $filePath = public_path('images/items/preview/').basename($expectedData['photo_url']);
        $this->assertFileExists($filePath);

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @dataProvider */
    public function validDataForPartialUpdateProvider(): array
    {
        return [
            "only_title_provided" => [
                [
                    'title' => 'Cool new item 10/10 (edited).'
                ]
            ],
            "only_desc_provided"  => [
                [
                    'desc' => 'Desc of coolest new item 10/10 (edited).'
                ]
            ],
            "only_price_provided" => [
                [
                    'price' => 9999.32
                ]
            ]
        ];
    }

    /**
     * @dataProvider validDataForPartialUpdateProvider
     * @test
     */
    public function request_should_success_when($data): void
    {
        $route = route('items.update', $this->existingItem->id);
        $response = $this->postJson($route, $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(200);

        $expectedData = Item::all()->first()->toArray();

        $response->assertExactJson($this->getSuccessResponse('Item was updated', [
            'item' => $expectedData
        ]));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_success_when_only_valid_category_id_provided(): void
    {
        $data = [
            'cat_id' => $this->newCategory->id
        ];

        $route = route('items.update', $this->existingItem->id);
        $response = $this->postJson($route, $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(200);

        $expectedData = Item::all()->first()->toArray();

        $response->assertExactJson($this->getSuccessResponse('Item was updated', [
            'item' => $expectedData
        ]));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_success_when_only_photo_provided(): void
    {
        $data = [
            'photo' => $this->newPreviewPhoto
        ];

        $route = route('items.update', $this->existingItem->id);
        $response = $this->postJson($route, $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(200);

        $expectedData = Item::all()->first()->toArray();

        $response->assertExactJson($this->getSuccessResponse('Item was updated', [
            'item' => $expectedData
        ]));

        // todo-robert также добавить проверку валидности ссылки на фото
        $filePath = public_path('images/items/preview/').basename($expectedData['photo_url']);
        $this->assertFileExists($filePath);

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_no_auth_token_provided(): void
    {
        $data = [
            'title'  => 'New item title!',
            'cat_id' => $this->newCategory->id,
            'desc'   => 'Cool item 10/10.(edited)',
            'price'  => 9999.34,
            'photo'  => $this->newPreviewPhoto,
        ];

        $route = route('items.update', $this->existingItem->id);

        $this->postJson($route, $data)
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_wrong_auth_token_provided(): void
    {
        $data = [
            'title'  => 'New item title!',
            'cat_id' => $this->newCategory->id,
            'desc'   => 'Cool item 10/10.(edited)',
            'price'  => 9999.34,
            'photo'  => $this->newPreviewPhoto,
        ];

        $route = route('items.update', $this->existingItem->id);

        $wrongHttpAuthHeaderWithToken = [
            'Authorization' => $this->httpAuthHeaderWithToken['Authorization'].'1'
        ];

        $this->postJson($route, $data, $wrongHttpAuthHeaderWithToken)
            ->assertStatus(401)
            ->assertExactJson($this->getFailResponse('Unauthenticated.'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_request_body_is_empty(): void
    {
        $data = [];

        $route = route('items.update', $this->existingItem->id);
        $this->postJson($route, $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Request body is empty!'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_user_is_not_the_seller_of_this_item(): void
    {
        $data = [
            'title'  => 'New item title!',
            'cat_id' => $this->newCategory->id,
            'desc'   => 'Cool item 10/10.(edited)',
            'price'  => 9999.34,
            'photo'  => $this->newPreviewPhoto,
        ];

        $newUser = $this->createFakeUser();
        $newUserAuthToken = $this->createAuthTokenForUser($newUser);

        $httpAuthHeaderWhitToken = ['Authorization' => 'Bearer '.$newUserAuthToken];

        $route = route('items.update', $this->existingItem->id);
        $this->postJson($route, $data, $httpAuthHeaderWhitToken)
            ->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Access denied'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_specified_item_not_found(): void
    {
        $data = [
            'title'  => 'New item title!',
            'cat_id' => $this->newCategory->id,
            'desc'   => 'Cool item 10/10.(edited)',
            'price'  => 9999.34,
            'photo'  => $this->newPreviewPhoto,
        ];

        $route = route('items.update', $this->existingItem->id + 3);

        $this->postJson($route, $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(404)
            ->assertExactJson($this->getFailResponse('Item not founded'));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @dataProvider */
    public function wrongRequestDataProvider(): array
    {
        return [
            // title
            "title_is_not_string"                         => [
                [
                    'title' => 1
                ],
                ['title' => ['The title must be a string.']]
            ],
            "title_has_more_than_100_characters"          => [
                [
                    'title' => Str::random(101)
                ],
                ['title' => ['The title may not be greater than 100 characters.']]
            ],
            // desc
            "desc_is_not_string"                          => [
                [
                    'desc' => 1
                ],
                ['desc' => ['The desc must be a string.']]
            ],
            "desc_has_more_than_255_characters"           => [
                [
                    'desc' => Str::random(256)
                ],
                ['desc' => ['The desc may not be greater than 255 characters.']]
            ],
            // price
            "price_is_not_numeric_case_1"                 => [
                [
                    'price' => 'nice_price'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_numeric_case_2"                 => [
                [
                    'price' => '23.34nice_price'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_numeric_case_3"                 => [
                [
                    'price' => 'nice_price23.34'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_valid_format_case_1"            => [
                [
                    'price' => '100'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_valid_format_case_2"            => [
                [
                    'price' => '100.1'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_valid_format_case_3"            => [
                [
                    'price' => '100.111'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_valid_format_case_4"            => [
                [
                    'price' => '100,11'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_included_in_price_range_case_1" => [
                [
                    'price' => '-23.24'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_included_in_price_range_case_2" => [
                [
                    'price' => '0'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_included_in_price_range_case_3" => [
                [
                    'price' => '0.99'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_included_in_price_range_case_4" => [
                [
                    'price' => '10000.99'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            // cat_id
            "cat_id_is_not_integer_case_1"                => [
                [
                    'cat_id' => 'Cars'
                ],
                ['cat_id' => ['The cat id must be an integer.']]
            ],
            "cat_id_is_not_integer_case_2"                => [
                [
                    'cat_id' => '20.3'
                ],
                ['cat_id' => ['The cat id must be an integer.']]
            ],
            "cat_id_is_not_integer_case_3"                => [
                [
                    'cat_id' => '20,3'
                ],
                ['cat_id' => ['The cat id must be an integer.']]
            ],
            // photo
            "preview_photo_is_not_image_case_1"           => [
                [
                    'photo' => 'niece photo'
                ],
                ['photo' => ['The photo must be an image.']]
            ],
            "preview_photo_is_not_image_case_2"           => [
                [
                    'photo' => UploadedFile::fake()->create('document.pdf', 1000)
                ],
                ['photo' => ['The photo must be an image.']]
            ],
            "preview_photo_file_size_exceeds_the_maximum" => [
                [
                    'photo' => UploadedFile::fake()->image('item.jpg')->size(10001)
                ],
                ['photo' => ['The photo may not be greater than 10000 kilobytes.']]
            ]
        ];
    }

    /**
     * @dataProvider wrongRequestDataProvider
     * @test
     */
    public function request_should_fail_when($data, $expectedValidationErrors): void
    {
        $route = route('items.update', $this->existingItem->id);
        $this->postJson($route, $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $expectedValidationErrors));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_item_with_specific_title_already_exist(): void
    {
        $newItem = Item::factory()->count(1)->create()[0];

        $data = [
            'title' => $newItem->title
        ];

        $route = route('items.update', $this->existingItem->id);
        $this->postJson($route, $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', [
                'title' => ['The title has already been taken.']
            ]));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_category_id_provided_is_not_exists(): void
    {
        $data = [
            'cat_id' => $this->newCategory->id + 2
        ];

        $route = route('items.update', $this->existingItem->id);
        $this->postJson($route, $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', [
                'cat_id' => ['The selected cat id is invalid.']
            ]));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }
}
