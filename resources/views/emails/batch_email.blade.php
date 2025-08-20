@component('mail::message')
# Hello {{ $user->name }},

{!! $messageText !!}

@component('mail::button', ['url' => url('/')])
Explore
@endcomponent

Thanks,
<br>
{{ config('app.name') }}
@endcomponent
