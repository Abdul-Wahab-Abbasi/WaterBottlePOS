<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\invoice;
use App\Models\InvoiceRecord;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class POSController extends Controller
{
    public function index()
    {
        $pendingOrderCount = Order::whereRaw('dl_bottles != rc_bottles')->count();

        // Update session variable based on pending order count
        if ($pendingOrderCount === 0) {
            Session::forget('pending_order_count');
        } else {
            Session::put('pending_order_count', $pendingOrderCount);
        }
        $orders = Order::with('orderProducts', 'customer')->latest()->get();

        // Pass the orders data to the view
        return view('POS.index', compact('orders'));
    }
    public function createOrderUser()
    {
        $customer = Customer::all();
        $product = Inventory::all();
        $invoice = Invoice::first();
        $order = Order::all();
        $lastOrder = Order::latest()->first(); // Fetch the last order
        $lastInvoice = InvoiceRecord::latest()->first(); // Fetch the last order
        return view('POS.createOrder', compact('customer', 'product', 'order', 'lastOrder', 'invoice', 'lastInvoice'));
    }
    public function addOrder(Request $request)
    {
        // Validate the request data
        $request->validate([
            'account_no' => 'required|exists:customers,id',
            'dl_bottles' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:inventory,id',
            'products.*.qty' => 'required|integer|min:1',
        ]);

        // Create a new order
        $order = new Order();
        $order->account_no = $request->account_no;
        $order->dl_bottles = $request->dl_bottles;
        $order->total_amount = $request->total_amount;
        $order->balance = 0.00;
        $order->order_date = now(); // Assuming current date
        $order->status = 'Pending'; // Assuming the initial status is pending
        $order->save();

        // Loop through each product in the request and create order product entries
        foreach ($request->products as $productData) {
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $productData['product_id'];
            $orderProduct->qty = $productData['qty'];
            $orderProduct->product_name = $productData['product_name'];
            $orderProduct->size = $productData['size'];
            $orderProduct->product_price = $productData['product_price'];
            $orderProduct->save();
        }

        $orderStatus = Order::all();
        $orderStatusCount = 0;
        foreach ($orderStatus as $ord) {
            if ($ord->paid_status == 'unpaid') {
                $orderStatusCount++;
            }
        }

        if ($orderStatusCount === 0) {
            Session::forget('pending_order_count');
        } else {
            Session::put('pending_order_count', $orderStatusCount);
        }
        $action = $request->input('submit_action');

        if ($action === 'addBill') {
            return redirect()->back()->with('success', ['Order Created Successfully!', 'success', 'check-circle']);
        } elseif ($action === 'generateInvoice') {
            return redirect()->route('invoice.createInvoice.show', $order->id);
        }
        // Redirect back with success message

    }
    public function destroy(Order $order)
    {
        // Delete the related order products first
        $order->orderProducts()->delete();

        // Then delete the order
        $order->delete();

        // Redirect back to the previous page with a success message
        return redirect()->back()->with('success', ['Order and related products deleted successfully.', 'success', 'check-circle']);
    }
    public function updateOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($request->status == 'Canceled') {
            $order->rc_bottles = $request->rc_bottles;
            $order->status = $request->status;
            $order->save();
            $pendingOrderCount = Order::where('dl_bottles', 'rc_bottles')->count();
            // Update session variable based on pending order count
            if ($pendingOrderCount === 0) {
                Session::forget('pending_order_count');
            } else {
                Session::put('pending_order_count', $pendingOrderCount);
            }

            // Redirect back with success message
            return redirect()->back()->with('success', ['Order Canceled Successfully!', 'warning', 'check-circle']);;
        }
        // Validate the request data
        $request->validate([
            'rc_bottles' => 'required|integer|min:0',
            'status' => 'required|in:Pending,Canceled', // Ensure the status field is validated
        ]);

        if($order->invoicePaid!=0){
            $order->rc_bottles = $request->rc_bottles;
        $order->status = $request->status;
        }else{
            $order->rc_bottles = $request->rc_bottles;
        $order->status = $request->status;
        }
        
        // Check if received bottles equal delivered bottles and paid status is Paid
        if ($order->rc_bottles == $order->dl_bottles && $order->status != 'Canceled') {
            $order->status = 'Completed';
        }

        // Save the changes to the order
        $order->save();

        // Count pending orders directly from the database
        $pendingOrderCount = Order::where('dl_bottles', 'rc_bottles')->count();

        // Update session variable based on pending order count
        if ($pendingOrderCount === 0) {
            Session::forget('pending_order_count');
        } else {
            Session::put('pending_order_count', $pendingOrderCount);
        }

        // Redirect back with success message
        return redirect()->back()->with('success', ['Order Updated Successfully!', 'success', 'check-circle']);
    }
    public function quikUpdate(Request $request)
    {
        $action = $request->input('submit_action');
        if($action === 'unReceivedUpdate'){
            $selectedOrders = $request->input('selected_orders');

            // Loop through each selected order ID
            foreach ($selectedOrders as $orderId) {
                // Find the order by its ID and update the paid_status
                $order = Order::findOrFail($orderId);
                $order->rc_bottles = $order->dl_bottles;
                $order->save();
            }

            // Redirect back with a success message
            return redirect()->back()->with('success', ['Bottles Updated Succesfully!', 'success', 'check-circle']);
        }else if ($action === 'allUpdate') {
            foreach ($request->orders as $orderId => $orderData) {
                $order = Order::findOrFail($orderId);
                // Update the order attributes
                if($orderData['cancel_status']=='delete'){
                    $order->delete();
                }elseif($orderData['status']=='Canceled'){
                    $order->status = $orderData['status'];
                    $order->save();
                }else{
                    if($order->invoicePaid!=0){
                        $order->rc_bottles = $orderData['rc_bottles'];
                        $order->save();
                    }else{
                    $order->rc_bottles = $orderData['rc_bottles'];
                    // Save the changes
                    $order->save();}
                }
                
            }
            return redirect()->back()->with('success', ['Selected Orders Updated!', 'success', 'check-circle']);
        }
    }
}
