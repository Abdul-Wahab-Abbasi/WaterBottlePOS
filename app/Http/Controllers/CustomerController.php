<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::all();
        return view('customer.index', compact('customer'));
    }
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        $orders = Order::where('account_no', $id)->get();
        $ordersCount = Order::where('account_no', $id)->count();
        $totalBottlesOrdered = Order::where('account_no', $id)->sum('dl_bottles');
        $recievedBottlesOrdered = Order::where('account_no', $id)->sum('rc_bottles');
        $pendingBottles=$totalBottlesOrdered-$recievedBottlesOrdered;
        $ordersThisWeek = Order::where('account_no', $id)->whereBetween('order_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->get();
        $ordersThisMonth = Order::where('account_no', $id)->whereYear('order_date', now()->year)
        ->whereMonth('order_date', now()->month)->get();
        $ordersThisYear = Order::where('account_no', $id)->whereYear('order_date', now()->year)->get();
        return view('customer.customerDetail', compact(
            'customer',
            'orders',
            'ordersCount',
            'totalBottlesOrdered',
            'recievedBottlesOrdered',
            'ordersThisWeek',
            'ordersThisMonth',
            'ordersThisYear',
            'pendingBottles'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'party_name' => 'required',
            'phone' => 'required|regex:/^03[0-4][0-9]{8}$/|min:11|max:11',
            'address' => 'required',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', ['Customer created successfully.', 'success', 'check-circle']);
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'party_name' => 'required',
            'phone' => 'required|regex:/^03[0-4][0-9]{8}$/|min:11|max:11',
            'address' => 'required',
        ]);
        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', ['Customer updated successfully.', 'success', 'check-circle']);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', ['Customer deleted successfully.', 'success', 'check-circle']);
    }
}
