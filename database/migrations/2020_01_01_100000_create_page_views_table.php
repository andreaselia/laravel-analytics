<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('analytics.db_connection'))->create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('uri');
            $table->string('source')->nullable();
            $table->string('country');
            $table->string('browser')->nullable();
            $table->string('device');
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
        Schema::connection(config('analytics.db_connection'))->dropIfExists('page_views');
    }
}
