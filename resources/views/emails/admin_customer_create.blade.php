<x-mail::message>
# Hello {{ $user->name }},

Admin Created Your account in {{ config('app.name') }}. <br>
Your User Email: {{$user->email}} <br>
Password: {{$password}} <br>
Please Login and Change Your Password for Account Confirmation.

<x-mail::button :url="route('login')">
Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
