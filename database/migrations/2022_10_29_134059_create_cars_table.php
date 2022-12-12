<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id');
            $table->string('sign');
            $table->string('manufacturer');
            $table->string('model');
            $table->string('color');
            $table->string('image');
            $table->string('status');
            $table->string('deleted_at')->nullable();
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
        Schema::dropIfExists('car');
    }
};
//insert into users(sign, manufacturer, model, color, image, status, created_at, updated_at) values ('DA X 6568', 'Skoda', 'Fabia', 'silver', 'DA X 6568.png',

// 1 	IN A 6050 	Audi 	A6 	blue 	true 	0000-00-00 00:00:00 	0000-00-00 00:00:00
// 2 	WOB NP 732 	VW 	Polo 	red 	true 	0000-00-00 00:00:00 	0000-00-00 00:00:00
// 3 	GG GT 302 	Opel 	Manta 	green 	true 	0000-00-00 00:00:00 	0000-00-00 00:00:00
// 4 	DA X 6568 	Skoda 	Fabia 	silver 	true 	0000-00-00 00:00:00 	0000-00-00 00:00:00
