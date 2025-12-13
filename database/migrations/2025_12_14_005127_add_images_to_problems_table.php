<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagesToProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('problems', function (Blueprint $table) {
        $table->json('images')->nullable()->after('description');
    });
}

public function down()
{
    Schema::table('problems', function (Blueprint $table) {
        $table->dropColumn('images');
    });
}
}
