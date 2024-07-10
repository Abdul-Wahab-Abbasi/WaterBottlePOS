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
            $table->foreignId('account_no')->constrained('customers', 'id')->onDelete('cascade');
            $table->integer('rc_bottles')->nullable();
            $table->integer('dl_bottles')->nullable();
            $table->decimal('total_amount', 8, 2);
            $table->date('order_date');
            $table->date('delivered_on')->nullable();
            $table->string('paid_status')->default('unpaid');
            $table->enum('status', ['Pending', 'Completed', 'Canceled']);
            $table->decimal('received', 8, 2)->default(0.00);
            $table->decimal('return_amount', 8, 2)->default(0.00);
            $table->decimal('balance', 8, 2);
            $table->integer('invoicePaid')->default(0);
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
