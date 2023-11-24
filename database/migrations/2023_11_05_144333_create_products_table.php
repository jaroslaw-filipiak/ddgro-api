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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('series_id');
            $table->foreign('series_id')->references('id')->on('products_series')->onUpdate('cascade')->onDelete('cascade');
            $table->string('distance_code');
            $table->string('distance_min');
            $table->string('distance_max');
            $table->string('photo');
            $table->string('name');
            $table->string('description');
            $table->string('short_name');
            $table->string('height_mm');
            $table->string('height_inch');
            $table->integer('packaging');
            $table->integer('euro_palet');
            $table->integer('price_net');
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
        Schema::dropIfExists('products');
    }
};
