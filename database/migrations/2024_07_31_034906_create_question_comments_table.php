<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('comment');
            $table->bigInteger('question_id')->unsigned();
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
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
        Schema::table('question_comments', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('question_comments');
    }


};
