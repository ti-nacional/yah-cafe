<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identity')->nullable();
            $table->string('name')->nullable();
            $table->string('symbol')->nullable();
            $table->string('rank')->nullable();
            $table->string('price_usd')->nullable();
            $table->string('last_price_usd')->nullable();
            $table->string('price_btc')->nullable();
            $table->string('volume_usd_24h')->nullable();
            $table->string('market_cap_usd')->nullable();
            $table->string('available_supply')->nullable();
            $table->string('total_supply')->nullable();
            $table->string('percent_change_1h')->nullable();
            $table->string('percent_change_24h')->nullable();
            $table->string('percent_change_7d')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coins');
    }
}
