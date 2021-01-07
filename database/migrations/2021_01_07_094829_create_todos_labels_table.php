<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'todos_labels',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('todo_id')->unsigned()->index();
                $table->foreign('todo_id')->references('id')->on('todos')->onDelete('cascade');

                $table->unsignedBigInteger('label_id')->unsigned()->index();
                $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');

                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos_labels');
    }
}
