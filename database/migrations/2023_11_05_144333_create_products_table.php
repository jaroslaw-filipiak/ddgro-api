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
            $table->string('series');
            $table->string('type');
            $table->string('distance_code');
            $table->integer('distance_min');
            $table->integer('distance_max');
            $table->string('photo');
            $table->string('name');
            $table->string('description');
            $table->string('short_name');
            $table->integer('height_mm');
            $table->integer('height_inch');
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
