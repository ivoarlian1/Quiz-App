<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->json('answers')->nullable();
            $table->integer('score')->default(0);
            $table->integer('total_points');
            $table->decimal('percentage', 5, 2)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_attempts');
    }
}; 