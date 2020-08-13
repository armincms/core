<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');  
            $table->string('name');
            $table->boolean('root')->default(0);
            $table->string('title_position')->nullable(); 
            $table->string('h1_position')->nullable(); 
            $table->string('email')->nullable(); 
            $table->string('status')->default('deactivated'); 
            $table->unsignedInteger('maintenance_id')->nullable(); 
            $table->unsignedInteger('user_role')->nullable();   
            $table->text('user_setting')->nullable(); 
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('sites')->insert([
            'root'  => 1,
            'name'  => request()->getHost(),
            'status'=> 'activated'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sites');
    }
}
