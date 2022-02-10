<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CoinsWallets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
       public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('BTC'); 
            $table->string('ETH'); 
            $table->string('XRP'); 
            $table->string('LTC'); 
            $table->string('XMR'); 
            $table->string('MIOTA'); 
            $table->string('DASH'); 
            $table->string('NEO'); 
            $table->string('TUSD'); 
            $table->string('USDT'); 

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
        Schema::dropIfExists('wallets_users');
    }
}
