<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('payment_status')->nullable(); //income = 0 , outcome = 1
            $table->integer('total_price')->default(0);
            $table->integer('cash')->default(0); //cash = 0 , debit = 1
            $table->integer('debit')->default(0); //cash = 0 , debit = 1
            $table->integer('kembalian')->default(0)->nullable(); //cash = 0 , debit = 1
            $table->text('note')->nullable();
            $table->timestamps();
            // $table->unsignedBigInteger('summary_id')->nullable();
            // $table->foreign('summary_id')->references('id')->on('summaries')->onDelete('cascade');
        });
    }


    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
