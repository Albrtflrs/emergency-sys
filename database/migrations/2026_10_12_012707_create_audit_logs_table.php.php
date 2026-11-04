<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->bigIncrements('id');

            $table->unsignedBigInteger('actor_user_id')->nullable();
            $table->string('actor_role', 20)->nullable();
            $table->string('action', 80);
            $table->string('target_type', 60)->nullable();
            $table->string('target_id', 64)->nullable();

            $table->json('payload');
            $table->json('before_summary')->nullable();
            $table->json('after_summary')->nullable();

            $table->binary('ip', 16)->nullable();
            $table->char('correlation_id', 26);

            $table->char('prev_hash', 64)->nullable();
            $table->char('row_hash', 64);

            $table->timestamp('created_at')->useCurrent();

            $table->index(['actor_user_id', 'created_at'], 'idx_audit_actor');
            $table->index(['action', 'created_at'], 'idx_audit_action');
            $table->index('correlation_id', 'idx_audit_corr');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};