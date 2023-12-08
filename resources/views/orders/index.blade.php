<x-app-layout>
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            <div class="p-6 space-x-2">
                <table class="table-fixed min-w-full">
                    <thead class="border-b border-gray-300">
                    <tr class="text-left">
                        <th class="p-2">Id</th>
                        <th class="p-2">Total</th>
                        <th class="p-2 w-2/6">Address</th>
                        <th class="p-2">Recipient</th>
                        <th class="p-2"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($orders as $order)
                        <tr class="text-sm border-b border-gray-200 even:bg-gray-50 hover:bg-gray-100 group">
                            <td class="p-2">{{ $order->public_id }}</td>
                            <td class="p-2">${{ $order->total }}</td>
                            <td class="p-2">{{ $order->address }}</td>
                            <td class="p-2">{{ $order->recipient }}</td>
                            <td class="p-2 text-right">
                                <x-secondary-button class="invisible group-hover:visible">
                                    {{ __('Reply') }}
                                </x-secondary-button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
