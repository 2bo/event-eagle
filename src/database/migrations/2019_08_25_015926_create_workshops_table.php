<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkshopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site_name'); //サイト名
            $table->bigInteger('event_id'); //イベントID
            $table->text('title')->nullable(); //タイトル
            $table->text('catch')->nullable(); //キャッチ
            $table->text('description')->nullable(); //説明
            $table->text('event_url')->nullable(); //イベントURL
            $table->text('address')->nullable(); //開催住所
            $table->text('place')->nullable(); //開催場所
            $table->double('lat', 9, 7)->nullable(); //緯度
            $table->double('lon', 10, 7)->nullable(); //経度
            $table->dateTimeTz('started_at')->nullable(); //開始時間
            $table->dateTimeTz('ended_at')->nullable(); //終了時間
            $table->integer('limit')->nullable(); //定員
            $table->integer('participants')->nullable(); //参加者数
            $table->integer('waiting')->nullable(); //補欠者数
            $table->integer('owner_id')->nullable(); //開催者ID
            $table->string('owner_nickname')->nullable(); //開催者ニックネーム
            $table->string('owner_twitter_id')->nullable(); //開催者のTwitterId
            $table->string('owner_display_name')->nullable(); //開催者表示名
            $table->integer('group_id')->nullable(); //グループID
            $table->dateTimeTz('event_created_at')->nullable(); //イベント登録日時
            $table->dateTimeTz('event_updated_at')->nullable(); //イベント更新日時
            $table->timestamps();
        });

        Schema::table('workshops', function (Blueprint $table) {
            $table->unique(['site_name', 'event_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workshops');
    }
}
