<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparkTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spark_tags', function (Blueprint $table) {
            $table->id();

            $table->foreignId("spark_id")->references("id")->on("sparks")->cascadeOnDelete();
            $table->foreignId("tag_id")->references("id")->on("tags")->cascadeOnDelete();

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
        Schema::dropIfExists('spark_tags');
    }
}
