<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleInstancesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_instances', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps(); 
            $table->string('title')->nullable();    
            $table->string('layout')->default('module-base');
            $table->string('template')->default('*');
            $table->string('language')->default('*');
            $table->string('show_on')->default('all');
            $table->text('locate')->nullable();
            $table->string('module')->nullable();
            $table->string('description')->nullable();  
            $table->integer('click_count')->default(0);  
            $table->string('position')->default('top1');
            $table->integer('ordering')->default(1);    
            $table->longText('params')->nullable();
            $table->softDeletes();
            $table->publishing();   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_instances');
    }
}
