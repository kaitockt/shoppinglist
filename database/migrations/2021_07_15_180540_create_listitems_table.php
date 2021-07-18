<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listitems', function (Blueprint $table) {
            $table->id();
            $table->integer('list_id')->unsigned();
            $table->string('name');
            $table->decimal('priority', 5, 2);
            $table->string('repeat');
            $table->boolean('fav');
            $table->timestamps();

            $table->foreign('list_id')
                ->references('id')
                ->on('shoppinglist')
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
        Schema::dropIfExists('listitems');
    }
}
