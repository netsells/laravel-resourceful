<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGenresTable extends Migration
{
    /**
     * Run the Migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('fiction');
            $table->string('name');
        });

        DB::table('genres')->insert([
            ['fiction' => false, 'name' => 'Art'],
            ['fiction' => false, 'name' => 'Biography'],
            ['fiction' => false, 'name' => 'Cookbook'],
            ['fiction' => false, 'name' => 'Guide'],
            ['fiction' => false, 'name' => 'History'],
            ['fiction' => false, 'name' => 'Memoir'],
            ['fiction' => true, 'name' => 'Crime'],
            ['fiction' => true, 'name' => 'Fantasy'],
            ['fiction' => true, 'name' => 'Horror'],
            ['fiction' => true, 'name' => 'Poetry'],
            ['fiction' => true, 'name' => 'Thriller'],
            ['fiction' => true, 'name' => 'Romance'],
        ]);
    }

    /**
     * Reverse the Migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('genres');
    }
}
