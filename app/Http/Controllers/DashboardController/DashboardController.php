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
use App\Models\Product;
use App\Http\Controllers\OrderController\OrderController;
use App\Http\Controllers\PurchaseController\PurchaseController;
use App\Http\Controllers\IncomeController\IncomeController;
use App\Http\Controllers\ExpenseController\ExpenseController;
use App\Http\Controllers\PaymentController\PaymentController;
use App\Http\Controllers\ProductController\ProductController;


class DashboardController extends Controller
{
    
    public function index(
        Request $request,
        OrderController $orderController,
        PurchaseController $purchaseController,
        IncomeController $incomeController,
        ExpenseController $expenseController,
        PaymentController $paymentController,
        ProductController $productController
    ) {
        try {
            
            $totals = $orderController->index($request);
            
            $totalsPurchase = $purchaseController->index($request);
            $totalIncome = $incomeController->index($request);
            $totalExpense = $expenseController->index($request);
            $totalPayment = $paymentController->index($request);
            $productStock = $productController->calculateStockForProducts();
            
    
            // Prepare data for the dashboard
            $saleTotalNetAmount = $totals['totalNetAmount'];
            $topSellingProducts = $totals['topSellingProducts'];
            
            $purchaseTotalNetAmount = $totalsPurchase['totalNetTotalWithTax'];
            $totalCredit = $totalPayment['totalCredit'];
            $totalDebit = $totalPayment['totalDebit'];
            
            
            $dailyPayments = $totalPayment['dailyPayments'];

            $formattedDailyPayments = [];
            foreach ($dailyPayments as $date => $payments) {
                $formattedDailyPayments[] = [
                    'date' => $date,
                    'totalCredit' => $payments['totalCredit'],  
                    'totalDebit' => $payments['totalDebit'],    
                ];
            }

            return view('dashboard.dashboard', compact(
                'saleTotalNetAmount',
                'topSellingProducts',
                'purchaseTotalNetAmount',
                'totalCredit',
                'totalDebit',
                'formattedDailyPayments',
                'productStock'
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to load dashboard data: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to load dashboard data. Please try again later.');
        }
    }   


    public function profitLossView(
        Request $request,
        OrderController $orderController,
        PurchaseController $purchaseController,
        IncomeController $incomeController,
        ExpenseController $expenseController,
        PaymentController $paymentController
    ) {
        try {
            
            $totals = $orderController->index($request);
            $totalsPurchase = $purchaseController->index($request);
            $totalIncome = $incomeController->index($request);
            $totalExpense = $expenseController->index($request);
            $totalPayment = $paymentController->index($request);
    
            // Pass data to the view
            return view('dashboard.profit-loss', [
                'saleTotalNetAmount' => $totals['totalNetAmount'],
                'totalNetProfit' => $totals['totalNetProfit'],
                'saleReturnTotalNetAmount' => $totals['totalNetReturnAmount'], 
                'purchaseTotalNetAmount' => $totalsPurchase['totalNetTotalWithTax'],
                'purchaseReturnTotalNetAmount' => $totalsPurchase['totalNetReturnAmount'],
                'totalIncome' => $totalIncome['totalAmount'],
                'totalExpense' => $totalExpense['totalAmount'],
                'totalCredit' => $totalPayment['totalCredit'],
                'totalDebit' => $totalPayment['totalDebit'],
                'currencySymbol' => $totals['currencySymbol'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch profit/loss data: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to fetch profit/loss data. Please try again later.');
        }
    }
    


}
