<?php

namespace App\Http\Controllers;

use App\Mail\OrderReplyMail;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        // TODO delegate to an event
        Mail::send(new OrderReplyMail([
            'message' => $validated['reply'],
            'id' => $order->public_id,
        ]));

        return redirect(route('orders.index'));
    }
}
