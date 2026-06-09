<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('created_at', 'users_created_at_report_index');
        });

        Schema::table('ebooks', function (Blueprint $table) {
            $table->index('category', 'ebooks_category_report_index');
            $table->index('price', 'ebooks_price_report_index');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->index('created_at', 'purchases_created_at_report_index');
            $table->index('payment_status', 'purchases_payment_status_report_index');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_created_at_report_index');
        });

        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropIndex('ebooks_category_report_index');
            $table->dropIndex('ebooks_price_report_index');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex('purchases_created_at_report_index');
            $table->dropIndex('purchases_payment_status_report_index');
        });
    }
};
