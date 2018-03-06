<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeActionButton extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Bang luu thong tin action trong module
        Schema::create('system_action', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('module_id');
            $table->string('code', 50);
            $table->string('name', 250);
            $table->tinyInteger('status');
            $table->Integer('order');
        });
        // Bang luu thong tin button trong action
        Schema::create('system_button', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('action_id');
            $table->string('code', 50);
            $table->string('name', 250);
            $table->tinyInteger('status');
            $table->Integer('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (Schema::hasTable('system_action')) {
            Schema::drop('system_action');
        }
        if (Schema::hasTable('system_button')) {
            Schema::drop('system_button');
        }
    }
}
