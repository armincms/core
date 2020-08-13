<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->increments('id');  
            $table->string('name');
            $table->boolean('root')->default(0);
            $table->statuses('status', true, 'deactiveated'); 
            $table->timestamps();
            $table->softDeletes();
        });

        \DB::table('domains')->insert([
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
        Schema::drop('domains');
    }
}
