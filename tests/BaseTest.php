<?php


namespace Tests;


use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
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

    //        $this->withoutExceptionHandling();

    /**
     * @param $message
     * @param  array  $data
     *
     * @return array
     */
    public function getSuccessResponse($message, $data = [])
    {
        return [
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ];
    }

    /**
     * @param $message
     * @param  array  $data
     *
     * @return array
     */
    public function getFailResponse($message, $data = [])
    {
        return [
            'status'  => 'fail',
            'message' => $message,
            'data'    => $data
        ];
    }

    /**
     * @return User
     */
    protected function createFakeUser(): User
    {
        $user = new User();
        $user->login = 'user_0';
        $user->email = 'user_0@gmail.com';
        $user->password = '1234qwer';
        $user->save();

        return $user;
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
