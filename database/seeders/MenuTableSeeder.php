<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'name' => 'Dashboard',
                'slug' => 'admin/dashboard',
            ],
            [
                'name' => 'Customers',
                'slug' => 'admin/customer/view',
            ],
            [
                'name' => 'Offline Orders',
                'slug' => 'admin/offline-order',
            ],
            [
                'name' => 'General Settings',
                'slug' => 'admin/settings/general',
            ],
            [
                'name' => 'Homepage Settings',
                'slug' => 'admin/homepage/configuration',
            ],
            [
                'name' => 'Currency',
                'slug' => 'admin/currency',
            ],
            [
                'name' => 'Vat & Tax',
                'slug' => 'admin/tax',
            ],
            [
                'name' => 'Email Templates',
                'slug' => 'admin/settings/email',
            ],
            [
                'name' => 'OTP Templates',
                'slug' => 'admin/settings/otp',
            ],
            [
                'name' => 'Categories',
                'slug' => 'admin/categories',
            ],
            [
                'name' => 'Primary Categories',
                'slug' => 'admin/category/index',
            ],
            [
                'name' => 'Sub Categories',
                'slug' => 'admin/categories/sub',
            ],
            [
                'name' => 'Add Category',
                'slug' => 'admin/categories/add',
            ],
            [
                'name' => 'Add Sub Category',
                'slug' => 'admin/categories/sub/add',
            ],
            [
                'name' => 'Category Banners',
                'slug' => 'admin/category-banner',
            ],
            [
                'name' => 'Specification Public Keys',
                'slug' => 'admin/categories/specification/keys/public',
            ],
            [
                'name' => 'Specification Keys',
                'slug' => 'admin/categories/specification/keys',
            ],
            [
                'name' => 'Specification Key Types',
                'slug' => 'admin/categories/specification/types',
            ],
            [
                'name' => 'Specification Type attributes',
                'slug' => 'admin/categories/specification/types/attributes/listing',
            ],
            [
                'name' => 'Products',
                'slug' => 'admin/product',
            ],
            [
                'name' => 'Create New Product',
                'slug' => 'admin/products/create',
            ],
            [
                'name' => 'Specification Control',
                'slug' => 'admin/products/specifications/edit',
            ],
            [
                'name' => 'Stock',
                'slug' => 'admin/stock',
            ],
            [
                'name' => 'Add/Create Stock',
                'slug' => 'admin/stock/create',
            ],
            [
                'name' => 'Flash Deals',
                'slug' => 'admin/flash-deal',
            ],
            [
                'name' => 'Create New Flash Deals',
                'slug' => 'admin/flash-deal/create',
            ],
            [
                'name' => 'Banners',
                'slug' => 'admin/banner',
            ],
            [
                'name' => 'Create New Banner',
                'slug' => 'admin/banner/create',
            ],
            [
                'name' => 'Coupon',
                'slug' => 'admin/coupon',
            ],
            [
                'name' => 'Category Bulk Import',
                'slug' => 'admin/import/category',
            ],
            [
                'name' => 'Brand Bulk Import',
                'slug' => 'admin/import/brand',
            ],
            [
                'name' => 'Product Bulk Import',
                'slug' => 'admin/import/product',
            ],
            [
                'name' => 'QR Code Generator',
                'slug' => 'admin/generate-qr',
            ],
            [
                'name' => 'All Orders',
                'slug' => 'admin/orders',
            ],
            [
                'name' => 'Pending Orders',
                'slug' => 'admin/orders?status=pending',
            ],
            [
                'name' => 'Packaging Orders',
                'slug' => 'admin/orders?status=packaging',
            ],
            [
                'name' => 'Shipping Orders',
                'slug' => 'admin/orders?status=shipping',
            ],
            [
                'name' => 'Out of Delivery Order',
                'slug' => 'admin/orders?status=out_of_delivery',
            ],
            [
                'name' => 'Delivered Orders',
                'slug' => 'admin/orders?status=delivered',
            ],
            [
                'name' => 'Returned Order',
                'slug' => 'admin/orders?status=returned',
            ],
            [
                'name' => 'Failed Order',
                'slug' => 'admin/orders?status=failed',
            ],
            [
                'name' => 'Refund Order',
                'slug' => 'admin/orders?status=refund_requested',
            ],
            [
                'name' => 'Customer List',
                'slug' => 'admin/customer',
            ],
            [
                'name' => 'Customer Question',
                'slug' => 'admin/customer/question',
            ],
            [
                'name' => 'Brands',
                'slug' => 'admin/brand',
            ],
            [
                'name' => 'Create Brands',
                'slug' => 'admin/brand/create',
            ],
            [
                'name' => 'Create New Brand Types',
                'slug' => 'admin/brand-type',
            ],
            [
                'name' => 'Product Sale Report',
                'slug' => 'admin/reports/productsSell',
            ],
            [
                'name' => 'Pricing Tier',
                'slug' => 'admin/pricing-tier',
            ],
            [
                'name' => 'Wishlist',
                'slug' => 'admin/reports/wishlist',
            ],
            [
                'name' => 'Home Page Category',
                'slug' => 'admin/home-page-category',
            ],
            [
                'name' => 'Website Header',
                'slug' => 'admin/website/header',
            ],
            [
                'name' => 'Website Footer',
                'slug' => 'admin/website/footer',
            ],
            [
                'name' => 'Pages',
                'slug' => 'admin/page',
            ],
            [
                'name' => 'Create New Page',
                'slug' => 'admin/page/create',
            ],
            [
                'name' => 'Website Appearance',
                'slug' => 'admin/website/appearance',
            ],
            [
                'name' => 'Home Page SEO',
                'slug' => 'admin/settings/seo/home',
            ],
            [
                'name' => 'Login Page SEO',
                'slug' => 'admin/settings/seo/login',
            ],
            [
                'name' => 'Register Page SEO',
                'slug' => 'admin/settings/seo/register',
            ],
            [
                'name' => 'Forget Password Page SEO',
                'slug' => 'admin/settings/seo/forget',
            ],
            [
                'name' => 'Contact Page SEO',
                'slug' => 'admin/settings/seo/contact',
            ],
            [
                'name' => 'PC Builder Page SEO',
                'slug' => 'admin/settings/seo/pc_builder',
            ],
            [
                'name' => 'All Categories Page SEO',
                'slug' => 'admin/settings/seo/all_categories',
            ],
            [
                'name' => 'All Brands Page SEO',
                'slug' => 'admin/settings/seo/all_brand',
            ],
            [
                'name' => 'Laptop Buying Guide Page SEO',
                'slug' => 'admin/settings/seo/laptop_buying_guide',
            ],
            [
                'name' => 'Track Order Page SEO',
                'slug' => 'admin/settings/seo/track_order',
            ],
            [
                'name' => 'Track Order Result Page SEO',
                'slug' => 'admin/settings/seo/track_order_result',
            ],
            [
                'name' => 'Compare Page SEO',
                'slug' => 'admin/settings/seo/compare',
            ],
            [
                'name' => 'Cart Page SEO',
                'slug' => 'admin/settings/seo/cart',
            ],
            [
                'name' => 'Checkout Page SEO',
                'slug' => 'admin/settings/seo/checkout',
            ],
            [
                'name' => 'Order Confirmation Page SEO',
                'slug' => 'admin/settings/seo/order_confirmation',
            ],
            [
                'name' => 'Flash Deals Page SEO',
                'slug' => 'admin/settings/seo/flash_deals',
            ],
            [
                'name' => 'Laptop Finder Budgets',
                'slug' => 'admin/laptop/budget',
            ],
            [
                'name' => 'Laptop Finder Purpose',
                'slug' => 'admin/laptop/purpose',
            ],
            [
                'name' => 'Laptop Finder Screen Sizes',
                'slug' => 'admin/laptop/screen',
            ],
            [
                'name' => 'Laptop Finder Portability',
                'slug' => 'admin/laptop/portability',
            ],
            [
                'name' => 'Laptop Finder Features',
                'slug' => 'admin/laptop/features',
            ],
            [
                'name' => 'Shipping Configuration',
                'slug' => 'admin/shipping-configuration',
            ],
            [
                'name' => 'Shipping Zone List',
                'slug' => 'admin/zone',
            ],
            [
                'name' => 'Shipping Country List',
                'slug' => 'admin/country',
            ],
            [
                'name' => 'Shipping City List',
                'slug' => 'admin/city',
            ],
            [
                'name' => 'Shipping Carriers List',
                'slug' => 'admin/carrier',
            ],
            [
                'name' => 'Notification/Custom Mail for All Users',
                'slug' => 'admin/push_mails',
            ],
            [
                'name' => 'Installment Plans',
                'slug' => 'admin/installments/plans',
            ],
            [
                'name' => 'Balance Requests',
                'slug' => 'admin/balance/requests',
            ],
            [
                'name' => 'Contact Messages',
                'slug' => 'admin/contact-message',
            ],
            [
                'name' => 'Activity Logs',
                'slug' => 'admin/activity-logs',
            ],
            [
                'name' => 'User/Stuff List',
                'slug' => 'admin/stuff',
            ],
            [
                'name' => 'User Roles & Permission',
                'slug' => 'admin/roles',
            ],
            [
                'name' => 'System Information',
                'slug' => 'admin/server-status',
            ],
            [
                'name' => 'Image Upload',
                'slug' => 'admin/image-upload',
            ],
            [
                'name' => 'Gateway Configuration',
                'slug' => 'admin/gateway-configuration',
            ]
        ]);

    }
}
