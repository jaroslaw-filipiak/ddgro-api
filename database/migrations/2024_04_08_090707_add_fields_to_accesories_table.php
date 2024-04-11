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
        Schema::table('accesories', function (Blueprint $table) {
            $table->string('for_type')->nullable();
            $table->string('name_for_client')->nullable();
            $table->json('fits_to_system')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accesories', function (Blueprint $table) {
            $table->dropColumn('for_type')->nullable();
            $table->dropColumn('name_for_client')->nullable();
            $table->dropColumn('fits_to_system')->nullable();
        });
    }
};