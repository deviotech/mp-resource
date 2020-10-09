<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeOrderIdUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_statuses', function (Blueprint $table) {
            $table->string('order_id')->unique()->nullable()->change();
            $table->enum('status', ['paid', 'pending'])->default('pending')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_statuses', function (Blueprint $table) {
            //
        });
    }
}
