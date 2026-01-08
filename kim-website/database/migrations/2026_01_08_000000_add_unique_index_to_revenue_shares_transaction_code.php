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
        try {
            Schema::table('revenue_shares', function (Blueprint $table) {
                $table->unique('transaction_code', 'revenue_shares_transaction_code_unique');
            });
        } catch (\Exception $e) {
            // In case the index already exists (different DB engines / test runs), ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revenue_shares', function (Blueprint $table) {
            $table->dropUnique('revenue_shares_transaction_code_unique');
        });
    }
};
