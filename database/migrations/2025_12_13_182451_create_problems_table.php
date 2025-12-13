<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_problems_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->text('statement');
            $table->string('department');
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
            $table->json('assignment_history')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('problems');
    }
};