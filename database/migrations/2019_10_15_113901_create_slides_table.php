<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 20);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('slide_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('slide_id');
            $table->foreign('slide_id')->references('id')->on('slides')->onDelete('cascade');
            $table->string('filename', 45);
            $table->string('link', 300)->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('info_slide_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('slide_image_id');
            $table->foreign('slide_image_id')->references('id')->on('slide_images')->onDelete('cascade');
            $table->string('name', 200)->nullable();
            $table->string('description', 300)->nullable();
            $table->string('additional', 300)->nullable();
            $table->string('lang', 2);
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
        Schema::dropIfExists('slides');
        Schema::dropIfExists('slide_images');
        Schema::dropIfExists('info_slide_images');
    }
}
