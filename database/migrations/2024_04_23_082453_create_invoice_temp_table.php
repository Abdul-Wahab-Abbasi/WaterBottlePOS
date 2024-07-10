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
        Schema::create('invoice_temp', function (Blueprint $table) {
            $table->id();
    $table->string('title')->nullable();
    $table->string('organization')->nullable();
    $table->string('image')->nullable();
    $table->text('note')->nullable();
    $table->text('address')->nullable();
    $table->text('message')->nullable();
    $table->text('phone')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_temp');
    }
};
