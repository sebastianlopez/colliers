<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->unsignedBigInteger('gallery_id')->nullable();
            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('set null');
            $table->unsignedBigInteger('video_gallery_id')->nullable();
            $table->foreign('video_gallery_id')->references('id')->on('video_galleries')->onDelete('set null');
            $table->integer('show_delete')->default(1);
            $table->string('image', 45)->nullable();
            $table->string('image_seo', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('info_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('content_id');
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->string('lang', 2);
            $table->string('title', 200);
            $table->text('content')->nullable();
            $table->string('title_meta', 200)->nullable();
            $table->text('keywords_meta')->nullable();
            $table->string('description_meta', 300)->nullable();
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
        Schema::dropIfExists('contents');
        Schema::dropIfExists('info_contents');

    }
}
