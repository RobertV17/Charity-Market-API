<?php


namespace Tests;


use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

/**
 * Class BaseTest
 * @package Tests
 */
class BaseTest extends TestCase
{
    use WithFaker;

    /**
     *
     */
    protected function clearDb(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('category')->truncate();
        DB::table('personal_access_tokens')->truncate();
        DB::table('users')->truncate();
        DB::table('item')->truncate();

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

    /**
     * @param $user
     *
     * @return bool
     */
    protected function checkExistsAuthTokenByUser($user): bool
    {
        return DB::table('personal_access_tokens')
            ->where('tokenable_id', $user->id)
            ->exists();
    }
}
