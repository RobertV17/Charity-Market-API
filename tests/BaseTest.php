<?php


namespace Tests;


use Illuminate\Support\Facades\DB;

/**
 * Class BaseTest
 * @package Tests
 */
class BaseTest extends TestCase
{
    protected function clearDb(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('category')->truncate();
        DB::table('personal_access_tokens')->truncate();
        DB::table('users')->truncate();
        DB::table('item')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
