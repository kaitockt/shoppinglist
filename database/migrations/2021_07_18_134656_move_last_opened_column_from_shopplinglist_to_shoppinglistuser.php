<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveLastOpenedColumnFromShopplinglistToShoppinglistuser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shoppinglist', function (Blueprint $table) {
            //
            // $table->dropColumn('last_opened');
        });
        Schema::table('shoppinglist_user', function (Blueprint $table) {
            //
            $table->timestamp('last_opened')->nullable()->useCurrent();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shoppinglist', function (Blueprint $table) {
            //
            $table->timestamp('last_opened')->nullable()->useCurrent();;
        });
        Schema::table('shoppinglist_user', function (Blueprint $table) {
            //
            $table->dropColumn('last_opened');
        });
    }
}
