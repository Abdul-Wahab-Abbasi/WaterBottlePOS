<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Billing;
use App\Models\invoice;
use Carbon\Carbon;

class billingController extends Controller
{
    public function index(Request $request)
    {

        $currentYear = now()->year;

        // Create an array to store the completed months
        $completedMonths = [];
        $currentMonth = now()->month;
        for ($month = 1; $month <= 12; $month++) {
            // Create a Carbon instance for the first day of the month
            $firstDayOfMonth = Carbon::createFromDate($currentYear, $month, 1);

            // Check if the first day of the month is in the past and not the current month
            if ($firstDayOfMonth->isPast() && $month != $currentMonth) {
                // Add the month to the list of completed months
                $completedMonths[] = $firstDayOfMonth->format('F'); // Format the month name (e.g., January)
            }
        }
        $monthlyCustomers = [];
        $customCustomers = [];

        // Get the selected month from the request
        $selectedMonth = request('selectedMonth');
        // Get the first date of the selected month
        $endDateOfMonth = Carbon::parse($selectedMonth)->endOfMonth();
        $startDateOfMonth = Carbon::parse($selectedMonth)->firstOfMonth();
        // Get customers eligible for monthly billing

        $eligibleMonthlyCustomers = Customer::where('created_at', '<', $endDateOfMonth)
            ->get();
        $eligibleCustomCustomers = Customer::all();

        // Iterate through eligible customers
        foreach ($eligibleMonthlyCustomers as $customer) {
            // Get the count of unpaid and paid orders for the customer
            $unpaidOrdersCount = $customer->orders()->where('paid_status', 'unpaid')->count();
            $paidOrdersCount = $customer->orders()->where('paid_status', 'Paid')->count();
            $thisMonthUnpaid = $customer->orders()
                ->where('paid_status', 'unpaid')
                ->whereDate('created_at', '>=', Carbon::parse($selectedMonth)->startOfMonth())
                ->whereDate('created_at', '<=', Carbon::parse($selectedMonth)->endOfMonth())
                ->count();
            $thisMonthPaid = $customer->orders()
                ->where('paid_status', 'Paid')
                ->whereDate('created_at', '>=', Carbon::parse($selectedMonth)->startOfMonth())
                ->whereDate('created_at', '<=', Carbon::parse($selectedMonth)->endOfMonth())
                ->count();

            // Determine the last billing status and date for the customer
            $lastBill = $customer->Billing()->latest()->skip(1)->first();
            if ($lastBill) {
                $lastBillDate = $lastBill->created_at->toDateString();
                $toDate = $lastBill->created_at->endOfMonth();
                $lastBillStatus = $lastBill->bill_status;
            } else {
                $lastBillDate = $customer->created_at->toDateString();
                $toDate = $customer->created_at->endOfMonth();
                $lastBillStatus = 'First Bill';
            }
            // check if the customr is already made the bill of this month
            $alreadyCreated = $customer->Billing()
                ->whereDate('from_date', '=', $startDateOfMonth)
                ->whereDate('to_date', '=', $endDateOfMonth)
                ->get();



            // Include eligible customers for monthly billing
            $monthlyCustomers[] = [
                'customer' => $customer,
                'unpaidOrdersCount' => $unpaidOrdersCount,
                'paidOrdersCount' => $paidOrdersCount,
                'lastBillStatus' => $lastBillStatus,
                'lastBillDate' => $lastBillDate,
                'toDate' => $toDate,
                'selectedMonth' => $selectedMonth,
                'thisMonthUnpaid' => $thisMonthUnpaid,
                'thisMonthPaid' => $thisMonthPaid,
                'alreadyCreated' => $alreadyCreated
            ];
        }
        foreach ($eligibleCustomCustomers as $customer) {
            // Get the count of unpaid and paid orders for the customer
            $unpaidOrdersCount = $customer->orders()->where('paid_status', 'unpaid')->count();
            $lastUnpaidOrder = $customer->orders()->where('paid_status', 'unpaid')->latest()->first();
            if($lastUnpaidOrder){
                $lastUnpaidOrderDate = $lastUnpaidOrder->created_at->toDateString();
            }else{
            $lastUnpaidOrderDate ="All Orders Paid";
            }
            $paidOrdersCount = $customer->orders()->where('paid_status', 'Paid')->count();
            // Determine the last billing status and date for the customer
            $lastBill = $customer->Billing()->latest()->skip(1)->first();
            if ($lastBill) {
                $lastBillDate = $lastBill->created_at->toDateString();
                $lastBillStatus = $lastBill->bill_status;
            } else {
                $lastBillDate = $customer->created_at->toDateString();
                $lastBillStatus = 'First Bill';
            }
            $customCustomers[] = [
                'customer' => $customer,
                'unpaidOrdersCount' => $unpaidOrdersCount,
                'paidOrdersCount' => $paidOrdersCount,
                'lastBillStatus' => $lastBillStatus,
                'lastBillDate' => $lastBillDate,
                'lastUnpaidOrderDate' => $lastUnpaidOrderDate,
            ];
        }
        return view('billing.index', compact('monthlyCustomers','customCustomers', 'completedMonths', 'selectedMonth'));
    }
    public function show(Request $request)
    {
        $invoice = Invoice::all();
        $selectedCustomers = $request->input('selected_customers');
        if($request->billingMonth=='custom'){
            $billingMonth = $request->billingMonth;
            $fromDate = $request->fromDate;
            $toDate =  $request->toDate;
            if($fromDate >= $toDate){
                return redirect()->back()->with('success', ['To_Date must be greater then From_Date.', 'warning', 'info-circle'])
                ;
            }
        }else{
            $billingMonth = $request->billingMonth;
            $currentYear = now()->year;
            $fromDate =  Carbon::parse($billingMonth)->startOfMonth()->toDateString();
            $toDate =  Carbon::parse($billingMonth)->endOfMonth()->toDateString();
        }
        
        $dueDays = $request->dueDays;
        $dueCharges = $request->dueCharges;

        // Initialize an array to store billing details for each selected customer
        $customerBillingDetails = [];

        foreach ($selectedCustomers as $customerId) {
            // Fetch the customer details
            $customer = Customer::findOrFail($customerId);

            $orders = Order::where('account_no', $customerId)
                ->whereDate('created_at', '>=', Carbon::parse($fromDate))
                ->whereDate('created_at', '<=', Carbon::parse($toDate))
                ->get();

            // Fetch the order products for each order
            foreach ($orders as $order) {
                $order->products = $order->products()->get();
            }

            // Calculate the total current amount by summing the total_amount of orders
            $totalCurrentAmount = $orders->where('paid_status', 'unpaid')->sum('total_amount');
            $totalCurrentBalance = $orders->sum('balance');
            $totalCurrentReceived = $orders->sum('received');
            $totalAmount = $orders->sum('total_amount');

            // Fetch the billing details for the customer to check if bill already present
            $billing = Billing::where('customer_id', $customerId)->where('to_date', $toDate)->get();
            // fetching future bills if present
            $futureBills = Billing::where('customer_id', $customerId)->where('from_date', '>=' ,$toDate)->where('bill_status','unpaid')->get();
           
            // Fetch previous unpaid bills for the customer
            if ($billing != null) {
                $previousUnpaidBills = Billing::where('customer_id', $customerId)
                    ->where('bill_status', 'unpaid')
                    ->where('to_date', '<', $fromDate)
                    ->get();
            } else {
                $previousUnpaidBills = '';
            }

            $previousTotal = 0;
            if (!$previousUnpaidBills->isEmpty()) {
                foreach ($previousUnpaidBills as $previousBill) {
                    $previousTotal += $previousBill->total;
                }
            }

            $typeOfBill = $billingMonth;
            $lastBillID = "Auto Generated";
            // Add the billing details to the array
            $customerBillingDetails[] = [
                'customer' => $customer,
                'orders' => $orders,
                'billing' => $billing,
                'previousUnpaidBills' => $previousUnpaidBills,
                'totalPreviousAmount' => $previousTotal,
                'totalCurrentAmount' => $totalCurrentAmount,
                'totalCurrentBalance' => $totalCurrentBalance,
                'totalCurrentReceived' => $totalCurrentReceived,
                'totalAmount' => $totalAmount,
            ];
            $actualTotal= $totalCurrentAmount - $totalCurrentBalance + $previousTotal;
            if ($billing->isEmpty()) {
                if($actualTotal==0){
                    return redirect()->back()->with('warning', ['No Unpaid Orders Found Between ', 'success', 'check-circle']);
                  }
                $addToBilling = new Billing();
                $addToBilling->customer_id = $customerId;
                $addToBilling->title = $typeOfBill;
                $metaDate = Carbon::parse($toDate);
                $dueDate = $metaDate->addDays($dueDays);
                $addToBilling->due_date = $dueDate;
                $addToBilling->from_date = $fromDate;
                $addToBilling->to_date = $toDate;
                $addToBilling->total = $totalCurrentAmount - $totalCurrentBalance + $previousTotal;
                $addToBilling->charges = $dueCharges;
                $addToBilling->save();
            } else {
                $dueDate = $billing[0]->due_date;
                $fromDate = $billing[0]->from_date;
                $toDate = $billing[0]->to_date;
                $dueCharges = $billing[0]->charges;
                $lastBillID = $billing[0]->id;
            }
            if(!$futureBills->isEmpty()){
               foreach($futureBills as $futureBill){
                $futureBill->total+= $totalCurrentAmount - $totalCurrentBalance + $previousTotal;
                $futureBill->save();
               }
            }
        }
        return view('billing.createBill', compact('customerBillingDetails', 'invoice', 'billingMonth', 'fromDate', 'toDate', 'dueDays', 'dueCharges', 'typeOfBill', 'lastBillID'))
            ->with('success', ['Bill created successfully.', 'success', 'check-circle']);
    }
    public function manage()
    {
        // Fetch billing details along with party name
        $bills = Billing::select('billing.*', 'customers.party_name')
            ->join('customers', 'billing.customer_id', '=', 'customers.id')
            ->get();
            foreach($bills as $bill){
                $bill->to_date = Carbon::parse($bill->to_date);
                $bill->due_date = Carbon::parse($bill->due_date);
            }
          
        return view('billing.manageBills', ['bills' => $bills]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'charges' => 'required',
            'dueDate' => 'required',
        ]);
        $billId = $request->input('id');
        $charges = $request->input('charges');
        $dueDate = $request->input('dueDate');

