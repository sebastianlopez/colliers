<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('position')->default(0);
            $table->integer('parent')->default(0);
            $table->string('type', 20);
            $table->string('image', 45)->nullable();
            $table->integer('level')->default(0);
            $table->integer('show_delete')->default(1);
            $table->boolean('hide')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('info_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('name', 200);
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
        Schema::dropIfExists('categories');
        Schema::dropIfExists('info_categories');
    }
}
