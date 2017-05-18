<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToReviews extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('reviews', function (Blueprint $table) {
      $table->foreign('user_id')->references('id')->on('users');
      $table->foreign('offer_id')->references('id')->on('offers');
      $table->foreign('booking_id')->references('id')->on('bookings');

    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('reviews', function (Blueprint $table) {
      $table->dropForeign('reviews_user_id_foreign');
      $table->dropForeign('reviews_offer_id_foreign');
      $table->dropForeign('reviews_booking_id_foreign');

    });
  }
}
