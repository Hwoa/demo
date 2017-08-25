<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_data', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->string('title', 255)->comment('タイトル');
            $table->integer('kind')->unsigned()->nullable()->comment('種別');
            $table->string('from_name', 255)->nullable()->comment('送信者名');
            $table->string('to_name', 255)->nullable()->comment('受信者名');
            $table->string('from_address', 255)->comment('送信者アドレス');
            $table->string('to_address', 255)->comment('受信者アドレス');
            $table->tinyInteger('is_read')->unsigned()->comment('既読フラグ');
            $table->tinyInteger('is_reply')->unsigned()->comment('返信フラグ');
            $table->text('contents')->comment('内容');
            $table->datetime('date')->comment('日付');
            $table->softDeletes();
            $table->timestamps();
            $table->index('kind');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_data');
    }
}
