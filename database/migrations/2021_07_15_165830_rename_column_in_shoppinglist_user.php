<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnInShoppinglistUser extends Migration
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
            $table->renameColumn('list_id', 'shoppinglist_id');
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
            $table->renameColumn('shoppinglist_id', 'list_id');
        });
    }
}
