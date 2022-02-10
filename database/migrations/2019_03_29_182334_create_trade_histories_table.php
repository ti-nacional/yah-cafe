<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('type'); // buy || sell ...
            $table->string('currency_from', 3);
            $table->string('currency_to', 3);
            $table->decimal('amount_from',15,8);
            $table->decimal('amount_to',15,8);
            $table->decimal('price_from',15,2);
            $table->decimal('price_to',15,2);
            $table->enum('status', array(0, 1))->default(1);
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
        Schema::dropIfExists('trades_history');
    }
}
