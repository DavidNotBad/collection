<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaiduCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baidu_city', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('市名称');
            $table->string('level')->comment('市等级');
            $table->text('summary')->comment('简介');
            $table->text('basicinfo')->comment('基本信息');
            $table->text('catalog_bak')->comment('目录备份');
            $table->text('catalog')->comment('目录');

            $table->engine = 'InnoDB';
            $table->comment = '百度城市信息';
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baidu_city');
    }
}
