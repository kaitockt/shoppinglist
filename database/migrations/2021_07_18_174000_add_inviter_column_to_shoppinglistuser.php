<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInviterColumnToShoppinglistuser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shoppinglist_user', function (Blueprint $table) {
            //
            $table->bigInteger('inviter')->unsigned()->default(1);
            $table->foreign('inviter')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shoppinglist_user', function (Blueprint $table) {
            //
            $table->dropColumn('inviter');
        });
    }
}
