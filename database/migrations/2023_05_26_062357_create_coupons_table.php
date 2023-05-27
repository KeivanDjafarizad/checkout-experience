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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->boolean('active')->default(true);
            $table->decimal('min_price', 18, 2)->default(0);
            $table->decimal('max_price', 18, 2)->nullable();
            $table->decimal('amount', 18, 2)->nullable();
            $table->text('associated_product_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
