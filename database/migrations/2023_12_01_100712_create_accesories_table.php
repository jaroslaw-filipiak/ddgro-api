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
        Schema::create('accesories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('short_name');
            $table->string('photo')->nullable();
            $table->string('height_mm')->nullable();
            $table->string('height_inch')->nullable();
            $table->integer('packaging')->nullable();
            $table->integer('euro_palet')->nullable();
            $table->decimal('price_net', 8, 2)->nullable();
            $table->string('wood_width',)->nullable();
            $table->integer('pieces_in_m2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accesories');
    }
};
