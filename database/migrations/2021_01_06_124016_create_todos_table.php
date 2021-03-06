<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'todos',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('hash')->index();
                $table->string('title');
                $table->text('description');
                $table->timestamp('schedule');
                $table->tinyInteger('priority')->default(1);
                $table->tinyInteger('archived')->default(0);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('todos');
    }
}
