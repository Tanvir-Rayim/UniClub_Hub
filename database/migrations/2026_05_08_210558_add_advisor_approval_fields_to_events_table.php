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
        Schema::table('events', function (Blueprint $table) {
            $table->enum('advisor_approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('advisor_remarks')->nullable()->after('advisor_approval_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['advisor_approval_status', 'advisor_remarks']);
        });
    }
};
