<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the Migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shelf_id')->nullable();
            $table->unsignedInteger('author_id');
            $table->string('isbn');
            $table->string('title');
            $table->text('text');

            $table->foreign('shelf_id')->references('id')->on('shelves');
            $table->foreign('author_id')->references('id')->on('authors');
        });
    }

    /**
     * Reverse the Migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('books');
    }
}
