<x-app-layout>
    <div class="pagetitle">
        <h1>Manage Bills</h1>
        <nav>
            <ol class="breadcrumb">
                 
                <li class="breadcrumb-item active">Manage Billing</li>
            </ol>
        </nav>
    </div>
    @if(session('success'))
    <div class="alert alert-{{ session('success')[1] }} alert-dismissible fade show" role="alert">
        <i class="bi bi-{{ session('success')[2] }} me-1"></i>
        {{ session('success')[0] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-{{ session('success')[1] }} alert-dismissible fade show" role="alert">
        <i class="bi bi-{{ session('success')[2] }} me-1"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
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
                        </ul>
                        <div class="tab-content border border-success rounded pt-2" id="myTabContent">
                            <div class="tab-pane fade active show" id="pills-monthly" role="tabpanel" aria-labelledby="home-tab">
                                <div class="card-title p-2 text-center ">
                                    Manage Bills
                                </div>
                                <div class="d-flex p-2 justify-content-between align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary fs-7" onclick="selectAll()">Select All</button>
                                    <button type="button" id="modalBtn" data-bs-toggle="modal" data-bs-target="#editMultipleProductModal" class="btn btn-sm btn-success fade fs-7">UPDATE BILLS</button>
                                </div>
                                <table class="table datatable table-bordered" id="orderTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Check</th>
                                            <th scope="col">Bill Id</th>
                                            <th scope="col">Customer Id</th>
                                            <th scope="col">Party Name</th>
                                            <th>Title</th>
                                            <th scope="col">From Date</th>
                                            <th scope="col">To Date</th>
                                            <th scope="col">Due Date</th>
                                            <th scope="col">Charges</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Bill Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bills as $bill)
                                        <tr>
                                            <td><input type="checkbox" class="fs-6 form-check-input border border-primary" data-charges="{{ $bill->charges }}" data-party="{{ $bill->party_name }}" data-todayDate="{{now()->today()->toDateString()}}" data-dueDate="{{ $bill->due_date }}" data-total="{{ $bill->due_date < now()->today() ? $bill->total + $bill->charges :  $bill->total}}" value="{{ $bill->id }}" onchange="addToArray(this)"></td>
                                            <td>{{ $bill->id }}</td>
                                            <td>{{ $bill->customer_id }}</td>
                                            <td>{{ $bill->party_name }}</td>
                                            <td>{{ $bill->title }}</td>
                                            <td>{{ $bill->from_date }}</td>
                                            <td>{{ $bill->to_date->toDateString() }}</td>
                                            <td><span class="w-100 d-block {{ $bill->due_date < now()->today() ? 'text-danger bg-danger-subtle' : '' }}">{{ $bill->due_date->toDateString() }}</span></td>
                                            <td>{{ $bill->charges }}</td>
                                            <td>{{ $bill->total }}</td>
                                            <td><span class="badge fs-7 bg-{{ $bill->bill_status == 'paid' ? 'success' : 'danger' }}">{{ $bill->bill_status }}</span></td>
                                            <td>
                                                <div class="wrapper d-flex gap-2 align-items-center justify-content-center">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editProductModal{{$bill->id}}" class="btn btn-sm btn-primary fs-7"><i class="bi bi-pencil-square"></i></a>
                                                    <form onsubmit="return confirm('Are You Sure To Delete This Bill?')" action="{{ route('Billing.destroy', $bill) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                                    </form>

                                                    <form method="POST" action="{{ route('Billing.show') }}" class="gap-2 d-flex bg-transparent flex-row align-items-center" style="width: max-content;">
                                                        @csrf <!-- CSRF Protection -->

                                                        <input type="hidden" class="form-control-sm form-control d-none" style="max-width: 55px;" name="dueDays" required value="{{$bill->to_date->diffInDays($bill->due_date)}}" id="dueDays">

                                                        <input type="hidden" class="form-control-sm form-control d-none" style="max-width: 80px;" name="dueCharges" required value="0" id="dueCharges">
                                                        <input type="hidden" class="form-control-sm form-control d-none" style="max-width: 80px;" name="fromDate" required value="{{$bill->from_date}}">
                                                        <input type="hidden" class="form-control-sm form-control d-none" style="max-width: 80px;" name="toDate" required value="{{$bill->to_date}}">

                                                        <input type="number" name="selected_customers[]" class="d-none" value="{{ $bill->customer_id }}">
                                                        <input type="hidden" name="billingMonth" class="d-none" value="{{$bill->title}}" id="">
                                                        <div class="btn-wrapper" id="createBill">
                                                            <button type="submit" class="btn btn-sm btn-success" title="Already Created" name="submit_action">View Bill</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div><!-- End Pills Tabs -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editMultipleProductModal" tabindex="-1" aria-labelledby="editMultipleProductModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Bills</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="modal-body" method="POST" action="{{ route('billing.updateStatus') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title fs-6">Click On Bills Tab To View</h5>
                                <!-- Bordered Tabs -->
                                <ul class="nav nav-tabs nav-tabs-bordered" id="tabWrapper" role="tablist">

                                </ul>
                                <div class="tab-content pt-2" id="borderedTabContent">

                                </div><!-- End Bordered Tabs -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                            <button type="submit" name="submit_action" value="allUpdate" class="btn btn-primary">UPDATE TO PAID</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @foreach($bills as $bill)
        @if($bill->bill_status == "unpaid")
        <div class="modal fade" id="editProductModal{{$bill->id}}" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Bill</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route('Billing.update')}}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-body">
                            <div class="card-title">Bill.ID: {{$bill->id}}&nbsp;&nbsp;Name: {{$bill->party_name}}</div>
                            <div class="row g-3">
                                <input type="hidden" class="d-none" name="id" value="{{$bill->id}}">
                                <div class="col-md-12">
                                    <label for="charges{{$bill->id}}" class="form-label">Charges</label>
                                    <input type="number" name="charges" value="{{$bill->charges}}" class="form-control" id="charges{{$bill->id}}">
                                </div>
                                <div class="col-md-12">
                                    <label for="dueDate{{$bill->id}}" class="form-label">Due Date</label>
                                    <input type="date" name="dueDate" value="{{$bill->due_date}}" class="form-control" id="dueDate{{$bill->id}}">
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </section>
    <script>
        function selectAll() {
            // Get all checkboxes
            let checkboxes = document.querySelectorAll('table tbody tr td input[type="checkbox"]');

            // Check if any checkbox is unchecked
            let isAnyUnchecked = Array.from(checkboxes).some(function(checkbox) {
                return !checkbox.checked;
            });

            // If any checkbox is unchecked, check all checkboxes
            // If all checkboxes are already checked, uncheck all checkboxes
            let newValue = isAnyUnchecked ? true : false;

            // Iterate through each checkbox
            checkboxes.forEach(function(checkbox) {
                // Update the checked status of each checkbox
                checkbox.checked = newValue;

                // Call the addToArray function if the checkbox is checked
                if (newValue) {
                    addToArray(checkbox);
                } else {
                    // If the checkbox is unchecked, remove its corresponding tab and tab content
                    let id = checkbox.value;
                    let tabToRemove = document.getElementById('order-tab-' + id);
                    let tabContentToRemove = document.getElementById('order-' + id);
                    if (tabToRemove && tabContentToRemove) {
                        tabToRemove.remove();
                        tabContentToRemove.remove();
                    }
                }
            });
            checkSelectedBox()
        }

        function checkSelectedBox() {
            let checkboxes = document.querySelectorAll('table tbody tr td input[type="checkbox"]');
            let anyChecked = false;
            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    anyChecked = true;
                    break; // Exit the loop early if any checkbox is checked
                }
            }

            // Add or remove the 'show' class based on whether any checkbox is checked
            if (anyChecked) {
                document.getElementById('modalBtn').classList.add('show');
            } else {
                document.getElementById('modalBtn').classList.remove('show');
            }
        }

        function addToArray(checkbox) {

            let id = checkbox.value;
            let dueDate = checkbox.getAttribute('data-dueDate');
            let todayDate = checkbox.getAttribute('data-todayDate');
            let charges = checkbox.getAttribute('data-charges');
            let party = checkbox.getAttribute('data-party');
            let total = checkbox.getAttribute('data-total');
            let tabWrapper = document.getElementById('tabWrapper');

            // Check if the checkbox is checked or unchecked
            if (checkbox.checked) {
                // Checkbox is checked, generate a new tab
                let newTab = document.createElement('button');
                newTab.classList.add('nav-link');
                newTab.setAttribute('id', 'order-tab-' + id);
                newTab.setAttribute('data-bs-toggle', 'tab');
                newTab.setAttribute('data-bs-target', '#order-' + id);
                newTab.setAttribute('type', 'button');
                newTab.setAttribute('role', 'tab');
                newTab.setAttribute('aria-controls', 'order-' + id);
                newTab.setAttribute('aria-selected', 'true');
                newTab.textContent = 'Bill ' + id;

                // Append the new tab to the tab wrapper
                tabWrapper.appendChild(newTab);

                // Generate the corresponding tab content
                let newTabContent = document.createElement('div');
                newTabContent.classList.add('tab-pane', 'fade');
                newTabContent.setAttribute('id', 'order-' + id);
                newTabContent.setAttribute('role', 'tabpanel');
                newTabContent.setAttribute('aria-labelledby', 'order-tab-' + id);
                newTabContent.innerHTML = `
                <input type="hidden"  required readonly value="${id}" name="bill_id" class="form-control d-none">
        <p>Name: <b>${party}</b></p>
        <p>Due Date: <b class="${dueDate<todayDate?'text-danger bg-danger-subtle':''}">${dueDate}</b></p>
        <p>Today's Date: <b>{{now()->today()->toDateString()}}</b></p>
        <p>Charges: <b>${charges}</b></p>
        <p>Total: <b>${total-charges}</b></p>
        <div class="col-md-12">
        <label for="Received${id}" class="form-label">Amount Received (max:${dueDate<todayDate? total:total-charges})</label>
        <input type="number" required readonly value="${dueDate<todayDate? total:total-charges}" max="${total}" min="${total}" name="received${id}" class="form-control" id="Received${id}">
        </div>
        <hr>`
                // Append the new tab content to the tab content wrapper
                document.getElementById('borderedTabContent').appendChild(newTabContent);
            } else {
                // Checkbox is unchecked, remove the corresponding tab and tab content
                let tabToRemove = document.getElementById('order-tab-' + id);
                let tabContentToRemove = document.getElementById('order-' + id);
                if (tabToRemove && tabContentToRemove) {
                    tabToRemove.remove();
                    tabContentToRemove.remove();
                }
            }
            checkSelectedBox()
        }
    </script>
</x-app-layout>