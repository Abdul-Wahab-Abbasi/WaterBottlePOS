<x-app-layout>
    <div class="pagetitle">
        <h1>Create Order</h1>
        <nav>
            <ol class="breadcrumb">
                 
                <li class="breadcrumb-item">POS</li>
                <li class="breadcrumb-item active">Create Order</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Create Order</h5>
                        <form class="row g-3" action="{{ route('POS.addOrder') }}" onsubmit="formValidation(event)" method="POST">
                            @csrf
                            <div class="col-md-12">
                                <label for="account_no" class="form-label">Select Customer</label>
                                <div class="dropdown">
                                    <input type="number" name="account_no" class="d-none" id="cus_id">
                                    <input type="text" data-bs-toggle="dropdown" aria-expanded="false" class="cus-btn-3 p-2 rounded w-100 h-100" autocomplete="off" id="account_no" placeholder="Search Customer" onfocus="handleSearch(this, 'selectSkill','cus_id')" oninput="handleSearch(this, 'selectSkill','cus_id')">
                                    <div style="max-height:270px; overflow-y:auto;" class="select w-100 dropdown-menu" id="selectSkill">
                                        @foreach($customer as $item)
                                        <textarea style="resize: none; cursor:pointer;" class="option p-0 ps-2 cus-btn-3 dropdown-item" data-value="{{ $item->id }}" readonly>{{ $item->party_name }} &nbsp;&nbsp;0{{ $item->phone }}</textarea>
                                        @endforeach
                                    </div>
                                    <p class="text-danger fs-7"></p>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <th>Pr.ID</th>
                                        <th>Pr.Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                    </thead>
                                    <tbody id="orderTableBody">

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12 text-sm-start">
                                <span class="d-flex align-items-center justify-content-center w-100 text-nowrap p-0 m-0">Total Items: &nbsp;<input id="totalItems" readonly type="text" class="form-control border-0 w-0 shadow-none d-inline p-0"></span><br>
                                <span class="d-flex align-items-center justify-content-center w-100 text-nowrap p-0 m-0">Total Quantity:&nbsp;<input id="totalQuantity" readonly name="dl_bottles" type="text" class="form-control border-0 w-0 shadow-none d-inline p-0"></span>
                                <br>
                                <b class="d-flex align-items-center justify-content-center w-100 text-nowrap p-0 m-0">Total Amount:&nbsp; <input id="totalAmount" readonly name="total_amount" type="text" class="form-control border-0 w-0 shadow-none d-inline p-0"></b>
                            </div>
                            <hr>
                            <div class="">
                                <button type="submit" onclick="formValidation(event)" value="addBill" id="billingBtn" name="submit_action" class="btn btn-primary mb-1">Add Order</button>
                                Or <br> if you are making Hand To Hand Order
                                Generate Invoice
                                <button id="invoiceGenerate" type="submit" onclick="formValidation(event)" value="generateInvoice" name="submit_action" class="btn btn-primary mt-2">Add & Generate Invoice</button>
                                <br>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card top-selling overflow-auto">
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title">Select Products</h5>
                            <input type="text" style="max-width: 300px;" class="form-control" id="searchInput" placeholder="Search">
                        </div>
                        <div class="POSproductslist pb-3">
                            @foreach($product as $item)
                            <label for="{{$item->id}}" class="icon bg-primary-subtle btn product" data-qty="{{$item->quantity}}" data-size="{{$item->size}}" data-size="{{$item->size}}" data-name="{{$item->name}}">
                                <img class="rounded" src="{{ asset($item->image ? 'storage/' . $item->image : 'assets/img/productDefault.jpg') }}" alt="" srcset="" style="width: 70%; aspect-ratio:1/1; object-fit:cover;">
                                <div class="label fs-6 fw-bold">{{$item->name}}</div>
                                <div class="fs-6 d-flex align-items-center justify-content-center"><span>Size: {{$item->size}}Ltr</span></div>
                                <input type="checkbox" data-qty="{{$item->quantity}}" data-price="{{$item->price}}" data-name="{{$item->name}}" data-size="{{$item->size}}" data-id="{{$item->id}}" onchange="addProduct(this)" name="" id="{{$item->id}}">
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </section>
    <!-- Display flash message -->
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
    <script>
        let tableBody = document.getElementById("orderTableBody");

        document.addEventListener("DOMContentLoaded", function() {
            let orderIDInput = document.getElementById('orderID');
            if (orderIDInput) {
                let orderID = parseInt(orderIDInput.value);
                if (!isNaN(orderID)) {
                    orderID += 1;
                    orderIDInput.value = orderID;
                } else {
                    console.log("Invalid orderID value:", orderIDInput.value);
                }
            } else {
                console.log("Element with id 'orderID' not found.");
            }


            var searchInput = document.getElementById("searchInput");
            searchInput.addEventListener("keyup", function() {
                var value = this.value.toLowerCase();
                var products = document.querySelectorAll(".POSproductslist .product");
                products.forEach(function(product) {
                    var qty = product.getAttribute("data-qty").toLowerCase();
                    var size = product.getAttribute("data-size").toLowerCase();
                    var name = product.getAttribute("data-name").toLowerCase();
                    if (qty.indexOf(value) > -1 || size.indexOf(value) > -1 || name.indexOf(value) > -1) {
                        product.style.display = "";
                    } else {
                        product.style.display = "none";
                    }
                });
            });
        });

        function addProduct(checkbox) {
            var productID = checkbox.getAttribute("data-id"); // Declare productName here

            if (checkbox.checked) {
                var productQty = parseFloat(checkbox.getAttribute("data-qty"));
                var productPrice = parseFloat(checkbox.getAttribute("data-price"));
                var productSize = parseFloat(checkbox.getAttribute("data-size"));
                var productName = checkbox.getAttribute("data-name"); // Declare productName here
                console.log(productName, productQty, productPrice)
                var index = tableBody.rows.length;
                var newRow = tableBody.insertRow();
                newRow.id = `TR${productID}`;
                newRow.innerHTML = `
            <td><input type="number" class="form-control shadow-none" readonly name="products[${index}][product_id]" value="${productID}" id="productID" readonly></td>
            <td><input type="text" class="form-control shadow-none" name="products[${index}][product_name]" value="${productName}" id="productName" readonly></td>
            <td><input type="number" class="form-control shadow-none" id="productQty" onchange="updateTotals();qtyChange(this)" name="products[${index}][qty]" value="1" min="1">
            <p class="text-danger fs-7"></p></td>
            <td><input type="number" class="form-control shadow-none" style="width:100%;" id="productPrice" oninput="qtyChange(this)" name="products[${index}][product_price]" min="1" value="0">
            <p class="text-danger fs-7"></p></td>
            <td><input type="text" class="form-control shadow-none d-none" name="products[${index}][size]" value="${productSize}" id="productSize" readonly></td>
        `;
                // Update totals
                updateTotals();
            } else {
                tableBody.querySelector(`#TR${productID}`).remove();
                // Update totals
                updateTotals();
            }
        }

        function updateTotals() {
            var totalItems = document.querySelectorAll("#orderTableBody tr").length;
            var totalQuantity = 0;
            var totalAmount = 0;
            var rows = document.querySelectorAll("#orderTableBody tr");
            rows.forEach(function(row) {
                var quantityInput = row.querySelector("input[name^='products'][name$='[qty]']");
                var priceInput = row.querySelector("input[name^='products'][name$='[product_price]']");
                var quantity = parseFloat(quantityInput.value);
                var price = parseFloat(priceInput.value);
                totalQuantity += quantity;
                totalAmount += quantity * price;
            });

            document.getElementById("totalItems").value = totalItems;
            document.getElementById("totalQuantity").value = totalQuantity;
            document.getElementById("totalAmount").value = totalAmount.toFixed(2); // Assuming you want to display amount with 2 decimal places
        }



        function qtyChange(inputField) {
            var value = parseInt(inputField.value);
            // Get the maximum allowed value from the max attribute
            var maxValue = parseInt(inputField.getAttribute('max'));
            var minValue = parseInt(inputField.getAttribute('min'));
            let error = inputField.parentElement.getElementsByTagName('p')[0];
            if (value < minValue) {
                inputField.value = minValue;
                error.textContent = `Minimum Value is ${minValue}!`;
                updateTotals()
            } else {
                error.textContent = ' ';
                error.innerHTML = ' ';
                updateTotals()
            }

        }

        // function convertBillingMethod(btn1, btn2) {
        //     btnBill = document.getElementById(btn1);
        //     btnInvoice = document.getElementById(btn2);
        //     btnBill.classList.toggle('d-none');
        //     btnInvoice.classList.toggle('d-none');
        // }

        function handleSearch(inputElement, selectId, cus_ID) {
            let select = document.getElementById(selectId);
            let account_no = document.getElementById(cus_ID);
            let display = inputElement;
            let selectOptions = select.querySelectorAll(".option");
            document.addEventListener("focusout", function(event) {
                if (!select.contains(event.relatedTarget)) {
                    if (display.value != "") {
                        select.classList.remove("selectionActive");
                        let filteredOption = select.querySelectorAll(".filteredOption");
                        let displayValue = filteredOption[0].getAttribute('data-value');
                        display.value = displayValue;
                    } else {
                        select.classList.remove("selectionActive");
                    }
                }
            });

            selectOptions.forEach(option => {
                option.onclick = () => {
                    for (let i = 0; i < selectOptions.length; i++) {
                        selectOptions[i].classList.remove('filteredOption')
                    }
                    let displayValue = option.getAttribute('data-value');
                    display.value = option.value;
                    account_no.value = displayValue;

                    select.classList.remove("show");
                };
            });

            display.onkeyup = () => {
                select.classList.add("selectionActive");
                filter = display.value.toUpperCase();
                for (let i = 0; i < selectOptions.length; i++) {
                    let content = selectOptions[i].value.toUpperCase();
                    if (content.indexOf(filter) > -1) {
                        selectOptions[i].style.display = "";
                        selectOptions[i].classList.add('filteredOption')
                    } else {
                        selectOptions[i].style.display = "none";
                        selectOptions[i].classList.remove('filteredOption')
                    }
                }
            };
        }

        function formValidation(e) {
            let customer = document.getElementById('cus_id');
            let index = tableBody.rows.length;
            let errorCus = customer.parentElement.getElementsByTagName('p')[0];
            if (customer.value == '' || index == 0) {
                console.log('Please Select Customer and Product')
                if (customer.value == '' && index == 0) {
                    errorCus.textContent = "Please Select Customer and Product"
                    e.preventDefault();
                } else if (customer.value == '') {
                    errorCus.textContent = "Please Select Customer"
                    e.preventDefault();
                } else if (index == 0) {
                    errorCus.textContent = "Please Select Products"
                    e.preventDefault();
                }
            } else {
                let productPrice = document.getElementById('productPrice');
                let productQty = document.getElementById('productQty');
                if (productPrice.value == 0 && productQty.value == 0) {
                    errorCus.textContent = "Please Select Price and Quantity"
                    e.preventDefault();
                } else if (productPrice.value == 0) {
                    errorCus.textContent = "Please Select Price"
                    e.preventDefault();
                } else if (productQty.value == 0) {
                    errorCus.textContent = "Please Select Product Quantity"
                    e.preventDefault();
                }
            }
        }
        // function hideModal(){
        //     modal.classList.remove("show");
        //         modal.style.display="none";
        // }
        // function validateRecievedAmount(inputEle){
        //     let total = document.getElementById('invoiceGrandTotal').value;
        //     let err = inputEle.parentElement.getElementsByClassName('InvoicePreviewAlert')[0];
        //     if(inputEle.value<total){
        //         err.innerText=`Minimum Value is ${total}`;
        //         inputEle.value=''
        //     }
        // }
    </script>
</x-app-layout>