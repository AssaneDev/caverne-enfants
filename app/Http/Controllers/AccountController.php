<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['items.artwork.artist'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->where('status', \App\OrderStatus::PAID)
            ->sum('total_cents') / 100;

        return view('account.index', compact('user', 'recentOrders', 'totalOrders', 'totalSpent'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.artwork.artist'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        if ((string)$order->user_id !== (string)Auth::id()) {
            abort(403);
        }

        $order->load(['items.artwork.artist', 'items.artwork.collection']);

        return view('account.order-detail', compact('order'));
    }

    public function invoice(Order $order)
    {
        if ((string)$order->user_id !== (string)Auth::id()) {
            abort(403);
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('account.invoice', compact('order'));
        
        return $pdf->stream('facture-' . $order->order_number . '.pdf');
    }
}
