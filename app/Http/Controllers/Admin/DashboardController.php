<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'customerCount' => User::where('role', 'customer')->count(),
            'orderCount' => Order::count(),
            'recentOrders' => Order::with('user')->latest()->take(6)->get(),
        ]);
    }
}
