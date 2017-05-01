<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('user_id'); //driver's id
            $table->datetime('meetup_time');
            $table->text('start_name');
            $table->text('start_addr');
            $table->double('start_lat'); 
            $table->double('start_lng');
            $table->text('end_name');
            $table->text('end_addr');
            $table->double('end_lat');
            $table->double('end_lng');
            $table->integer('vacancy'); //number of available spots
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
