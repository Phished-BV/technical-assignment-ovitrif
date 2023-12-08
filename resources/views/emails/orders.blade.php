<x-mail::message>
Your order has been placed.

# Order id: {{ $order->id }}

- **Total:** ${{ $order->total }};
- **Address:** Some Street 123, 1234 AB, Some City;
- **Recipient:** First LastName.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
