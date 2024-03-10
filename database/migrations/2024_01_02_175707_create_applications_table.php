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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('type');
            $table->integer('total_area');
            $table->integer('count');
            $table->integer('gap_between_slabs')->nullable();
            $table->integer('lowest');
            $table->integer('highest');
            $table->integer('terrace_thickness')->nullable();
            $table->integer('distance_between_joists')->nullable();
            $table->integer('distance_between_supports')->nullable();
            $table->integer('joist_height')->nullable();
            $table->integer('slab_width')->nullable();
            $table->integer('slab_height')->nullable();
            $table->integer('slab_thickness')->nullable();
            $table->integer('tiles_per_row');
            $table->integer('sum_of_tiles');
            $table->string('support_type');
            $table->string('main_system');
            $table->string('name_surname');
            $table->string('email');
            $table->string('proffesion');
            $table->boolean('terms_accepted');
            $table->integer('slabs_count');
            $table->integer('supports_count');
            $table->json('products');
            $table->json('accesories')->nullable();
            $table->json('additional_accessories');
            $table->json('m_standard')->nullable();
            $table->json('m_spiral')->nullable();
            $table->json('m_max')->nullable();
            $table->json('m_alu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
};