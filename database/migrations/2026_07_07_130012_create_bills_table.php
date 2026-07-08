<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('billing_year', 4);
            $table->unsignedTinyInteger('billing_month');
            $table->integer('electricity_usage');
            $table->string('status', 10)->default('unpaid');
            $table->timestamps();
        });

        // Add check constraint for billing_month between 1 and 12
        DB::statement('ALTER TABLE bills ADD CONSTRAINT chk_billing_month CHECK (billing_month >= 1 AND billing_month <= 12)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
