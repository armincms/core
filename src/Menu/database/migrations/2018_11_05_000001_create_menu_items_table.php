<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');    
            $table->string('title', 250)->nullable();  
            $table->text('url', 250)->nullable();   
            $table->integer('level')->default(99);  
            $table->integer('depth')->default(0);  
            $table->string('icon', 250)->nullable();  
            $table->unsignedInteger('menu_item_id')->nullable()->index();  
            $table->unsignedInteger('menu_id')->index(); 
            $table->text('params')->nullable();  
            $table->nullableMorphs('menuable');
 
            $table->foreign('menu_item_id')->references('id')->on('menu_items'); 
            $table->foreign('menu_id')->references('id')->on('menus'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
