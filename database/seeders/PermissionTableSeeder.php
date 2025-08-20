<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

			// System Optimizer
			['name' => 'system-optimizer'],

			// Offline Order
			['name' => 'offline-order'],

			// Category
			['name' => 'category.view'],
			['name' => 'category.create'],
			['name' => 'category.update'],
			['name' => 'category.delete'],
			['name' => 'category.specification-control'],

			// Category Banner
			['name' => 'category-banner.view'],
			['name' => 'category-banner.create'],
			['name' => 'category-banner.update'],
			['name' => 'category-banner.delete'],

			// Specification Key
			['name' => 'specification-key.view'],
			['name' => 'specification-key.create'],
			['name' => 'specification-key.update'],
			['name' => 'specification-key.delete'],

			// Specification Types
			['name' => 'specification-types.view'],
			['name' => 'specification-types.create'],
			['name' => 'specification-types.update'],
			['name' => 'specification-types.delete'],

			// Specification Type Attributes
			['name' => 'specification-type-attribute.view'],
			['name' => 'specification-type-attribute.create'],
			['name' => 'specification-type-attribute.update'],
			['name' => 'specification-type-attribute.delete'],

			// Products
			['name' => 'product.view'],
			['name' => 'product.create'],
			['name' => 'product.update'],
			['name' => 'product.delete'],
			['name' => 'product.specification'],
			['name' => 'product.duplicate'],

			// Stock
			['name' => 'stock.view'],
			['name' => 'stock.create'],
			['name' => 'stock.delete'],

			// Flash Deal
			['name' => 'flash-deal.view'],
			['name' => 'flash-deal.create'],
			['name' => 'flash-deal.update'],
			['name' => 'flash-deal.delete'],

			// Banner
			['name' => 'banner.view'],
			['name' => 'banner.create'],
			['name' => 'banner.update'],
			['name' => 'banner.delete'],

			// Coupon
			['name' => 'coupon.view'],
			['name' => 'coupon.create'],
			['name' => 'coupon.update'],
			['name' => 'coupon.delete'],
			['name' => 'coupon.assign-to-customer'],

			// Bulk Import
			['name' => 'bulk-import.category'],
			['name' => 'bulk-import.brand'],
			['name' => 'bulk-import.product'],
			['name' => 'bulk-import.stock'],
			['name' => 'bulk-import.specification'],

			// QR Cart Suggestion
			['name' => 'qr-cart-suggestion'],

			// Orders
			['name' => 'all-order.view'],
			['name' => 'all-order.update'],
			['name' => 'pending-order.view'],
			['name' => 'pending-order.update'],
			['name' => 'packaging-order.view'],
			['name' => 'packaging-order.update'],
			['name' => 'shipping-order.view'],
			['name' => 'shipping-order.update'],
			['name' => 'confirm-order.view'],
			['name' => 'confirm-order.update'],
			['name' => 'delivered-order.view'],
			['name' => 'delivered-order.update'],
			['name' => 'returned-order.view'],
			['name' => 'returned-order.update'],
			['name' => 'failed-order.view'],
			['name' => 'failed-order.update'],
			['name' => 'refund-requested-order.view'],
			['name' => 'refund-requested-order.update'],

			// Customer
			['name' => 'customer.view'],
			['name' => 'customer.create'],
			['name' => 'customer.update'],
			['name' => 'customer.delete'],
			['name' => 'customer.send-gift-points-to-customer'],

			// Customer Review
			['name' => 'customer-review.view'],
			['name' => 'customer-review.create'],
			['name' => 'customer-review.update'],
			['name' => 'customer-review.delete'],
			['name' => 'customer-review.answer'],

			// Customer Question
			['name' => 'customer-question.view'],
			['name' => 'customer-question.answer'],

			// Brand
			['name' => 'brand.view'],
			['name' => 'brand.create'],
			['name' => 'brand.update'],
			['name' => 'brand.delete'],

			// Brand Types
			['name' => 'brand-type.view'],
			['name' => 'brand-type.create'],
			['name' => 'brand-type.update'],
			['name' => 'brand-type.delete'],

			// Report
			['name' => 'product-sale-report.view'],
			['name' => 'order-report.view'],
			['name' => 'stock-purchase-report.view'],
			['name' => 'transaction-report.view'],

			// Pricing Tier
			['name' => 'pricing-tier.view'],
			['name' => 'pricing-tier.create'],
			['name' => 'pricing-tier.update'],
			['name' => 'pricing-tier.delete'],

			// Wishlist
			['name' => 'wishlist.view'],
			['name' => 'wishlist.delete'],

			// Website Setup
			['name' => 'website-setup.home-page-category'],
			['name' => 'website-setup.header'],
			['name' => 'website-setup.footer'],
			['name' => 'website-setup.page-management'],
			['name' => 'website-setup.appearance'],
			['name' => 'website-setup.seo'],

			// Settings
			['name' => 'settings.general-settings'],
			['name' => 'settings.homepage-settings'],
			['name' => 'settings.currency'],
			['name' => 'settings.vat-tax'],
			['name' => 'settings.email-templates'],
			['name' => 'settings.sms-templates'],

			// Laptop Finder
			['name' => 'laptop-finder.offer-page'],
			['name' => 'laptop-finder.finder-page'],
			['name' => 'laptop-finder.budget'],
			['name' => 'laptop-finder.purpose'],
			['name' => 'laptop-finder.screen-size'],
			['name' => 'laptop-finder.portability'],
			['name' => 'laptop-finder.features'],

			// Shipping Configuration
			['name' => 'shipping-configuration.update'],
			['name' => 'shipping-configuration.zone'],
			['name' => 'shipping-configuration.country'],
			['name' => 'shipping-configuration.city'],
			['name' => 'shipping-configuration.carrier'],

			// Send email
			['name' => 'send-mail'],

			// Installment plans
			['name' => 'installment-plans.view'],
			['name' => 'installment-plans.create'],
			['name' => 'installment-plans.update'],
			['name' => 'installment-plans.delete'],
			['name' => 'installment-plans.view-balance-request'],
			['name' => 'installment-plans.update-balance-request'],

			// Stuff
			['name' => 'stuff.view'],
			['name' => 'stuff.create'],
			['name' => 'stuff.update'],
			['name' => 'stuff.delete'],

			// Roles & Permission
			['name' => 'roles.view'],
			['name' => 'roles.create'],
			['name' => 'roles.update'],
			['name' => 'roles.delete'],

            // System Status
			['name' => 'system-status.view'],

			// Contact Message
			['name' => 'contact-message.view'],
			['name' => 'contact-message.delete'],

			// Activity Log
			['name' => 'activity-log.view'],

			// Image Upload
			['name' => 'image-upload.view'],
			['name' => 'image-upload.upload'],
			['name' => 'image-upload.delete'],

			// Gateway Configuration
			['name' => 'gateway-configuration.view'],
		];

		$insert_data = [];
		$time_stamp = Carbon::now();
		foreach ($data as $d) {
			$d['guard_name'] = 'admin';
			$d['created_at'] = $time_stamp;
			$insert_data[] = $d;
		}

		Permission::insert($insert_data);

    }
}
