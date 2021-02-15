<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geoip', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('ip')->virtualAs('INET_NTOA(id)');//虚拟字段
            $table->string('country_code', 2)->nullable();//国家 ISO3166 代码
            $table->string('province',128)->nullable();//省
            $table->string('city',128)->nullable();//市
            $table->string('district')->nullable();//区
            $table->string('isp')->nullable();//运营商
            $table->double('longitude')->nullable()->comment('经度');
            $table->double('latitude')->nullable()->comment('纬度');
            $table->engine = 'MyISAM';
            $table->charset = 'utf8';
            $table->collation = 'utf8_bin';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geoip');
    }
}
