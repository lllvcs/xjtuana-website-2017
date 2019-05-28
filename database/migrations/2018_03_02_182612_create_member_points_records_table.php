<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberPointsRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_points_records', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('member_points_id')->unsigned();
            $table->foreign('member_points_id')->references('id')->on('member_points')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('change');
            $table->string('desc');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_points_records');
    }
}
