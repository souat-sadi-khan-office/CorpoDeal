@component('mail::message')
![Logo](http://domain.com/images/logo.png)

# Hello {{ $user->name }},

You've successfully logged in. If this was not you, please secure your account immediately.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
