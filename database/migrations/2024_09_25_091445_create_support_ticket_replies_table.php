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
        Schema::create('support_ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('admin_id');
            $table->longText('message');
            $table->string('file_one')->nullable();
            $table->string('file_two')->nullable();
            $table->string('file_three')->nullable();
            $table->boolean('is_email_sent')->default(false);

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('ticket_id')
                ->references('id')
                ->on('support_tickets')
                ->onDelete('cascade');

            $table->foreign('admin_id')
                ->references('id')
                ->on('admins')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_replies');
    }
};
