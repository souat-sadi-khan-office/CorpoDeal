@component('mail::message')
# Scan Your QR Code

Hello {{$user->name}},

We are excited to help you easily add products to your cart. Below is your QR code that you can scan to log in and automatically add suggested products to your cart.

@component('mail::panel')
**How to Use Your QR Code:**
- Simply scan the QR code attached to this email.
- The QR code will log you in and automatically add the suggested products to your cart.
- Enjoy a seamless shopping experience with the products recommended by our admin team.

@endcomponent

@component('mail::panel')
The QR code is valid until 24 Hours. Be sure to use it before it expires!
@endcomponent

If you have any issues or questions, please don't hesitate to contact our support team. We're here to help!

Thank you for choosing {{ config('app.name') }}.

Best regards,
The {{ config('app.name') }} Team.
@endcomponent
