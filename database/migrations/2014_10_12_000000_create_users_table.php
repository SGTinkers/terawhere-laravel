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
      $table->string('email')->unique();
      $table->string('password');
      $table->string('dp');
      $table->string('facebook_id');
      $table->string('google_id');
      $table->integer('gender')->nullable();
      $table->integer('exp');
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
