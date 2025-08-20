<x-mail::message>
# Request Approved

Hello {{ $user->name ?? 'Valued User' }},

Your Negative Balance Request in **{{ config('app.name') }}** has been approved by the admin.

### Amount: {{ $balanceREQ->currency->code ?? 'USD' }} {{ number_format($balanceREQ->amount, 2) }}

@if(!empty($description))
### Admin Note:
{{ $description }}
@endif

### Installment Schedule
@if($installments->where('is_paid', false)->isNotEmpty())
<x-mail::table>
| Next Installment       | Initial Amount       | Extra Amount          | Total Amount          |
|------------------------|----------------------|-----------------------|-----------------------|
@foreach ($installments->where('is_paid', false) as $installment)
| {{ \Carbon\Carbon::parse($installment['payment_date'])->format('d M Y') }} | {{ '$'.number_format($installment['initial_amount'], 2) }} | {{ '$'.number_format($installment['extra_amount'], 2) }} | {{ '$'.number_format($installment['final_amount'], 2) }} |
@endforeach
</x-mail::table>
@else
**No Installments Scheduled**
@endif

### Payout History
@if($installments->where('is_paid', true)->isNotEmpty())
<x-mail::table>
| Paid Installment       | Initial Amount       | Extra Amount          | Total Amount          |
|------------------------|----------------------|-----------------------|-----------------------|
@foreach ($installments->where('is_paid', true) as $installment)
| {{ \Carbon\Carbon::parse($installment['payment_date'])->format('d M Y') }} | {{ '$'.number_format($installment['initial_amount'], 2) }} | {{ '$'.number_format($installment['extra_amount'], 2) }} | {{ '$'.number_format($installment['final_amount'], 2) }} |
@endforeach
</x-mail::table>
@else
**No Paid Installments Available**
@endif

<x-mail::button :url="route('account.negative.balance')">
Click here for Details.
</x-mail::button>

Thank you,
{{ config('app.name') }} Team
</x-mail::message>
