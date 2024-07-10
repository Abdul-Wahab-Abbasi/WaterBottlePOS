<x-app-layout>
    <section class="d-flex justify-content-center align-items-center">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card invoice-card" id="#invoicecard">
                    <div class="card-title card-title-preview text-center p-1 opacity-75">Invoice Preview</div>
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ asset($invoice->image ? 'storage/' . $invoice->image : 'assets/img/productDefault.jpg') }}" id="invoiceProfileImage" style="max-width: 110px;" alt="Profile" class="rounded-circle border">
                        <h2 class="mb-1 fs-4">{{ $invoice->organization }}</h2>
                        <h3 class="fs-5">{{ $invoice->title }}</h3>
                        <div class="dropdown-divider"></div>
                        <table class="border-0 fs-7 mt-2">
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                            <tr>
                                <td><input type="text" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly value="{{$invoiceRecord == null ? 'auto generated' : $invoiceRecord->id}}" name="id" id=""></td>
                                <td><input type="date" class="border-0 shadow-none form-control p-0 m-0 fs-7" value="{{$invoiceRecord == null ? date('Y-m-d') : $invoiceRecord->date}}" readonly name="date" id="invoiceDate"></td>
                                <td><input type="text" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly value="{{$invoiceRecord == null ? date('h:i A') : $invoiceRecord->time}}" name="time" id="invoiceTime"></td>
                            </tr>
                            <tr>
                                <th>CUSTOMER</th>
                                <th>ADMIN</th>
                                <th>ORDER ID</th>
                            </tr>
                            <tr>
                                <td><input type="text" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly name="customer" value="{{ $order->customer->party_name }}" id="invoiceCustomer"></td>
                                <td><input type="text" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly value="{{$invoiceRecord == null ? Auth::user()->name : $invoiceRecord->admin}}" name="admin" id=""></td>
                                <td>{{ $order->id }}</td>
                            </tr>
                        </table>
                        <span class="mt-1"></span>
                        <caption>Product Details</caption>
                        <table class="fs-7 table border-1">
                            <tr>
                                <th class="border-1">ID</th>
                                <th class="border-1">Product Name</th>
                                <th class="border-1">Qty</th>
                                <th class="border-1">Rate</th>
                                <th class="border-1">Amount</th>
                            </tr>
                            @if ($orderProducts->count() > 0)
                            @foreach ($orderProducts as $orderProduct)
                            <tr>
                                <td class="border-1"><input type="number" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly name="customer" value="{{ $orderProduct->product_id }}" id="invoiceProductId"></td>
                                <td class="border-1"><input type="text" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly name="invoiceProduct" value="{{ $orderProduct->product_name }}" id="invoiceProduct"></td>
                                <td class="border-1"><input type="number" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly name="invoiceQty" value="{{ $orderProduct->qty }}" id="invoiceQty"></td>
                                <td class="border-1"><input type="number" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly name="invoiceRate" value="{{ $orderProduct->product_price }}" id="invoiceRate"></td>
                                <td class="border-1"><input type="number" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly name="invoiceTotal" value="{{ $order->total_amount }}" id="invoiceTotal"></td>
                            </tr>
                            @endforeach
                            @else
                            <p>No products found for this order.</p>
                            @endif
                        </table>
                        <span class="mt-2"></span>
                        @if($invoiceRecord != null || $order->paid_status=="Paid")
                        <div class="w-100">
                            @else
                            <form action="{{ route('invoice.store-invoice-record') }}" onsubmit="validateRecieved(event,'recieved','totalAmount')" method="post" id="invoiceForm">
                                @endif
                                @csrf
                                <div class="billing-details fs-7 w-100 text-start">
                                    <div class="detail d-flex align-items-center justify-content-between">
                                        <b>TOTAL</b>
                                        <b>Rs {{ $order->total_amount }}</b>
                                    </div>
                                    <input type="hidden" name="title" value="{{ $invoice->title }}">
                                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                                    <input type="hidden" name="time" value="{{ date('h:i A') }}">
                                    <input type="hidden" name="customer_id" value="{{ $order->customer->id }}">
                                    <input type="hidden" name="admin" value="{{ Auth::user()->name }}">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <input type="hidden" name="total" id="totalAmount" value="{{ $order->total_amount }}">
                                    @if($order->paid_status!="Paid" && $invoiceRecord == null)
                                    <div class="detail w-100 d-flex py-2 align-items-center justify-content-between">
                                        <b>RECIEVED</b>
                                        @if($invoiceRecord != null)
                                        <b>Rs {{ $invoiceRecord->received }}</b>
                                        @else
                                        <b style="justify-self:flex-end;"><span class="InvoicePreviewAlert text-danger fs-7" >Enter Recieved Amount</span></b>
                                        <input type="number" oninput="updateReturnAmount('recieved','returned','totalAmount','balanceAmount','balanceAmountValue')" required class="InvoicePreviewInput bg-warning-subtle fw-bold border border-danger form-control m-0 fs-7 w-25" name="received" id="recieved" value="">
                                        @endif
                                    </div>
                                    <div class="detail d-flex align-items-center justify-content-between">
                                        <b>CHANGE RETURNED</b>
                                        @if($invoiceRecord == null)
                                        <input type="number" oninput="updateReturnAmount('recieved','returned','totalAmount','balanceAmount')" class="InvoicePreviewInput bg-warning-subtle fw-bold form-control m-0 fs-7 w-25" name="change_returned" id="returned" value="{{$invoiceRecord == null ? '' : $invoiceRecord->change_returned}}">
                                        @else
                                        <b>Rs {{$invoiceRecord->change_returned}}</b>
                                        @endif
                                    </div>
                                    <div class="detail d-flex align-items-center justify-content-between">
                                        <b>Balance</b>
                                        @if($invoiceRecord == null)
                                        <b>Rs <span id="balanceAmount"></span> <input type="hidden" id="balanceAmountValue" name="balanceAmount"></b>
                                        @else
                                        <b>Rs @if($invoiceRecord->changed_returned == 0) 0 @elseif($invoiceRecord->changed_returned == ($invoiceRecord->received - $invoiceRecord->total)) 0 @else {{( $invoiceRecord->received - $invoiceRecord->total ) - $invoiceRecord->change_returned}} @endif</b>
                                        @endif
                                    </div>
                                    @elseif($invoiceRecord != null)
                                    <div class="detail w-100 d-flex py-2 align-items-center justify-content-between">
                                        <b>RECIEVED</b>
                                        @if($invoiceRecord != null)
                                        <b>Rs {{ $invoiceRecord->received }}</b>
                                        @else
                                        <b style="justify-self:flex-end;"><span class="InvoicePreviewAlert text-danger fs-7" >Enter Recieved Amount</span></b>
                                        <input type="number" oninput="updateReturnAmount('recieved','returned','totalAmount','balanceAmount','balanceAmountValue')" required class="InvoicePreviewInput bg-warning-subtle fw-bold border border-danger form-control m-0 fs-7 w-25" name="received" id="recieved" value="">
                                        @endif
                                    </div>
                                    <div class="detail d-flex align-items-center justify-content-between">
                                        <b>CHANGE RETURNED</b>
                                        @if($invoiceRecord == null)
                                        <input type="number" oninput="updateReturnAmount('recieved','returned','totalAmount','balanceAmount')" class="InvoicePreviewInput bg-warning-subtle fw-bold form-control m-0 fs-7 w-25" name="change_returned" id="returned" value="{{$invoiceRecord == null ? '' : $invoiceRecord->change_returned}}">
                                        @else
                                        <b>Rs {{$invoiceRecord->change_returned}}</b>
                                        @endif
                                    </div>
                                    <div class="detail d-flex fs-7 align-items-center justify-content-between">
                                        <b>Balance</b>
                                        @if($invoiceRecord == null)
                                        <b>Rs <span id="balanceAmount"></span> <input type="hidden" id="balanceAmountValue" name="balanceAmount"></b>
                                        @else
                                        <b>Rs @if($invoiceRecord->changed_returned === 0) 0 @elseif($invoiceRecord->changed_returned === ($invoiceRecord->received - $invoiceRecord->total)) 0 @else {{( ($invoiceRecord->received - $invoiceRecord->total) - $invoiceRecord->change_returned)}} @endif</b>
                                        @endif
                                    </div>
                                    @else
                                    <div class="detail w-100 text-success fs-6 d-flex py-1 align-items-center justify-content-center text-center">
                                       <b>Paid By Bill</b>
                                    </div>
                                    @endif
                                </div>
                                <div class="bottom-messages fs-7 py-2 w-100">
                                    <span class="note"><b>Note:</b> <br> {{$invoice->note }}</span>
                                    <br>
                                    <span class="message"><b>Benefit:</b> <br>{{$invoice->message }}</span>
                                    <br>
                                    <p class="address m-0"><b>Address:</b><br>{{$invoice->address }}</p>
                                </div>
                                @if($invoiceRecord != null ||$order->paid_status=="Paid")
                                <button type="submit" class="btn btn-success printBtn" onclick="window.print()">Print</button>
                                @else
                                <button type="submit" class="btn btn-success printBtn" onclick="validateRecieved(event,'recieved','totalAmount')">Print</button>
                                @endif
                                @if($invoiceRecord != null ||$order->paid_status=="Paid")
                        </div>
                        @else
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- <button onclick="printInvoice()">print</button> -->

    </section>

    @if(session('success'))
    <div class="alert alert-{{ session('success')[1] }} alert-dismissible fade show" role="alert">
        <i class="bi bi-{{ session('success')[2] }} me-1"></i>
        {{ session('success')[0] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(session('success'))
    <script>
        window.print()
    </script>
    @endif
    <script>
        let i =0;
        function updateReturnAmount(input, returnEle, totalEle,balance,balanceVal) {
            let returnAmount = document.getElementById(returnEle);
            let balanceAmount = document.getElementById(balance);
            let balanceAmountValue = document.getElementById(balanceVal);
            let inputEle = document.getElementById(input);
          let actualReturn = 0;
            let totalAmount = parseFloat(document.getElementById(totalEle).value);
            let err = inputEle.parentElement.getElementsByClassName('InvoicePreviewAlert')[0];
            if (inputEle.value < totalAmount) {
                err.textContent = `Minimum Value is ${totalAmount}`;
            } else {
                actualReturn = inputEle.value - totalAmount;
                if(returnAmount.value>actualReturn){
                    returnAmount.value=actualReturn;
                }else if(i>0){
                    if(returnAmount.value===''){
                        returnAmount.value=0;
                        returnAmount.blur();
                        returnAmount.focus();
                    }
                }else if(returnAmount.value===''){
                    returnAmount.value = inputEle.value - totalAmount;
                    i++;
                };
                balanceAmount.innerHTML = actualReturn - returnAmount.value;
                balanceAmountValue.value = actualReturn - returnAmount.value;
                err.textContent = `Received Amount Entered`;
            }
        }

        function validateRecieved(e, inputEle, totalEle) {
            let totalAmount = parseInt(document.getElementById(totalEle).value);
            let inputElement = document.getElementById(inputEle);
            let err = inputElement.parentElement.getElementsByClassName('InvoicePreviewAlert')[0]
            if (inputElement.value < totalAmount) {
                e.preventDefault();
                err.textContent = `Minimum Value is ${totalAmount}`;
                inputElement.classList.add('bg-danger-subtle')
            } else {
                returnAmount.value = inputElement.value - totalAmount;
                returnAmountDisplay.textContent = inputElement.value - totalAmount;
                err.textContent = `Received Amount Entered`;
                inputElement.classList.remove('bg-danger-subtle')
            }
        }
    </script>
</x-app-layout>