        // Retrieve the bill by ID
        $bill = Billing::findOrFail($billId);

        // Update the charges
        $bill->charges = $charges;
        $bill->due_date = $dueDate;
        $bill->save();
        return redirect()->back()->with('success', ['Bill Updated Successfully!', 'success', 'check-circle']);
    }
    public function updateStatus(Request $request)
{
    // Retrieve the bill ID from the request
    $billId = $request->input('bill_id');

    // Find the bill by ID
    $bill = Billing::findOrFail($billId);

    // Retrieve the customer ID from the bill
    $customerId = $bill->customer_id;

    // Retrieve all unpaid bills of this customer
    $unPaidBills = Billing::where('customer_id', $customerId)
        ->where('bill_status', 'unpaid')->where('to_date','<=',$bill->to_date)
        ->get();

    // Now iterate over each unpaid bill to find out the related orders 
    foreach ($unPaidBills as $unPaidBill) {
        // Update the bill status to 'Paid'
        $unPaidBill->bill_status = 'paid';
        $unPaidBill->save();

        // Retrieve orders within the date range of the current bill
        $orders = Order::where('account_no', $customerId)
            ->where('created_at', '>=', $unPaidBill->from_date)
            ->where('created_at', '<=', $unPaidBill->to_date)
            ->where('paid_status', 'unpaid')
            ->get();
            
        // Update the paid_status and balance of each order
        foreach ($orders as $order) {
            $order->paid_status = 'Paid';

            if($order->received > $order->total_amount){
                $haveToReturn = $order->total_amount - $order->received;
                if($order->return_amount != $haveToReturn){
                    $order->balance = 0;
                    $order->return_amount=$order->received - $order->total_amount;
                }else{
                    $order->balance = 0;
                }
            }elseif($order->received == 0){
                $order->balance = 0;
                $order->return_amount=0;
                $order->received= $order->total_amount;
            }
            $order->save();
        }
    }

    return redirect()->back()->with('success', ['Bill Paid Successfully!', 'success', 'check-circle']);
}

public function destroy(Billing $bill)
{
    $futureBills = Billing::where('customer_id', $bill->customer_id)
                    ->where('from_date', '>=', $bill->to_date) // Use '>=' instead of '>'
                    ->where('bill_status', 'unpaid')
                    ->get();

    // Then delete the order
    if (!$futureBills->isEmpty()) {
        foreach ($futureBills as $futureBill) {
            $futureBill->total -= $bill->total;
            $futureBill->save();
        }
    }

    $bill->delete();

    // Redirect back to the previous page with a success message
    return redirect()->back()->with('success', ['Bill Deleted Successfully!', 'success', 'check-circle']);
}
}
