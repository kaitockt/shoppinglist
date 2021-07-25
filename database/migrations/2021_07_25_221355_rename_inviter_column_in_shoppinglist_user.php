<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameInviterColumnInShoppinglistUser extends Migration
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
            $table->renameColumn('inviter', 'inviter_id');
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
            $table->renameColumn('inviter_id', 'inviter');
        });
    }
}
