<?php


namespace Tests\Feature\Api\Items;

use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;
use Modules\Item\Models\Category;
use Modules\Item\Models\Item;
use Tests\BaseTest;

class AddingTest extends BaseTest
{
    private $category;
    private $previewPhoto;
    private $httpAuthHeaderWithToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);

        $user = $this->createFakeUser();
        $authToken = $this->createAuthTokenForUser($user);

        $this->previewPhoto = UploadedFile::fake()->image('photo1.jpg')->size(1000);
        $this->category = Category::factory()->create();
        $this->httpAuthHeaderWithToken = ['Authorization' => 'Bearer '.$authToken];
    }

    /** @test */
    public function request_should_success_when_item_data_valid()
    {
        $data = [
            'title'  => 'item 1',
            'cat_id' => $this->category->id,
            'desc'   => 'Cool item 10/10.',
            'price'  => 300.24,
            'photo'  => $this->previewPhoto,
        ];

        $response = $this->postJson(route('items.add'), $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(200);

        $expectedData = Item::all()->first()->toArray();

        $response->assertExactJson($this->getSuccessResponse('Item was added', [
            'item' => $expectedData
        ]));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @dataProvider  */
    public function validationTestsProvider(): array
    {
        return [
            // title
            "no_title_is_provided"                         => [
                [
                    'desc'  => 'Cool item 10/10.',
                    'price' => 300.24
                ],
                ['title' => ['The title field is required.']]
            ],
            "title_is_not_string"                          => [
                [
                    'title' => 1,
                    'desc'  => 'Cool item 10/10.',
                    'price' => 300.24,
                ],
                ['title' => ['The title must be a string.']]
            ],
            "title_has_more_than_100_characters"           => [
                [
                    'title' => Str::random(101),
                    'desc'  => 'Cool item 10/10.',
                    'price' => 300.24,
                ],
                ['title' => ['The title may not be greater than 100 characters.']]
            ],
            // desc
            "no_desc_is_provided"                          => [
                [
                    'title' => 'Item 1',
                    'price' => 300.24
                ],
                ['desc' => ['The desc field is required.']]
            ],
            "desc_is_not_string"                           => [
                [
                    'title' => 'Item 1',
                    'desc'  => 1,
                    'price' => 300.24,
                ],
                ['desc' => ['The desc must be a string.']]
            ],
            "desc_has_more_than_255_characters"            => [
                [
                    'title' => 'Item 1',
                    'desc'  => Str::random(256),
                    'price' => 300.24,
                ],
                ['desc' => ['The desc may not be greater than 255 characters.']]
            ],
            // price
            "no_price_is_provided"                         => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.'
                ],
                ['price' => ['The price field is required.']]
            ],
            "price_is_not_numeric_case_1"                  => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => 'nice_price'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_numeric_case_2"                  => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '23.34nice_price'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_numeric_case_3"                  => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => 'nice_price23.34'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_valid_format_case_1"             => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '100'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_valid_format_case_2"             => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '100.1'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_valid_format_case_3"             => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '100.111'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_valid_format_case_4"             => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '100,11'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_included_in_price_range_case_1"  => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '-23.24'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_included_in_price_range_case_2"  => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '0'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_included_in_price_range_case_3"  => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '0.99'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            "price_is_not_included_in_price_range_case_4"  => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '10000.99'
                ],
                ['price' => ['The price must not exceed 10 000 rub and have the following format: 250.00']]
            ],
            // cat_id
            "no_category_id_is_provided"                   => [
                [
                    'title'  => 'Item 1',
                    'desc'   => 'Cool item 10/10.',
                    'price'  => '250.30',
                    'cat_id' => null
                ],
                ['cat_id' => ['The cat id field is required.']]
            ],
            // photo
            "no_preview_photo_is_provided"                 => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '250.30',
                    'photo' => null
                ],
                ['photo' => ['The photo field is required.']]
            ],
            "preview_photo_is_not_image_case_1"            => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '250.30',
                    'photo' => 'niece photo'
                ],
                ['photo' => ['The photo must be an image.']]
            ],
            "preview_photo_is_not_image_case_2"            => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '250.30',
                    'photo' => UploadedFile::fake()->create('document.pdf', 1000)
                ],
                ['photo' => ['The photo must be an image.']]
            ],
            "preview_photo_file_size_exceeds_the_maximum " => [
                [
                    'title' => 'Item 1',
                    'desc'  => 'Cool item 10/10.',
                    'price' => '250.30',
                    'photo' => UploadedFile::fake()->image('item.jpg')->size(10001)
                ],
                ['photo' => ['The photo may not be greater than 10000 kilobytes.']]
            ]
        ];
    }

    /**
     * @dataProvider validationTestsProvider
     * @test
     */
    public function request_should_fail_when($data, $validationError)
    {
        if ( ! array_key_exists('cat_id', $data)) {
            $data = array_merge($data, ['cat_id' => $this->category->id]);
        }

        if ( ! array_key_exists('photo', $data)) {
            $data = array_merge($data, ['photo' => $this->previewPhoto]);
        }

        $response = $this->postJson(route('items.add'), $data, $this->httpAuthHeaderWithToken);

        $response->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', $validationError));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_item_with_provided_title_already_exists()
    {
        $this->createItems(1);
        $item = Item::all()->first();

        $data = [
            'title'  => $item->title,
            'cat_id' => $this->category->id,
            'desc'   => 'Cool item 10/10.',
            'price'  => 300.24,
            'photo'  => $this->previewPhoto,
        ];

        $this->postJson(route('items.add'), $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', [
                'title' => [
                    'The title has already been taken.'
                ]
            ]));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }

    /** @test */
    public function request_should_fail_when_category_id_provided_is_not_exists()
    {
        $data = [
            'title'  => 'Title 2',
            'cat_id' => $this->category->id + 2,
            'desc'   => 'Cool item 10/10.',
            'price'  => '300.34',
            'photo'  => $this->previewPhoto,
        ];

        $this->postJson(route('items.add'), $data, $this->httpAuthHeaderWithToken)
            ->assertStatus(403)
            ->assertExactJson($this->getFailResponse('Incorrect data', [
                'cat_id' => [
                    'The selected cat id is invalid.'
                ]
            ]));

        $this->clearDb();
        $this->removeStoredItemPreviewImages();
    }
}
