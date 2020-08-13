<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppLogsMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_logs_meta', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer("app_log_id")->unsigned()->index();
            $table->foreign("app_log_id")
                        ->references('id')
                        ->on('app_logs')
                        ->onDelete('cascade');

            $table->string('type')->default('null'); 
            $table->string('key')->index();
            $table->text('value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_logs_meta');
    }
}
