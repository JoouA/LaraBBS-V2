<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zans', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->index()->comment('赞的人');
            // 当用户删除的时候相对应的赞也会被删除
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('topic_id')->index()->comment('被赞的专题');
            // 当专题被删除的时候，相对应的赞也会被删除
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');

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
        Schema::dropIfExists('zans');
    }
}
