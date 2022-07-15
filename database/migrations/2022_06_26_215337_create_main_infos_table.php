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
        Schema::create('main_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('whatsapp')->nullable();
            $table->integer('totalProjects')->nullable();
            $table->integer('totalCustomers')->nullable();
            $table->integer('totalExperience')->nullable();
            $table->text('aboutUsEnglish');
            $table->text('aboutUsArabic')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
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
        Schema::dropIfExists('main_infos');
    }
};
