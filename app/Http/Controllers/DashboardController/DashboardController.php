<?php

namespace App\Http\Controllers\DashboardController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch all orders
        $orders = Order::with('orderItems')->get();

        // Calculate total sales
        $sales = $orders->reduce(function ($total, $order) {
            $grossAmount = $order->orderItems->reduce(function ($subtotal, $item) {
                $totalBeforeDiscount = $item->unit_price * $item->quantity;

                // Calculate product-level discount
                $discountAmount = (strpos($item->discount_type, '%') !== false)
                    ? (($totalBeforeDiscount * rtrim($item->discount_amount, '%')) / 100)
                    : $item->discount_amount;

                $totalAfterDiscount = $totalBeforeDiscount - $discountAmount;
                return $subtotal + $totalAfterDiscount;
            }, 0);

            // Apply order-level discount
            $orderDiscount = ($order->discount_type === 'percentage')
                ? (($grossAmount * $order->discount_amount) / 100)
                : $order->discount_amount;

            $grossAmountAfterDiscount = $grossAmount - $orderDiscount;
            $otherCharges = $order->other_charges ?? 0;

            return $total + $grossAmountAfterDiscount + $otherCharges;
        }, 0);

        // Fetch all purchases
        $purchases = Purchase::with('purchaseItems')->get();

        // Calculate total purchases
        $totalPurchases = $purchases->reduce(function ($total, $purchase) {
            $grossAmount = $purchase->purchaseItems->reduce(function ($subtotal, $item) {
                $totalBeforeDiscount = $item->rate * $item->quantity;

                // Calculate product-level discount
                $discountAmount = (strpos($item->discount_type, '%') !== false)
                    ? (($totalBeforeDiscount * rtrim($item->discount_amount, '%')) / 100)
                    : $item->discount_amount;

                $totalAfterDiscount = $totalBeforeDiscount - $discountAmount;
                return $subtotal + $totalAfterDiscount;
            }, 0);

            // Apply order-level discount
            $orderDiscount = ($purchase->discount_type === '%')
                ? (($grossAmount * $purchase->discount_amount) / 100)
                : $purchase->discount_amount;

            $grossAmountAfterDiscount = $grossAmount - $orderDiscount;
            $otherCharges = $purchase->other_charges ?? 0;

            return $total + $grossAmountAfterDiscount + $otherCharges;
        }, 0);

        // Calculate total paid
        $paid = Payment::sum('amount');

        // Calculate total amount due (sales and purchases)
        $amountDue = $orders->reduce(function ($total, $order) {
            $grossAmount = $order->orderItems->reduce(function ($subtotal, $item) {
                return $subtotal + ($item->unit_price * $item->quantity);
            }, 0);

            // Apply order-level discount
            $orderDiscount = ($order->discount_type === 'percentage')
                ? (($grossAmount * $order->discount_amount) / 100)
                : $order->discount_amount;

            $grossAmountAfterDiscount = $grossAmount - $orderDiscount;
            $otherCharges = $order->other_charges ?? 0;
            $netTotal = $grossAmountAfterDiscount + $otherCharges;

            return $total + max($netTotal - $order->paid, 0);
        }, 0);

        // Pass the calculated data to the view
        return view('dashboard.dashboard', compact('sales', 'totalPurchases', 'paid', 'amountDue'));
    }






    public function profitLossView()
    {
        return view('dashboard.profit-loss'); // Blade file path
    }











}
