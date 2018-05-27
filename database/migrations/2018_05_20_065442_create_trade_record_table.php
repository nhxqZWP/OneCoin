<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradeRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_record', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('use');
            $table->unsignedBigInteger('amount');
            $table->unsignedTinyInteger('type');  //0-buy 1-sell
            $table->unsignedTinyInteger('profit');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default('');

            $table->index('type');
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
