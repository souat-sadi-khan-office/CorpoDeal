# CorpoDeal Single Vendor Multi Location Multi Currency eCommerce Website (Laravel + jQuery)

A **modern, scalable and feature-rich eCommerce platform** built with **Laravel** (backend) and **jQuery** (frontend).  
Designed for performance, flexibility, and seamless shopping experience.

---

## Features

- **User Authentication**
  - Customer Registration & Login
  - Social Login (Google, Facebook)
  - Forgot & Reset Password via OTP

- **Product Management**
  - Categories, Brands, Tags
  - Product Variants (Size, Color, etc.)
  - Discount & Flash Deals
  - Product Search & Filters

- **Shopping & Checkout**
  - Add to Cart / Wishlist / Compare
  - Coupon & Discount System
  - Guest Checkout Support
  - Multiple Payment Gateways

- **Order Management**
  - Place Orders via Web & API
  - Order Tracking System
  - Tier-based Discounts
  - Invoice & Transaction History

- **Dashboard & Reports**
  - Sales Analytics
  - Stock & Inventory Reports
  - User Activity Logs

- **Mobile App Ready**
  - API Endpoints with Laravel Sanctum
  - Flutter App Integration

---

## Tech Stack

- **Backend:** [Laravel 10+](https://laravel.com/)  
- **Frontend:** jQuery, Bootstrap 5  
- **Database:** MySQL  
- **Authentication:** Laravel Sanctum / Socialite  
- **API Documentation:** Laravel Scribe  
- **Payment:** SSLCommerz, Stripe, PayPal (extensible)  

---

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/souat-sadi-khan-office/CorpoDeal
   cd CorpoDeal

2. Install dependencies:
   ```bash
    composer install
    npm install && npm run dev

3. Create environment file:
   ```bash
    cp .env.example .env
    php artisan key:generate

4. Setup database:
   ```bash
    php artisan migrate --seed

5. Start development server:
   ```bash
    php artisan serve

## API Documentation

- REST APIs powered by **Laravel Sanctum**  
- Auto-generated docs using **Laravel Scribe**

### Example Endpoints
- `POST /api/v1/register` → Register user  
- `POST /api/v1/login` → Login user  
- `POST /api/v1/checkout` → Place an order  

---

## Contributing

Contributions are always welcome!  
Please fork this repo and submit a pull request.

---

## License

This project is licensed under the **CorpoEx**.  
You are not free to use, modify, and distribute with attribution.

---

## Author

**CorpoEx**  
*Web Developer | Software Engineer | Laravel & jQuery Specialist*  

- 🌐 Portfolio: [corpoex.com](https://corpoex.com)  
- 📧 Email: sadi.khan@projukti-bd.com  
