<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_activities', function (Blueprint $table) {
            if (!Schema::hasColumn('transaction_activities', 'purchase_id')) {
                $table->foreignId('purchase_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('purchases')
                    ->nullOnDelete()
                    ->unique();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaction_activities', function (Blueprint $table) {
            if (Schema::hasColumn('transaction_activities', 'purchase_id')) {
                $table->dropConstrainedForeignId('purchase_id');
            }
        });
    }
};
