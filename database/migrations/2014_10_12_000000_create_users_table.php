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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->default('unregistered_user.png');
            $table->string('telefon')->nullable();
            $table->string('status')->default(true);
            $table->string('role')->default('client');
            $table->integer('last_thread_id');
            $table->integer('balance')->default(5555);
            $table->string('deleted_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
//insert into users(name, email, email_verified_at, password, image, telefon, status, remember_token, created_at, updated_at) values ('Ernst Müller', 'emuell@muell.er', '2022-30-10 01:00:00', 'password', '1.jpg', '026/754 51 55', 'user', 'token1', '2022-30-10 00:00:00', '2022-30-10 00:00:00');
