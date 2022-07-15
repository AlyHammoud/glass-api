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
        Schema::table('products', function (Blueprint $table) {
            $table->string('titleArabic')->after('title')->nullable();
            $table->string('briefDetailsArabic')->after('briefDetails')->nullable();
            $table->text('fullDetailsArabic')->after('fullDetails')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('titleArabic');
            $table->dropColumn('briefDetailsArabic');
            $table->dropColumn('fullDetailsArabic');
        });
    }
};
