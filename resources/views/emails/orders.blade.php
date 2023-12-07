<x-mail::message>
# Order Email

Your order with id {{ $order->id }} has been placed.

# Order Details
<x-mail::table>
| Id                   | Total                 |
| --------------------:| ---------------------:|
| **{{ $order->id }}** | $ {{ $order->total }} |
</x-mail::table>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
