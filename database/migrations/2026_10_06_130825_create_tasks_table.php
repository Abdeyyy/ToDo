<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("tasks", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("category_id")->nullable();
            $table->string("title");
            $table->text("description")->nullable();
            $table->enum("status", ["pending", "in_progress", "completed"])->default("pending");
            $table->enum("priority", ["low", "medium", "high"])->default("medium");
            $table->dateTime("due_date")->nullable();
            $table->dateTime("completed_at")->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("tasks");
    }
};
