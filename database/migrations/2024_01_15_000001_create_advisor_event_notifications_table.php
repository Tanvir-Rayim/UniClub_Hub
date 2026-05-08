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
        Schema::create('advisor_event_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advisor_id');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('club_id');
            $table->enum('status', ['pending', 'read', 'archived'])->default('pending');
            $table->enum('notification_type', ['new_proposal', 'budget_update', 'attendance_sync'])->default('new_proposal');
            $table->text('message')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('advisor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');

            // Prevent duplicate pending notifications of the same type for the same event and advisor
            $table->unique(['advisor_id', 'event_id', 'notification_type'], 'adv_evt_notif_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisor_event_notifications');
    }
};
