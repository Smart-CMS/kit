<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(config('kit.admins_table_name'), function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique()->index();
            $table->string('password');
            $table->string('telegram_id')->nullable();
            $table->json('notifications')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('kit.admins_table_name'));
    }
};
