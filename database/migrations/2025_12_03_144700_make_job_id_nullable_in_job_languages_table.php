<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeJobIdNullableInJobLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_languages', function (Blueprint $table) {
            $table->unsignedBigInteger('job_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_languages', function (Blueprint $table) {
            $table->unsignedBigInteger('job_id')->nullable(false)->change();
        });
    }
}

