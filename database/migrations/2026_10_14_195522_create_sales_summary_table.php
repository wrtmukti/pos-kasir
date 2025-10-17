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

            // 🔗 Relasi utama
            $table->date('transaction_date');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            // 📦 Detail penjualan per item
            $table->integer('quantity_sold')->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0); // qty * unit_price

            // 💸 Diskon per item
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->nullOnDelete();
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('price_after_discount', 12, 2)->default(0);

            // 🎟️ Voucher persentase (berlaku untuk semua item di order)
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->decimal('voucher_percent', 5, 2)->nullable()->default(0);
            $table->boolean('voucher_applied')->default(false);

            // 💰 Total pendapatan per item setelah semua potongan
            $table->decimal('total_revenue', 12, 2)->default(0);

            // 💳 Metode pembayaran
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
