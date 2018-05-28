<?php

use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            //列类型
            $table->increments('id')->unsigned()->comment('主键');
            $table->string('name')->default('')->comment('地区名');
            $table->unsignedInteger('pid')->comment('父级id');
            $table->unsignedTinyInteger('level')->comment('层级');

            $table->engine = 'InnoDB';//存储引擎
            $table->comment = '全国地区表';
            $table->autoIncrement = 1;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
