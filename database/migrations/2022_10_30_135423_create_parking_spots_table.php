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
        Schema::create('parking_spots', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('car_id')->nullable();
            $table->string('number');
            $table->string('row');
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
        Schema::dropIfExists('parking_spots');
    }
};
//  ["id"=>"2",  "number"=>"2",  "row"=>"1", "image" => "./img/parking_spot.png", "status"=>true],
//  ["id"=>"3",  "number"=>"3",  "row"=>"1", "image" => "./img/parking_spot.png", "status"=>false],
//  ["id"=>"4",  "number"=>"4",  "row"=>"1", "image" => "./img/parking_spot.png", "status"=>false],
//  ["id"=>"1",  "number"=>"1",  "row"=>"1", "image" => "./img/parking_spot.png", "status"=>true],
//  ["id"=>"5",  "number"=>"5",  "row"=>"2", "image" => "./img/parking_spot.png", "status"=>true],
//  ["id"=>"6",  "number"=>"6",  "row"=>"2", "image" => "./img/parking_spot.png", "status"=>true],
//  ["id"=>"7",  "number"=>"7",  "row"=>"2", "image" => "./img/parking_spot.png", "status"=>false],
//  ["id"=>"8",  "number"=>"8",  "row"=>"2", "image" => "./img/parking_spot.png", "status"=>false],
//  ["id"=>"9",  "number"=>"9",  "row"=>"3", "image" => "./img/parking_spot.png", "status"=>false],
//  ["id"=>"10", "number"=>"10", "row"=>"3", "image" => "./img/parking_spot.png", "status"=>false],
//  ["id"=>"11", "number"=>"11", "row"=>"3", "image" => "./img/parking_spot.png", "status"=>true],
//  ["id"=>"12", "number"=>"12", "row"=>"3", "image" => "./img/parking_spot.png", "status"=>true],
//  ["id"=>"13", "number"=>"13", "row"=>"4", "image" => "./img/parking_spot.png", "status"=>true],
//  ["id"=>"14", "number"=>"14", "row"=>"5", "image" => "./img/parking_spot.png", "status"=>false],
//  ["id"=>"15", "number"=>"15", "row"=>"5", "image" => "./img/parking_spot.png", "status"=>false],

//  insert into parking_spots(number, row, image, status, created_at, updated_at) values ('1', '1', './img/parking_spot.png', true, '2022-30-10 00:00:00', '2022-30-10 00:00:00');
