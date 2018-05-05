<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('songs', function (Blueprint $table) {
          $table->engine = 'InnoDB';
          $table->increments('id');
          $table->uuid('uuid')->nullable();
          $table->integer('user_id')->unsigned();
          $table->integer('genre_id')->unsigned()->nullable();
          $table->string('artist')->nullable();
          $table->string('title')->nullable();
          $table->string('slug')->unique();
          $table->string('spotify')->nullable();
          $table->string('playback')->nullable();
          $table->string('image')->nullable();
          $table->string('key')->nullable();
          $table->string('time')->nullable();
          $table->integer('tempo')->unsigned()->nullable();
          $table->integer('duration')->unsigned()->nullable();
          $table->string('flow')->nullable();
          $table->text('chords')->nullable();
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs');
    }
}
