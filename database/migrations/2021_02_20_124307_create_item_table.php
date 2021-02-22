<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTable extends Migration
{
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->string('title',100)->unique();
            $table->string('desc',255);
            $table->decimal('price', $precision = 10, $scale = 2);
            $table->string('photo_url',255)->unique();
            $table->unsignedBigInteger('cat_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::table('item', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cat_id')->references('id')->on('category')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('item', function (Blueprint $table) {
            $table->dropForeign(['user_id'],['cat_id']);
        });

        Schema::dropIfExists('item');
    }
}
