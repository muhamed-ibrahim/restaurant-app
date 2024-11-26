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
            $table->unsignedBigInteger('table_id');
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('waiter_id')->nullable();
            $table->decimal('total', 8, 2)->default(0);
            $table->boolean('paid')->default(false);
            $table->date('date')->default(now());

            // Define foreign keys
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('waiter_id')->references('id')->on('waiters')->onDelete('set null');
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
