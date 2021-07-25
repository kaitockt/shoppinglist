<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFavColFromListitems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listitems', function (Blueprint $table) {
            //
            $table->dropColumn('fav');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listitems', function (Blueprint $table) {
            //
            $table->boolean('fav')->nullable()->default(false);
        });
    }
}
