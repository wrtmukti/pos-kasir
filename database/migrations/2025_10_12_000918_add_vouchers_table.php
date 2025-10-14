<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code');
            $table->integer('voucher_type'); // 0 percent 1 potongan
            $table->integer('value');
            $table->integer('status'); // 0 non aktif 1 potongan
            $table->integer('balance')->nullable();
            $table->dateTime('starttime')->nullable();
            $table->dateTime('endtime')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
