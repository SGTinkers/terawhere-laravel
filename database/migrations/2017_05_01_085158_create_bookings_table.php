<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('bookings', function (Blueprint $table) {

      $table->increments('id');
      $table->integer('user_id'); //who is booking the offer
      $table->integer('offer_id'); //which driver's offer
      $table->integer('status')->default(1); //still ongoing?
      $table->text('driver_remarks'); //remarks by driver to passenger
      $table->integer('rating'); //rating 1-5 of the passenger
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
