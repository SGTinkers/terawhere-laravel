<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('users', function (Blueprint $table) {
      $table->char('id', 32);
      $table->string('name');
      $table->string('email')->unique()->nullable();
      $table->string('password');
      $table->string('dp')->nullable();
      $table->string('facebook_id')->unique()->nullable();
      $table->string('google_id')->unique()->nullable();
      $table->string('gender')->nullable();
      $table->integer('exp');
      $table->string('timezone')->default('Asia/Singapore');
      $table->rememberToken();
      $table->timestamps();
      $table->primary('id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('users');
  }
}
