<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        $totalAmount = $payments->sum('amount');

        $custTotal = Customer::count();

        $productTotal = Product::count();

        $newOrderCount = Order::where('status', 0)->count();

        $paymentDaily = Payment::whereDate('created_at', Carbon::today())->get();
        $dailyTotal = $paymentDaily->sum('amount');

        return view('dashboard', compact('totalAmount', 'dailyTotal', 'custTotal', 'newOrderCount', 'productTotal'));
    }
}
