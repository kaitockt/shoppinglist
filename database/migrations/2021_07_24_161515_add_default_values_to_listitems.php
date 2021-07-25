<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultValuesToListitems extends Migration
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
            $table->string('repeat')->nullable()->default('')->change();
            $table->boolean('done')->default(false)->change();
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
            $table->string('repeat')->nullable(false)->default(null)->change();
            $table->boolean('done')->default(null)->change();
        });
    }
}
