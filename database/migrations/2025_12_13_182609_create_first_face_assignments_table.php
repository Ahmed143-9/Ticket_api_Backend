<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_first_face_assignments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('first_face_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('department')->nullable(); // null means 'all' departments
            $table->boolean('is_active')->default(true);
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate assignments for same department
            $table->unique(['user_id', 'department']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('first_face_assignments');
    }
};