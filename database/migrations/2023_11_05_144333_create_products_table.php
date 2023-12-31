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
            $table->string('distance_code')->nullable();
            $table->string('photo')->nullable();
            $table->string('name');
            $table->string('short_name');
            $table->string('height_mm')->nullable();
            $table->string('height_inch')->nullable();
            $table->integer('packaging')->nullable();
            $table->integer('euro_palet')->nullable();
            $table->decimal('price_net', 8, 2)->nullable();
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
