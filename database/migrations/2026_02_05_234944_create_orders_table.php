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
       Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_number')->unique();

    $table->foreignId('user_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->decimal('subtotal', 10, 2);
    $table->decimal('tax', 10, 2)->default(0);
    $table->decimal('delivery_fee', 10, 2)->default(0);
    $table->decimal('total_amount', 10, 2);

    $table->enum('status', [
        'pending', 'processing',
        'completed', 'cancelled'
    ])->default('pending');

    $table->enum('payment_method', ['cash', 'card', 'online'])->default('cash');
    $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');

    $table->text('delivery_address');
    $table->string('customer_phone', 20);
    $table->text('notes')->nullable();

    $table->timestamp('confirmed_at')->nullable();
    $table->timestamp('delivered_at')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
