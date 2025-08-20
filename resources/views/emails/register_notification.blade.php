@component('mail::message')
![Logo](http://domain.com/images/logo.png)

# Hello {{ $user->name }},

**Welcome to  {{ config('app.name') }}**

Thank you for registering with us. We're excited to have you on board.

@component('mail::button', ['url' => route('login')])
Visit Us
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
