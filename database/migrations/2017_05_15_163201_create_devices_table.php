<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('devices', function (Blueprint $table) {
      $table->increments('id');
      $table->char('user_id', 32); 
      
      $table->string('platform');
      $table->string('device_token')->unique();
     
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
