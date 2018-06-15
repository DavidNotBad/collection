<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaiduCityDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baidu_city_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->comment('父级ID');
            $table->string('title')->comment('标题');
            $table->text('content')->comment('内容');

            $table->engine = 'InnoDB';
            $table->comment = '百度城市信息详情';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baidu_city_detail');
    }
}
