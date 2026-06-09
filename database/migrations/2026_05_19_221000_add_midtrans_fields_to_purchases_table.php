<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('purchases', 'midtrans_order_id')) {
                $table->string('midtrans_order_id')->nullable()->unique()->after('notes');
            }
            if (!Schema::hasColumn('purchases', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            }
            if (!Schema::hasColumn('purchases', 'midtrans_snap_token')) {
                $table->string('midtrans_snap_token')->nullable()->after('midtrans_transaction_id');
            }
            if (!Schema::hasColumn('purchases', 'midtrans_payment_type')) {
                $table->string('midtrans_payment_type')->nullable()->after('midtrans_snap_token');
            }
            if (!Schema::hasColumn('purchases', 'midtrans_transaction_status')) {
                $table->string('midtrans_transaction_status')->nullable()->after('midtrans_payment_type');
            }
            if (!Schema::hasColumn('purchases', 'midtrans_fraud_status')) {
                $table->string('midtrans_fraud_status')->nullable()->after('midtrans_transaction_status');
            }
            if (!Schema::hasColumn('purchases', 'midtrans_response')) {
                $table->json('midtrans_response')->nullable()->after('midtrans_fraud_status');
            }
            if (!Schema::hasColumn('purchases', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('midtrans_response');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            foreach ([
                'paid_at',
                'midtrans_response',
                'midtrans_fraud_status',
                'midtrans_transaction_status',
                'midtrans_payment_type',
                'midtrans_snap_token',
                'midtrans_transaction_id',
                'midtrans_order_id',
            ] as $column) {
                if (Schema::hasColumn('purchases', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
