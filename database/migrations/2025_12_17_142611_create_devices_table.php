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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->string('imei')->unique();
            $table->string('status')->default('stock'); // stock, repair, pickup_ready, sold
            $table->text('condition_notes')->nullable();

            // Finanzas
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();

            // Fechas
            $table->dateTime('entry_date');
            $table->dateTime('sold_at')->nullable();

            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
