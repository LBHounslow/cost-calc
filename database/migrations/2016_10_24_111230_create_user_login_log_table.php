<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLoginLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('login_user_name');
            $table->string('login_user_email');
            $table->string('login_client_ip');
            $table->integer('success');
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
        Schema::drop('user_login_log');
    }
}
