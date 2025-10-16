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
        Schema::create('sales_summary', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->date('transaction_date');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            // Detail penjualan
            $table->integer('quantity_sold')->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);

            // Diskon / voucher
            $table->decimal('discount_amount', 12, 2)->default(0)->nullable();
            $table->string('voucher_code', 100)->nullable();

            // Metode pembayaran
            $table->enum('payment_method', ['cash', 'qris', 'card'])->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_summary');
    }
};
