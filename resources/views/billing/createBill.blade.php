<x-app-layout>
    <div class="container">
    @if(session('success'))
    <div class="alert alert-{{ session('success')[1] }} alert-dismissible fade show" role="alert">
        <i class="bi bi-{{ session('success')[2] }} me-1"></i>
        {{ session('success')[0] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
        <button type="button" onclick="window.print()" style="z-index: 5; bottom:80%; right:40%;" class="btn btn-success printBtn position-fixed"><i class="bi bi-printer-fill"></i>&nbsp;PRINT</button>
        <div class="row justify-content-center">
            @foreach ($customerBillingDetails as $customerDetails)
            <div class="col col-lg-6">
                <div class="card invoice-card billing-card p-0">
                    <div class="card-title card-title-preview text-center p-1 opacity-75">Bill Preview</div>
                    <div class="card-body pt-2">
                        <div class="invoice-title text-center">
                            @foreach($invoice as $item)
                            <div class="m-0">
                                <h2 class="card-title p-1 pt-0 m-0 fs-5">{{$item->organization }}</h2>
                            </div>
                            <div class="text-muted">
                                <p class="mb-0 fs-7">{{$item->address }}</p>
                                <p class="mb-0 fs-6"><i class="uil uil-phone me-1 fs-7"></i>{{$item->phone }}</p>
                            </div>
                            @endforeach
                            <h2 class="card-title p-0 m-0 fs-6">@if($billingMonth=='custom')From {{$fromDate}} to {{$toDate}} @else For the Month of {{$billingMonth}} {{now()->year}} @endif</h2>
                        </div>

                        <hr class="m-1">

                        <div class="row">
                            <div class="col">
                                <div class="text-dark fw-medium" style="line-height:18px ;">
                                    <p class="fs-7 m-0">Bill No: <b>{{$lastBillID}}</b></p>
                                    <p class="fs-7 mb-0">Billed To: <b>{{ $customerDetails['customer']->party_name }}</b></p>
                                    <p class="mb-0 fw-normal fs-7">Account No: <b>{{ $customerDetails['customer']->id }}</b></p>
                                    <p class="mb-0 fw-normal fs-7">Address: <b>{{ $customerDetails['customer']->address }}</b></p>
                                    <p class="mb-0 fw-normal fs-7">Contact: <b>0{{ $customerDetails['customer']->phone }}</b></p>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        <div class="py-1" style="justify-self: center;">
                            <p class="fs-6 fw-semibold text-center m-0">Orders Summary</p>
                            <div class="table-responsive">
                                <table class="table table-bordered border-secondary mx-auto" style="width: max-content;">
                                    <thead>
                                        <tr>
                                            <th class="fs-7 fw-medium p-1 text-center">DATE</th>
                                            <th class="fs-7 fw-medium p-1 text-center">DESCRIPTION</th>
                                            <th class="fs-7 fw-medium p-1 text-center">RATE</th>
                                            <th class="fs-7 fw-medium p-1 text-center">QTY</th>
                                            <th class="fs-7 fw-medium p-1 text-center">DL-BT</th>
                                            <th class="fs-7 fw-medium p-1 text-center">RC-BT</th>
                                            <th class="fs-7 fw-medium p-1 text-center">BAL</th>
                                            <th class="fs-7 fw-medium p-1 text-center">AMOUNT</th>
                                            <th class="fs-7 fw-medium p-1 text-center">CASH</th>
                                            <th class="fs-7 fw-medium p-1 text-center">BALANCE</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        @foreach ($customerDetails['orders'] as $order)
                                        <tr>
                                            <td class="fs-7 p-1 text-center">{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</td>

                                            <td class="fs-7 p-1 text-center">
                                                {{ $order->products[0]->product_name  }} {{ $order->products[0]->size  }}Ltr
                                            </td>
                                            <td class="fs-7 p-1 text-center">
                                                {{ $order->products[0]->product_price  }}
                                            </td>
                                            <td class="fs-7 p-1 text-center">
                                                {{ $order->products[0]->qty  }}
                                            </td>
                                            <td class="fs-7 p-1 text-center">
                                                {{ $order->dl_bottles }}
                                            </td>
                                            <td class="fs-7 p-1 text-center">
                                                {{ $order->rc_bottles }}
                                            </td>
                                            <td class="fs-7 p-1 text-center">
                                                {{ $order->dl_bottles - $order->rc_bottles }}
                                            </td>
                                            <td class="fs-7 p-1 text-center">
                                                {{ $order->total_amount }}
                                            </td>
                                            <td class="fs-7 p-1 text-center">
                                                {{ $order->received}}
                                            </td>
                                            <td class="fs-7 p-1 text-center">
                                            {{ $order->balance}}
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr><td colspan="7" class="p-1"><div class="fs-7 align-items-center w-100 d-flex justify-content-end fw-bold">Total: </div></td>
                                        <td class="p-1">
                                       <span class="fs-7">
                                       {{ $customerDetails['totalAmount'] }} Rs
                                       </span>
                                        </td>
                                        <td class="p-1">
                                        <span class="fs-7">
                                       {{ $customerDetails['totalCurrentReceived'] }} Rs
                                       </span>
                                        </td>
                                        <td class="p-1">
                                        <span class="fs-7">
                                       {{ $customerDetails['totalCurrentBalance'] }} Rs
                                       </span>
                                        </td>
                                    </tr>
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div>
                            <!-- end table responsive -->
                        </div>
                        <div class="row">
                            <div class="col col-12">
                                <h5 class="fs-6">Previous Unpaid Bills</h5>
                                <div class="text-dark fw-medium" style="line-height: 19px;">
                                    @if ($customerDetails['previousUnpaidBills']->isEmpty())
                                    <p class="fs-7">No previous unpaid bills found.</p>
                                    @else
                                    @foreach ($customerDetails['previousUnpaidBills'] as $previousBill)
                                    <h5 class="fs-7 mb-1 fw-semibold">{{ $loop->iteration }}: {{ $previousBill->title }}: {{ $previousBill->total }} Rs</h5>
                                    @endforeach
                                    <h5 class="fs-7 fw-bold">Total Previous Unpaid Bills Amount: {{$customerDetails['totalPreviousAmount'] }} Rs</h5>
                                    @endif
                                </div>
                            </div>

                            <hr>
                            <h5 class="fs-6 fw-bold">Totals</h5>
                            <div class="col col-12" style="line-height: 19px;">
                                <h5 class="fs-7 fw-semibold">Total Current Bill: {{ $customerDetails['totalCurrentAmount']}} Rs</h5>
                                <h5 class="fs-7 fw-semibold">Total Balance Amount: {{$customerDetails['totalCurrentBalance'] }} Rs</h5>
                                @if (!$customerDetails['previousUnpaidBills']->isEmpty())
                                @foreach ($customerDetails['previousUnpaidBills'] as $previousBill)
                                <h5 class="fs-7 fw-semibold">Total Previous Amount: {{ $previousBill->total }} Rs</h5>
                                @endforeach
                                @endif
                                <h5 class="fs-6 fw-bold">Total Due Amount: {{ $customerDetails['totalCurrentAmount'] - $customerDetails['totalCurrentBalance'] + $customerDetails['totalPreviousAmount'] }} Rs</h5>
                            </div>
                            <div class="col col-12 m-0">
                                <h2 class="card-title p-0 m-0 fs-6">PAYABLE WITHIN <b class="text-danger fs-6">{{$dueDays}} DAYS</b>: {{ $customerDetails['totalCurrentAmount'] - $customerDetails['totalCurrentBalance'] + $customerDetails['totalPreviousAmount'] }} Rs</h2>
                                <h2 class="card-title p-0 m-0 fs-6">PAYABLE AFTER DUE DAYS: {{ $customerDetails['totalCurrentAmount'] +  $dueCharges - $customerDetails['totalCurrentBalance'] + $customerDetails['totalPreviousAmount']}} Rs</h2>
                            </div>
                            @foreach($invoice as $item)
                            <p class="fs-7 mt-1 m-0">
                                <b>Benefits:</b> <br>
                                {{$item->message }}
                            </p>
                            <p class="fs-7">
                                <b>Note:</b> <br>
                                {{$item->note }}
                            </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>