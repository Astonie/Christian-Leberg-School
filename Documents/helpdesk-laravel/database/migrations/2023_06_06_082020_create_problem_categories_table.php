<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->unsignedBigInteger('unit_id')->index('unit_id');
            $table->string('name');
            // $table->foreign(['unit_id'], 'problem_categories_ibfk_1')->references(['id'])->on('units')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
        Schema::dropIfExists('problem_categories');
    }
};
