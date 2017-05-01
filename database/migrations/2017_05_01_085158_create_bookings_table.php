<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
        $table->integer('user_id'); //who accepted the offer
        $table->integer('offer_id'); //which driver's offer
        $table->integer('status'); //still ongoing?
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
