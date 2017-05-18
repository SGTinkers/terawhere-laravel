<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToBookings extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('bookings', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('offer_id')->references('id')->on('offers');

    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('bookings', function (Blueprint $table) {
      $table->dropForeign('bookings_user_id_foreign');
      $table->dropForeign('bookings_offer_id_foreign');

    });
  }
}
