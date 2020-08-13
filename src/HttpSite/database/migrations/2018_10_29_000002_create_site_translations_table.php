<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_translations', function (Blueprint $table) {
            $table->increments('id');  
            $table->string('title'); 
            $table->string('description')->nullable(); 
            $table->string('page_title')->nullable(); 
            $table->string('email_title')->nullable(); 
            $table->string('h1')->nullable(); 
            $table->string('page_h1')->nullable(); 
            $table->text('keywords')->nullable();  
            $table->string('language')->default('fa');
            $table->unsignedInteger('site_id')->nullabel();
            $table->softDeletes();

            $table->foreign('site_id')->references('id')->on('sites');
        });  

        \DB::table('site_translations')->insert([ 
            'title' => 'site', 
            'site_id' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('site_translations');
    }
}
