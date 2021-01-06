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
                $table->string('hash')->index();
                $table->string('title');
                $table->text('description');
                $table->timestamp('time');
                $table->bigInteger('priority');
                $table->tinyInteger('is_completed')->default(0);
                $table->tinyInteger('archived')->default(0);
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->timestamps();

                $table->foreign('parent_id')->references('id')->on('todos');
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
