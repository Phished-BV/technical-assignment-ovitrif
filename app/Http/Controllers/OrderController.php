<?php

namespace App\Http\Controllers;

use App\Events\OrderReplied;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('orders.index', [
            'orders' => Order::latest()->get(),
        ]);
    }

    public function edit(Order $order): View
    {
        $this->authorize('update', $order);

        return view('orders.reply', [
            'order' => $order,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'reply' => 'required|string|max:255',
        ]);

        $order->update($validated);

        event(new OrderReplied($order));

        return redirect(route('orders.index'));
    }
}
