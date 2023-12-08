<x-mail::message>
Your order has been placed.

# Order id: {{ $id }}.

- **Total:** ${{ $total }};
- **Address:** {{ $address }};
- **Recipient:** {{ $recipient }}.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
