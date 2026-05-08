<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('budget_item_id')->nullable()->constrained('event_budgets', 'id')->onDelete('set null');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->string('file_path');
            $table->string('file_type'); // 'receipt', 'invoice', 'proof_of_purchase', 'other'
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_proofs');
    }
};
