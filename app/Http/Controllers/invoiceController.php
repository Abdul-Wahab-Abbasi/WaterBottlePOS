<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoice;
use App\Models\Order;
use App\Models\Customer;
use App\Models\InvoiceRecord;
use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class invoiceController extends Controller
{
    public function invoiceTemp()
    {
        $invoiceTemp = invoice::all();
        return view('invoice.invoiceTemplate', compact('invoiceTemp'));
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required',
            'organization' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required',
            'note' => 'required',
            'message' => 'required',
            'phone' => 'required',
        ]);

        // Find the invoice by its ID
        $invoice = Invoice::findOrFail($id);

        // Update the invoice fields
        $invoice->title = $request->title;
        $invoice->organization = $request->organization;
        $invoice->address = $request->address;
        $invoice->note = $request->note;
        $invoice->message = $request->message;
        $invoice->phone = $request->phone;

        // Handle image update
        if ($request->hasFile('image')) {
            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public', $imageName);
            $invoice->image = $imageName;
        }

        // Save the changes to the invoice
        $invoice->save();

        // Redirect back with success message
        return redirect()->route('invoice.invoiceTemp')->with('success', ['Invoice & Bill Updated Successfully.', 'success', 'check-circle']);
    }
    public function showInvoicePreview($id)
    {
        try {
            // Check if an invoice record with the given order ID exists
            $invoiceRecord = InvoiceRecord::where('order_id', $id)->first();
            $order = Order::with('customer')->findOrFail($id);
            if ($invoiceRecord) {
                // If an invoice record exists, fetch its data and send it to the view
                
                $invoice = Invoice::first();
                $orderProducts = OrderProduct::where('order_id', $id)->get();
    
                // Return the order, invoice, and related data to the invoice template
                return view('invoice.createInvoice', [
                    'order' => $order,
                    'invoice' => $invoice,
                    'orderProducts' => $orderProducts,
                    'invoiceRecord' => $invoiceRecord
                ]);
            } else {
                // If no invoice record exists, proceed with your original logic to fetch data from other tables
                $order = Order::with('customer')->findOrFail($id);
                $invoice = Invoice::first();
                $orderProducts = OrderProduct::where('order_id', $id)->get();
                $invoiceRecord='';
                // Return the order, invoice, and related data to the invoice template
                return view('invoice.createInvoice', [
                    'order' => $order,
                    'invoice' => $invoice,
                    'orderProducts' => $orderProducts,
                    'invoiceRecord' => $invoiceRecord
                ]);
            }
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->withError('Order not found.');
        }
    }
    public function storeInvoiceRecord(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'title' => 'required',
        'date' => 'required',
        'time' => 'required',
        'customer_id' => 'required',
        'admin' => 'required',
        'order_id' => 'required',
        'total' => 'required',
        'received' => 'required',
        'change_returned' => 'required',
        'balanceAmount' => 'required',
    ]);
if($request->received<$request->total){
    return redirect()->back()->withErrors('Received Amount not equals to Total Amount');
}
    $order = Order::findOrFail($request->order_id);
    $order->paid_status = 'Paid';
    $order->invoicePaid = 1;
    $order->received = $request->received;
    $order->return_amount = $request->change_returned;
    $order->balance = $request->balanceAmount;
    $order->save();
    // Store the invoice record in the database
    $invoiceRecord = InvoiceRecord::create($validatedData);

    // Retrieve the change_returned and received values
    $changeReturned = $invoiceRecord->change_returned;
    $received = $invoiceRecord->received;
    $pendingOrderCount = Order::where('paid_status', 'unpaid')->count();

    // Update session variable based on pending order count
    if ($pendingOrderCount === 0) {
        Session::forget('pending_order_count');
    } else {
        Session::put('pending_order_count', $pendingOrderCount);
    }
    // Redirect back with success message
    return redirect()->back()->with('success', ['Invoice record created successfully.', 'success', 'check-circle'])
                             ->with(compact('changeReturned', 'received'));
}
}
