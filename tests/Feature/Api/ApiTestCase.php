<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tests\TestCase;

/**
 * Class BaseTest
 * @package Tests
 */
class ApiTestCase extends TestCase
{
    use WithFaker;

    private $tableForCleaning = [
        'category',
        'personal_access_tokens',
        'users',
        'item'
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    protected function clearDb(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach($this->tableForCleaning as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * @param $message
     * @param  array|null  $data
     *
     * @return array
     */
    public function getSuccessResponse($message = null, array $data = null): array
    {
        return [
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ];
    }

    /**
     * @param $message
     * @param  array|null  $data
     *
     * @return array
     */
    public function getFailResponse($message, array $data = null): array
    {
        return [
            'status'  => 'fail',
            'message' => $message,
            'data'    => $data
        ];
    }

    /**
     * @param  string  $password
     *
     * @return User
     */
    protected function createFakeUser(string $password = '1234qwer'): User
    {
        $user = new User();
        $user->login = 'user_0';
        $user->email = 'user_0@gmail.com';
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }

    /**
     * @param  User  $user
     *
     * @return string
     */
    protected function createAuthTokenForUser(User $user): string
    {
        return $user->createToken($user->login)->plainTextToken;
    }
}
