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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->integer('activity_id')->nullable();
            $table->enum('activity_type', [
                'default', 'admin', 'area', 'banner', 'brand', 'cart', 'category', 'city', 'country', 'coupon',
                'currency', 'notice', 'offer', 'order', 'page', 'payment', 'product', 'productquestion', 'productquestionanswer',
                'productspecification', 'productstock', 'producttax', 'promocode', 'promocodeusage', 'rating', 'refundrequest',
                'refundtransaction', 'reviewanswer','search', 'specificationkey', 'specificationkeytype', 'specificationkeytypeattribute',
                'stockpurchase', 'subscriber', 'supportticket', 'supportticketreply', 'tax', 'user', 'useraddress', 'usercoupon', 'userphone',
                'userpoint', 'userwallet', 'wallettopup', 'wallettransaction', 'wishlist', 'zone', 'system'
            ])->default('default');
            $table->text('activity');
            $table->enum('action', ['create', 'update', 'delete', 'view', 'default'])->default('default');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
