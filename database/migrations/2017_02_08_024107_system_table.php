<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// Bang luu thong tin danh muc
        Schema::create('system_listtype', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->string('xml_file_name');
            $table->tinyInteger('status');
            $table->Integer('order');
        });
        // Bang luu thong tin loai danh muc
        Schema::create('system_list', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('listtype_id');
            $table->string('code');
            $table->string('name');
            $table->longText('xml_data');
            $table->Integer('order');
            $table->tinyInteger('status');
        });
        // Bang chua thong tin menu
		Schema::create('system_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('url', 256);
			$table->string('layout', 50);
            $table->string('icon', 50)->default("fa-cube");
            $table->string('type', 20)->default("module");
            $table->integer('parent')->unsigned()->default(0);
            $table->integer('hierarchy')->unsigned()->default(0);
        });
		// Bang luu thong tin module
		Schema::create('system_module', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('name', 50);
            $table->tinyInteger('status');
            $table->integer('order');
            $table->string('layout', 50);
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
		if (Schema::hasTable('system_listtype')) {
            Schema::drop('system_listtype');
        }
		if (Schema::hasTable('system_list')) {
            Schema::drop('system_list');
        }
		if (Schema::hasTable('system_module')) {
            Schema::drop('system_module');
        }
		if (Schema::hasTable('system_module')) {
            Schema::drop('system_module');
        }
    }
}
