<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('currency_from');
            $table->decimal('amount_from', 15, 6);
            $table->string('currency_to');
            $table->decimal('amount_to', 15, 6)->default(0);
            $table->string('status', 34)->default('new');
            $table->string('txid_from')->nullable();
            $table->string('txid_to')->nullable();
            $table->string('payin');
            $table->string('payout');
            $table->decimal('east_fee', 15, 2)->default(0);
            $table->decimal('provider_fee', 15, 2)->default(0);
            $table->string('id_provider')->unique();
            $table->string('provider');
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
        Schema::dropIfExists('exchanges');
    }
}
