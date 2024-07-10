<x-app-layout>
@if(session('success'))
    <div class="alert alert-{{ session('success')[1] }} alert-dismissible fade show" role="alert">
        <i class="bi bi-{{ session('success')[2] }} me-1"></i>
        {{ session('success')[0] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="pagetitle">
        <h1>Create Bills</h1>
        <nav>
            <ol class="breadcrumb">
                 
                <li class="breadcrumb-item active">Billing</li>
            </ol>
        </nav>
    </div>

    <section class="section">

        <div class="row">
            <div class="col-xl-12 justify-content-center">
                <div class="card">
                    <div class="card-body">
                        <!-- Pills Tabs -->
                        <ul class="nav nav-pills my-2" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fs-6 active" style="scale: 0.9;" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-monthly" type="button" role="tab" aria-controls="pills-monthly" aria-selected="true">Monthly Bills</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fs-6 " style="scale: 0.9;" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-custom" type="button" role="tab" aria-controls="pills-custom" aria-selected="false" tabindex="-1">Custom Bills</button>
                            </li>
                        </ul>
                        <div class="tab-content border border-success rounded pt-2" id="myTabContent">
                            <div class="tab-pane fade active show" id="pills-monthly" role="tabpanel" aria-labelledby="home-tab">
                                <div class="card-title p-2 text-center ">
                                    Select Customers & Month To Generate Monthly Bills
                                </div>
                                <form method="GET" class="d-flex p-2 align-items-center justify-content-center gap-1" action="{{ route('Billing.index') }}">
                                    @foreach ($completedMonths as $index => $month)

                                    <button type="submit" name="selectedMonth" class="btn btn-sm btn-outline-primary{{ $selectedMonth == $month ? ' active' : '' }}" value="{{ $month }}">
                                        {{$month}}
                                    </button>

                                    @endforeach
                                </form>
                                <div class="wraper p-2 w-100 d-flex align-items-center justify-content-between">

                                    <div class="form-check ms-3 d-flex align-items-center justify-content-center ">
                                        <input class="form-check-input d-block fs-6 border border-primary" name="operation" onchange="addToArrayPending( this.checked)" type="checkbox" id="btlRc">
                                        <label class="form-check-label ms-2 text-dark" for="btlRc">
                                            Select All Customers
                                        </label>
                                    </div>
                                    <form method="POST" action="{{ route('Billing.show') }}" class="gap-2 fade d-flex bg-transparent flex-row align-items-center" style="width: max-content;">
                                        @csrf <!-- CSRF Protection -->
                                        <label for="dueDays">Payable Within Days</label>
                                        <input type="number" class="form-control-sm form-control" style="max-width: 55px;" name="dueDays" required value="10" id="dueDays">
                                        <label for="dueCharges">Charges after due days</label>
                                        <input type="number" class="form-control-sm form-control" style="max-width: 80px;" name="dueCharges" required value="0" id="dueCharges">
                                        <span class="fs-6">Selected Customers (<span id="orderCount"></span>)</span>
                                        <div id="inputWrapper">

                                        </div>
                                        <input type="hidden" name="billingMonth" value="{{$selectedMonth}}" id="">
                                        <div class="btn-wrapper d-none" id="createBill">
                                            <button type="submit" class="btn  btn-success" name="submit_action">Create Bill</button>
                                        </div>
                                    </form>
                                </div>

                                <table class="table datatable table-bordered" id="orderTable">

                                    <thead>
                                        <tr>
                                            <!-- <th></th> -->
                                            <th scope="col">Check</th>
                                            <th scope="col">Account No</th>
                                            <th scope="col">Party Name</th>
                                            <th>This month's Unpaid orders</th>
                                            <th scope="col">Last Bill Date</th>
                                            <th scope="col">Last Bill Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($selectedMonth)
                                        @foreach($monthlyCustomers as $billing)
                                        <tr>
                                            @if(!$billing['alreadyCreated']->isEmpty())
                                            <td>
                                                <form method="POST" action="{{ route('Billing.show') }}" class="gap-2 d-flex bg-transparent flex-row align-items-center" style="width: max-content;">
                                                    @csrf <!-- CSRF Protection -->

                                                    <input type="hidden" class="form-control-sm form-control d-none" style="max-width: 55px;" name="dueDays" required value="10" id="dueDays">

                                                    <input type="hidden" class="form-control-sm form-control d-none" style="max-width: 80px;" name="dueCharges" required value="0" id="dueCharges">

                                                    <input type="number" name="selected_customers[]" class="d-none" value="{{ $billing['customer']->id }}">
                                                    <input type="hidden" name="billingMonth" class="d-none" value="{{$selectedMonth}}" id="">
                                                    <div class="btn-wrapper" id="createBill">
                                                        <button type="submit" class="btn btn-sm btn-success" title="Already Created" name="submit_action">View Bill</button>
                                                    </div>
                                                </form>
                                            </td>
                                            @elseif($billing['thisMonthUnpaid'] > 0)
                                            <td><input type="checkbox" class="fs-6 form-check-input border border-primary" value="{{ $billing['customer']->id }}" onchange="addToArray(this)"></td>
                                            @else
                                            <td><button class="btn bg-success-subtle btn-sm" title="All orders have been paid; no unpaid orders remain." onclick="showMsg()"><i class="bi bi-check-circle"></i></button></td>
                                            @endif
                                            <td>{{ $billing['customer']->id }}</td>
                                            <td>{{ $billing['customer']->party_name }}</td>
                                            <td>
                                                <div style="margin: -5px;" class=" text-center bg-{{$billing['thisMonthUnpaid'] > 0 ? 'danger-subtle':''}}">{{$billing['thisMonthUnpaid']}}</div>
                                            </td>
                                            <td>{{ $billing['lastBillDate'] }}</td>
                                            <td>{{ $billing['lastBillStatus'] }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                            <div class="tab-pane fade" id="pills-custom" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="card-title p-2 text-center ">
                                    Select Customers To Generate Custom Date Bills
                                </div>
                            <div class="row p-2">
                                    <div class="col-lg-9">
                                        <div class="card">
                                            <table class="table datatable table-bordered">
                                                <thead>
                                                    <tr>
                                                        <!-- <th></th> -->
                                                        <th scope="col">Check</th>
                                                        <th scope="col">Account No</th>
                                                        <th scope="col">Party Name</th>
                                                        <th>Unpaid orders</th>
                                                        <th>Last Unpaid order</th>
                                                        <th scope="col">Last Bill Date</th>
                                                        <th scope="col">Last Bill Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($customCustomers as $billing)
                                                    <tr>
                                                        <td><input type="radio" data-id="{{ $billing['customer']->id }}" data-name="{{ $billing['customer']->party_name }}" data-toDate="" class="fs-6 form-check-input border border-primary" value="{{ $billing['customer']->id }}" name="customCustomer" onchange="addToForm(this)"></td>
                                                        <td>{{ $billing['customer']->id }}</td>
                                                        <td>{{ $billing['customer']->party_name }}</td>
                                                        <td>
                                                            <div style="margin: -5px;" class=" text-center bg-{{$billing['unpaidOrdersCount'] > 0 ? 'danger-subtle':''}}">{{$billing['unpaidOrdersCount']}}</div>
                                                        </td>
                                                        <td>{{$billing['lastUnpaidOrderDate'] }}</td>
                                                        <td>{{ $billing['lastBillDate'] }}</td>
                                                        <td>{{ $billing['lastBillStatus'] }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Create Bill</h5>
                                                <form class="row g-3" action="{{ route('Billing.show') }}" method="POST" id="customCustomerForm">
                                                    @csrf
                                                        <input type="hidden" id="selected_customers" class="form-control" name="selected_customers[]" readonly value="">
                                                    <input type="hidden" class="d-none" name="billingMonth" value="custom" id="">
                                                    <div class="col-md-12">
                                                        <label for="inputName5" class="form-label">Customer</label>
                                                        <input type="text" name="party_name" required readonly class="form-control" id="inputName">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="fromDate">From Date</label>
                                                        <input type="date" class="form-control-sm form-control" oninput="checkDate('fromDate','toDate','dateError')" name="fromDate" required id="fromDate">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="toDate">To Date</label>
                                                        <input type="date" class="form-control-sm form-control"  oninput="checkDate('fromDate','toDate','dateError')" name="toDate" required id="toDate">
                                                        <p class="fs-7 text-danger" id="dateError"></p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="dueDays">Payable Within Days</label>
                                                        <input type="number" class="form-control-sm form-control"  style="max-width: 55px;" name="dueDays" required value="10" id="dueDays">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="dueCharges">Charges after due days</label>
                                                        <input type="number" class="form-control-sm form-control" style="max-width: 80px;" name="dueCharges" required value="0" id="dueCharges">
                                                    </div>
                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-sm btn-primary">Create Bill</button>
                                                        <button type="reset" class="btn btn-sm btn-secondary">Reset</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </section>
    <div id="alertOrders" class="alert  alert-warning alert-dismissible fade" role="alert">
        <i class="bi bi-warning me-1"></i>
        All orders have been paid; no unpaid orders remain.
    </div>
    <script>
        function showMsg() {
            document.getElementById('alertOrders').classList.add('show')
            setTimeout(() => {
                document.getElementById('alertOrders').classList.remove('show')
            }, 2400)
        }
        const selectedOrders = [];
        function checkDate(fromDate , toDate, dateError){
          
            let from_date = new Date(document.getElementById(fromDate).value); 
            let to_date = new Date(document.getElementById(toDate).value); 
            let date_error = document.getElementById(dateError); 
            const today = new Date();
            from_date.setHours(0, 0, 0, 0);
    to_date.setHours(0, 0, 0, 0);
    today.setHours(0, 0, 0, 0);
            console.log(today)
            if(from_date >= to_date){
                date_error.textContent='To_Date must be greater then From_Date';
                document.getElementById(toDate).value='';
            }else if(to_date>=today){
                date_error.textContent="To_Date must be less then Today's Date";
                document.getElementById(toDate).value='';
            }
            else{
                date_error.textContent='';
            }
        }
        function addToForm(checkbox){
            let form = document.getElementById('customCustomerForm');
            const idField = document.getElementById('selected_customers');
                const nameField = document.getElementById('inputName');
            let id = checkbox.value;
            if (checkbox.checked) {
                idField.value = checkbox.getAttribute('data-id');
                nameField.value = checkbox.getAttribute('data-id');
                nameField.value += ' '+checkbox.getAttribute('data-name');
            }
        }
        function addToArray(checkbox) {
            let createBill = document.getElementById('createBill');
            createBill.classList.remove('d-none')
            let id = checkbox.value;
            // Check if the checkbox is checked or unchecked
            if (checkbox.checked) {
                // Checkbox is checked, add the order to the array
                selectedOrders.push({
                    id: id,
                });
                console.log(selectedOrders)
                showOrders()
            } else {
                // Checkbox is unchecked, remove the order from the array
                let indexToRemove = selectedOrders.findIndex(order => order.id === id);
                if (indexToRemove !== -1) {
                    selectedOrders.splice(indexToRemove, 1);

                }
                console.log(selectedOrders)
                showOrders()
                if (selectedOrders.length == 0) {
                    createBill.classList.add('d-none')
                }
            }
        }

        function addToArrayPending(checked) {
            if (checked) {

                // Uncheck all checkboxes first
                let createBill = document.getElementById('createBill');
                createBill.classList.remove('d-none')
                let allCheckboxes = document.querySelectorAll(`#orderTable tbody tr td input[type="checkbox"]`);
                allCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    let id = checkbox.value;
                    let indexToRemove = selectedOrders.findIndex(order => order.id === id);
                    if (indexToRemove !== -1) {
                        selectedOrders.splice(indexToRemove, 1);
                    }
                });

                // Check only unpaid checkboxes
                let unpaidStatus = document.querySelectorAll(`#orderTable tbody tr td input[type="checkbox"]`);
                unpaidStatus.forEach(checkbox => {

                    checkbox.checked = true;
                    let id = checkbox.value;
                    // Update the selectedOrders array
                    if (!selectedOrders.some(order => order.id === id)) {
                        selectedOrders.push({
                            id: id,
                        });
                    }
                });

                // Update the UI to reflect the changes in the selected orders
                showOrders();
                if (selectedOrders.length == 0) {
                    createBill.classList.add('d-none')
                }
            } else {
                // Unselect all unpaid orders
                let unpaidStatus = document.querySelectorAll(`#orderTable tbody tr td input[type="checkbox"]`);
                unpaidStatus.forEach(checkbox => {
                    checkbox.checked = false;
                    let id = checkbox.value;
                    let indexToRemove = selectedOrders.findIndex(order => order.id === id);
                    if (indexToRemove !== -1) {
                        selectedOrders.splice(indexToRemove, 1);
                    }
                });

                // Update the UI to reflect the changes in the selected orders
                showOrders();
                if (selectedOrders.length == 0) {
                    createBill.classList.add('d-none')
                }
            }
        }

        function showOrders() {
            let ordersWrapper = document.getElementById('inputWrapper');
            let orderCountSpan = document.getElementById('orderCount');
            ordersWrapper.parentElement.classList.add('show')
            // Clear the existing content
            ordersWrapper.innerHTML = '';

            // Update the total selected order count
            orderCountSpan.textContent = selectedOrders.length;

            // Create input elements for each selected order ID
            for (let i = 0; i < selectedOrders.length; i++) {
                let order = selectedOrders[i];

                // Create an input element for the order ID
                let idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'selected_customers[]'; // Use an array to hold order IDs
                idInput.value = order.id;

                // Append the input element to the wrapper
                ordersWrapper.appendChild(idInput);
            }
            if (selectedOrders.length == 0) {
                ordersWrapper.parentElement.classList.remove('show')
            }
        };
    </script>
</x-app-layout>