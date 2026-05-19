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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('posts', 'id')->nullOnDelete();
            $table->foreignId('root_id')->nullable()->constrained('posts', 'id')->nullOnDelete();
            $table->foreignId('repost_of_id')->nullable()->constrained('posts', 'id')->nullOnDelete();
            $table->text('content')->nullable();
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('repost_count')->default(0);
            $table->unsignedInteger('replies_count')->default(0);
            $table->timestamps();
            $table->index('parent_id');
            $table->index('root_id');
            $table->index('repost_of_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
