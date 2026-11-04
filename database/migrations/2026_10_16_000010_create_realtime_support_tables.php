<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->boolean('thread_closed')->default(false)->after('status');
        });

        Schema::create('report_messages', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->char('report_id', 26);
            $table->string('sender_type', 20);
            $table->unsignedBigInteger('staff_actor_id')->nullable();
            $table->text('body');
            $table->boolean('closed_thread')->default(false);
            $table->timestamps();

            $table->foreign('report_id')
                ->references('id')->on('incident_reports')
                ->cascadeOnDelete();

            $table->foreign('staff_actor_id')
                ->references('id')->on('users')
                ->nullOnDelete();
        });

        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->char('report_id', 26);
            $table->string('channel', 32)->default('email');
            $table->string('event', 64)->default('new_report');
            $table->string('status', 20)->default('queued');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamps();

            $table->foreign('report_id')
                ->references('id')->on('incident_reports')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_deliveries');
        Schema::dropIfExists('report_messages');

        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropColumn('thread_closed');
        });
    }
};
