<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->char('id', 26)->primary();
            $table->string('reference_code', 20)->unique();
            $table->unsignedBigInteger('category_id');

            $table->text('description');
            $table->string('location_text', 500);

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('location_accuracy', 8, 2)->nullable();

            $table->string('reporter_name', 120);
            $table->string('contact_number', 20);
            $table->boolean('reporting_for_other')->default(false);
            $table->string('relationship', 80)->nullable();
            $table->boolean('victim_contactable')->nullable();

            $table->char('language', 5)->default('en');
            $table->string('priority', 10)->default('medium');
            $table->string('status', 20)->default('submitted');

            $table->char('idempotency_key', 36)->unique();
            $table->boolean('suspicion_flag')->default(false);

            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'priority', 'submitted_at'], 'idx_queue');
            $table->index(['category_id', 'submitted_at'], 'idx_dup');
            $table->index(['latitude', 'longitude'], 'idx_geo');

            $table->foreign('category_id')
                ->references('id')->on('incident_categories')
                ->restrictOnDelete();
        });

        if (DB::getDriverName() !== 'sqlite') {
            // FULLTEXT on InnoDB is supported on MariaDB 10.0.5+.
            DB::statement('ALTER TABLE incident_reports ADD FULLTEXT ft_search (description, location_text)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};