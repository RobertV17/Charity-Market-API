<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->string('title',100)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category');
    }
}
