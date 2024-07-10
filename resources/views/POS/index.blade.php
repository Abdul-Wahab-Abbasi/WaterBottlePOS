<x-app-layout>
    <div class="pagetitle">
        <h1>Manage Orders</h1>
        <nav>
            <ol class="breadcrumb">
                 
                <li class="breadcrumb-item">POS</li>
                <li class="breadcrumb-item active">Manage Orders</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col">
                <div class="card top-selling overflow-auto">
                    <div class="card-body pb-0">
                        <h5 class="card-title">Orders</h5>
                        <!-- Display flash message -->
                        @if(session('success'))
                        <div class="alert alert-{{ session('success')[1] }} alert-dismissible fade show" role="alert">
                            <i class="bi bi-{{ session('success')[2] }} me-1"></i>
                            {{ session('success')[0] }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <div class="d-flex bg-secondary-subtle w-100 p-2 px-4 m-2 border-top border-bottom border-primary justify-content-between align-items-center">
                            <div class="buttonWrapper d-flex gap-5">
                                <div class="form-check">
                                    <input class="form-check-input fs-6 border border-primary" name="operation" onchange="addToArrayPending('unreceived', this.checked,'btReceivedStatus')" type="checkbox" id="btlRc">
                                    <label class="form-check-label" for="btlRc">
                                        Select All Unreceived Bottles
                                    </label>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('POS.quik-update') }}" class="gap-1 fade d-flex bg-transparent flex-row align-items-center" style="width: max-content;">
                                @csrf <!-- CSRF Protection -->
                                <span class="fs-6">Selected Orders (<span id="orderCount"></span>)</span>
                                <div id="inputWrapper">

                                </div>
                                <div class="btn-wrapper d-none" id="unReceived">
                                    <button type="submit" class="btn btn-sm btn-success" name="submit_action" value="unReceivedUpdate">UPDATE TO Received</button>
                                </div>
                                <div class="btn-wrapper d-none" id="updateAll">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editMultipleProductModal" class="btn btn-sm btn-success">UPDATE Selected</button>
                                </div>
                            </form>
                        </div>
                        <table class="table datatable table-bordered" id="orderTable">
                            <thead>
                                <tr>
                                    <!-- <th></th> -->
                                    <th scope="col">Check</th>
                                    <th scope="col">Or.ID</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Acc.</th>
                                    <th scope="col">Party Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">DL-BT</th>
                                    <th scope="col">RC-BT</th>
                                    <th scope="col">BAL-BT</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Paid Status</th>

                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $ord)
                                <tr>
                                    <!-- <td><input type="checkbox" name="" id=""></td> -->
                                    <td class="fw-bold"><input type="checkbox" class="fs-6 form-check-input border border-primary" onchange="addToArray(this)" data-dlBottles="{{ $ord->dl_bottles }}" data-btReceivedStatus="{{$ord->dl_bottles==$ord->rc_bottles ? 'received' : 'unreceived'}}" data-cancel-status="{{$ord->status}}" data-name="{{ $ord->customer->party_name }}" data-status="{{ $ord->paid_status }}" data-invoicePaid="{{ $ord->invoicePaid }}" data-btReceived="{{$ord->rc_bottles==$ord->dl_bottles ? $ord->dl_bottles : $ord->rc_bottles}}" name="id" value="{{ $ord->id }}" id=""></td>
                                    <td class="fw-bold">{{ $ord->id }}</td>
                                    <td class="fw-bold">{{ $ord->order_date }}</td>
                                    <td class="fw-bold">{{ $ord->account_no }}</td>
                                    <td><a href="{{ route('customers.show', $ord->account_no) }}" class="text-primary fw-bold">{{ $ord->customer->party_name }}</a></td>
                                    <td class="fw-bold">
                                        @foreach($ord->orderProducts as $orderProduct)
                                        {{ $orderProduct->product_name }} &nbsp; {{ $orderProduct->size }}Ltr
                                        @endforeach
                                    </td>
                                    <td class="fw-bold">{{ $ord->dl_bottles }}</td>
                                    <td class="fw-bold">
                                        <div style="margin: -5px;" class=" text-center bg-{{ $ord->dl_bottles != $ord->rc_bottles ? 'danger-subtle' : 'success-subtle' }}">{{ $ord->rc_bottles==null? 0 : $ord->rc_bottles}}</div>
                                    </td>
                                    <td class="fw-bold">{{ $ord->dl_bottles - $ord->rc_bottles }}</td>
                                    <td class="fw-bold">Rs {{ $ord->total_amount }}</td>
                                    <td><span class="badge fs-7 bg-{{ $ord->paid_status == 'Paid' ? 'success' : ($ord->status == 'Canceled' ? 'danger' : 'warning') }}">{{$ord->status == "Canceled" ? 'Canceled' : ($ord->invoicePaid == 0 ? $ord->paid_status=="Paid" ? $ord->paid_status.'by bill' : 'Unpaid' : "Paid By Invoice")}}</span></td>
                                    <td>
                                        <div class="wrapper d-flex gap-2 align-items-center justify-content-center">
                                            @if($ord->status == "Pending"||$ord->status == "Completed")
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editProductModal{{$ord->id}}" class="btn btn-sm btn-primary fs-7"><i class="bi bi-pencil-square"></i></a>
                                            <a href="{{ route('invoice.createInvoice.show',$ord->id) }}" class="btn btn-sm btn-primary fs-7">INVOICE</a>
                                            @endif
                                            @if($ord->status == "Canceled")
                                            <form onsubmit="return confirm('Are You Sure To Confirm Delete This Order?')" action="{{ route('orders.destroy', $ord->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>
    @foreach($orders as $ord)
    @if($ord->status == "Pending"||$ord->status == "Completed")
    <div class="modal fade" id="editProductModal{{$ord->id}}" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('orders.update', $ord->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="card-title">Or.ID: {{$ord->id}}&nbsp;&nbsp;Name: {{$ord->customer->party_name}}</div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="recievedBottle{{$ord->id}}" class="form-label">Bottles Received (max:{{$ord->dl_bottles}})</label>
                                <input type="number" max="{{$ord->dl_bottles}}" min="0" onchange="qtyChange(this)" name="rc_bottles" value="{{ $ord->rc_bottles != '' ? $ord->rc_bottles : 0 }}" class="form-control" id="recievedBottle{{$ord->id}}">
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <label for="status{{$ord->id}}" class="form-label text-danger">Cancel? <p class="text-danger m-0 p-0 fs-7">{{ $ord->paid_status == 'Paid' ? "You can't delete paid orders." : 'Update if canceled, otherwise keep.' }}</p></label>
                                <select name="status" {{ $ord->paid_status == 'Paid' ? 'hidden' : '' }} class="form-select" id="status{{$ord->id}}">
                                    <option value="Pending" {{ $ord->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Canceled">Canceled</option>
                                </select>
                            </div>
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
    <div class="modal fade" id="editMultipleProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" method="POST" action="{{ route('POS.quik-update') }}">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fs-6">Click On Orders Tab to Update</h5>
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home" type="button" role="tab" aria-controls="home" aria-selected="true">Order 1</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2" id="borderedTabContent">

                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit_action" value="allUpdate" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function qtyChange(inputField) {
            var value = parseInt(inputField.value);
            // Get the maximum allowed value from the max attribute
            var maxValue = parseInt(inputField.getAttribute('max'));
            var minValue = parseInt(inputField.getAttribute('min'));
            // If the value exceeds the maximum, set it to the maximum value
            if (value >= maxValue) {
                inputField.value = maxValue;

            } else if (value < minValue) {
                inputField.value = minValue;
            } else {

            }
        }
        const selectedOrders = [];

        function addToArray(checkbox) {
            let updateAllBtn = document.getElementById('updateAll');
            updateAllBtn.classList.remove('d-none')
            let unReceivedBtn = document.getElementById('unReceived');
            unReceivedBtn.classList.add('d-none')

            let id = checkbox.value;
            let name = checkbox.getAttribute('data-name');

            let btReceived = checkbox.getAttribute('data-btReceived');
            let dlBottles = checkbox.getAttribute('data-dlBottles');
            let invoicePaid = checkbox.getAttribute('data-invoicePaid');
            let cancelStatus = checkbox.getAttribute('data-cancel-status');
            let status = checkbox.getAttribute('data-status');

            // Check if the checkbox is checked or unchecked
            if (checkbox.checked) {
                // Checkbox is checked, add the order to the array
                selectedOrders.push({
                    id: id,
                    name: name,
                    btReceived: btReceived,
                    status: status,
                    cancelStatus: cancelStatus,
                    dlBottles: dlBottles,
                    invoicePaid: invoicePaid
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
                    updateAllBtn.classList.add('d-none')
                }
            }
            generateTabs();
        }

        function addToArrayPending(type, checked, status) {
            let unReceivedBtn = document.getElementById('unReceived');
            let updateAllBtn = document.getElementById('updateAll');
            updateAllBtn.classList.add('d-none')
            if (status == 'btReceivedStatus') {
                unReceivedBtn.classList.remove('d-none')
            }
            if (checked) {
                // Uncheck all checkboxes first
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
                let unpaidStatus = document.querySelectorAll(`#orderTable tbody tr td input[data-${status}="${type}"]`);
                unpaidStatus.forEach(checkbox => {

                    checkbox.checked = true;
                    let id = checkbox.value;
                    let name = checkbox.getAttribute('data-name');
                    let btReceived = checkbox.getAttribute('data-btReceived');
                    let dlBottles = checkbox.getAttribute('data-dlBottles');
                    let cancelStatus = checkbox.getAttribute('data-cancel-status');
                    let invoicePaid = checkbox.getAttribute('data-invoicePaid');
                    let status = checkbox.getAttribute('data-status');
                    // Update the selectedOrders array
                    if (!selectedOrders.some(order => order.id === id)) {
                        selectedOrders.push({
                            id: id,
                            name: name,
                            btReceived: btReceived,
                            cancelStatus: cancelStatus,
                            status: status,
                            dlBottles: dlBottles,
                            invoicePaid: invoicePaid
                        });
                    }
                });

                // Update the UI to reflect the changes in the selected orders
                showOrders();
            } else {
                // Unselect all unpaid orders
                let unpaidStatus = document.querySelectorAll(`#orderTable tbody tr td input[data-${status}="${type}"]`);
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
                idInput.name = 'selected_orders[]'; // Use an array to hold order IDs
                idInput.value = order.id;

                // Append the input element to the wrapper
                ordersWrapper.appendChild(idInput);
            }
            if (selectedOrders.length == 0) {
                ordersWrapper.parentElement.classList.remove('show')
            }
        };

        function generateTabs() {
            // Get the container for tabs
            let tabContainer = document.getElementById('borderedTab');
            let contentContainer = document.getElementById('borderedTabContent');

            // Clear existing tabs and tab content
            tabContainer.innerHTML = '';
            contentContainer.innerHTML = '';

            // Generate tabs based on selected orders
            selectedOrders.forEach((order, index) => {
                // Create tab button
                let tabButton = document.createElement('button');
                tabButton.setAttribute('class', 'nav-link fs-7 bg-primary-subtle m-1 ' + (index === 0 ? ' active' : '') + (order.cancelStatus == 'Canceled' ? ' text-danger border-danger' : ''));
                tabButton.setAttribute('id', 'order-tab-' + index);
                tabButton.setAttribute('data-bs-toggle', 'tab');
                tabButton.setAttribute('data-bs-target', '#bordered-order-' + index);
                tabButton.setAttribute('type', 'button');
                tabButton.setAttribute('role', 'tab');
                tabButton.setAttribute('aria-controls', 'order-' + index);
                tabButton.setAttribute('aria-selected', index === 0 ? 'true' : 'false');
                tabButton.innerText = order.id + " " + order.name;

                // Create list item for the tab
                let listItem = document.createElement('li');
                listItem.setAttribute('class', 'nav-item');
                listItem.appendChild(tabButton);

                // Append list item to tab container
                tabContainer.appendChild(listItem);

                // Create tab content
                let tabContent = document.createElement('div');
                tabContent.setAttribute('class', 'tab-pane fade' + (index === 0 ? ' active show' : ''));
                tabContent.setAttribute('id', 'bordered-order-' + index);
                tabContent.setAttribute('role', 'tabpanel');
                tabContent.setAttribute('aria-labelledby', 'order-tab-' + index);

                // Add content to tab content
                if (order.cancelStatus == 'Canceled') {
                    tabContent.innerHTML = `<div class="d-flex align-items-center justify-content-center gap-2">
                    <input type="checkbox"  name="orders[${order.id}][cancel_status]" value="delete" class="fs-6 form-check-input border border-danger" id="delete${order.id}">
    <label for="delete${order.id}" class="form-label text-danger m-0">Delete ?</label>
</div>`
                } else {
                    tabContent.innerHTML = `
                <input type="hidden"  name="orders[${order.id}][cancel_status]" value="null" class="fs-6 d-none form-check-input border border-danger" id="delete${order.id}">
        <div class="col-md-12">
    <label for="recievedBottle${order.id}" class="form-label">Bottles Received (max:${order.dlBottles})</label>
    <input type="number" max="${order.dlBottles}" min="0" onchange="qtyChange(this)" name="orders[${order.id}][rc_bottles]" class="form-control" value="${order.btReceived}" id="recievedBottle${order.id}">
</div>
<hr>
<div class="col-md-12">
    <label for="status${order.id}" class="form-label text-danger">Cancel? <p class="text-danger m-0 p-0 fs-7">${order.status == 'Paid' ? "You can't delete paid orders." : 'Update if canceled, otherwise keep.'}</p></label>
    <select name="orders[${order.id}][status]" ${order.status == 'Paid' ? 'hidden' : ''} class="form-select" id="status${order.id}">
        <option value="Pending" ${order.cancelStatus == 'Pending' ? 'selected' : ''}>Pending</option>
        <option value="Canceled">Canceled</option>
    </select>
</div>

        `;
                }

                // Append tab content to container
                contentContainer.appendChild(tabContent);
            });
        }
    </script>
</x-app-layout>