<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('offers', function (Blueprint $table) {
      $table->increments('id');
      $table->char('user_id', 32); //driver's id

      $table->datetime('meetup_time');

      $table->string('start_name');
      $table->string('start_addr');
      $table->double('start_lat', 10, 6);
      $table->double('start_lng', 10, 6);
      $table->string('start_geohash');

      $table->string('end_name');
      $table->string('end_addr');
      $table->double('end_lat', 10, 6);
      $table->double('end_lng', 10, 6);
      $table->string('end_geohash');

      $table->integer('vacancy'); //number of available spots
      $table->integer('status')->default(1); //still ongoing?
      $table->string('pref_gender')->nullable();
      $table->string('remarks')->nullable();
      $table->string('vehicle_number');
      $table->string('vehicle_desc')->nullable();
      $table->string('vehicle_model');

      $table->softDeletes();
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
    //
  }
}
