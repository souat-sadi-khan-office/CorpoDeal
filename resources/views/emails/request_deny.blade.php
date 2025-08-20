<x-mail::message>
# Request not Approved

Hello {{ $user->name ?? 'Valued User' }},

Your Negative Balance Request in **{{ config('app.name') }}** has been Declined by the admin.

### Amount: {{$balanceREQ->currency->code ?? 'USD' }} {{ number_format($balanceREQ->amount, 2) }}
### Installment Plan {{$balanceREQ->installmentPlan->name}} - {{$balanceREQ->installmentPlan->length}} Months + {{$balanceREQ->installmentPlan->extra_charge_percent}}%
@if(!empty($message))
### Admin Note:
{{ $message }}
@endif

<x-mail::button :url="route('account.negative.balance')">
Click here for Details.
</x-mail::button>

Thank you,
{{ config('app.name') }} Team
</x-mail::message>
