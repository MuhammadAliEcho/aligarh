<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('mail')->default(0);
            $table->boolean('sms')->default(0);
            $table->boolean('whatsapp')->default(0);
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
        Schema::dropIfExists('notifications_settings');
    }
}
