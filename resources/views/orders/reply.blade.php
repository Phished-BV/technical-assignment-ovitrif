<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('orders.update', $order) }}">
            @csrf
            @method('patch')
            <h1 class="text-2xl font-black mb-4">Reply to order #{{ $order->public_id }}</h1>
            <textarea
                name="reply"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old('reply', $order->reply) }}</textarea>
            <x-input-error :messages="$errors->get('reply')" class="mt-2" />
            <div class="mt-4 space-x-2">
                <x-primary-button>{{ __('Submit') }}</x-primary-button>
                <a href="{{ route('orders.index') }}">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-app-layout>
