<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_master', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('student_id');
            $table->string('gr_no', 128);
            $table->date('payment_month');
            $table->integer('discount')->nullable();
            $table->integer('paid_amount');
            $table->string('payment_type', 16);
            $table->integer('total_amount');
            $table->integer('net_amount');
            $table->integer('late_fee');
            $table->string('chalan_no', 32)->nullable();
            $table->date('due_date');
            $table->date('date');
            $table->date('date_of_payment');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('invoice_master');
    }
};
