<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_ipv4', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->ipAddress('ip')->virtualAs('INET_NTOA(id)');//虚拟字段
            $table->string('country_code', 2)->nullable();//国家 ISO3166 代码
            $table->string('province', 128)->nullable();//省
            $table->string('city', 128)->nullable();//市
            $table->string('district')->nullable();//区
            $table->string('isp')->nullable();//运营商
            $table->string('scenario', 20)->nullable()->comment('使用场景');
            $table->double('longitude')->nullable()->comment('经度');
            $table->double('latitude')->nullable()->comment('纬度');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geo_ipv4');
    }
};
