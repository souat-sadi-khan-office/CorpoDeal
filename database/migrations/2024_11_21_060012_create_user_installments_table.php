<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_installments', function (Blueprint $table) {
            $table->id();
            $table->integer('installment_number')->comment('Serial of Installment in this Balance request');
            $table->timestamp('payment_date');
            $table->decimal('initial_amount', 10, 2);
            $table->decimal('extra_amount', 10, 2);
            $table->decimal('final_amount', 10, 2);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('negative_balance_request_id');
            $table->foreign('negative_balance_request_id')->references('id')->on('negative_balance_requests')->onDelete('cascade');
            $table->boolean('is_paid')->default(false);
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
            $table->enum('paid_by', ['self','admin','unpaid'])->default('unpaid');
            $table->unsignedBigInteger('admin_id')->nullable()->comment('when Admin Updates payment Status');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_installments');
    }
};
