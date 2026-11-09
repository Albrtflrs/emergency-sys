<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('body');
            $table->string('category');
            $table->string('severity')->default('normal');
            $table->string('status')->default('draft');
            $table->boolean('is_banner')->default(false);
            $table->json('attachments')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'published_at']);
            $table->index(['is_banner', 'status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_posts');
    }
};