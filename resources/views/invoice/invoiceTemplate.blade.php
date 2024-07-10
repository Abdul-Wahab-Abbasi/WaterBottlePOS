<x-app-layout>
    <div class="pagetitle">
        <h1>Invoice Template</h1>
        <nav>
            <ol class="breadcrumb">
                 
                <li class="breadcrumb-item">Invoice</li>
                <li class="breadcrumb-item active">Invoice Template</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
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
        <div class="row">
            <div class="col-xl-5">
                @foreach($invoiceTemp as $item)
                <div class="card invoice-card p-0">
                    <div class="card-title card-title-preview text-center p-1 opacity-75">Bill Preview</div>
                    <div class="card-body pt-2">
                        <div class="invoice-title text-center">
                            <div class="m-0">
                                <h2 class="card-title p-1 m-0 fs-4">{{$item->organization }}</h2>
                                <h2 class="card-title p-1 m-0 fs-6">For the Month of APR 2024</h2>
                            </div>
                            <div class="text-muted">
                                <p class="mb-1 fs-7">{{$item->address }}</p>
                                <p class=" mb-0"><i class="uil uil-phone me-1 fs-7"></i> {{$item->phone }}</p>
                            </div>
                        </div>

                        <hr class="m-1">

                        <div class="row">
                            <div class="col">
                                <div class="text-dark fw-medium">
                                    <h5 class="fs-6">Bill No: <b>#2845</b></h5>
                                    <h5 class="fs-7 mb-1">Billed To: <b>Abdul Wahab</b></h5>
                                    <p class="mb-1 fw-normal fs-7">Account No: <b>123</b></p>
                                    <p class="mb-1 fw-normal fs-7">Address: <b>4068 Post Avenue Newfolden, MN 56738</b></p>
                                    <p class="mb-1 fw-normal fs-7">Contact: <b>001-234-5678</b></p>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        <div class="py-2">
                            <h5 class="fs-6 fw-bold">Orders Summary</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered" style="width: max-content;">
                                    <thead>
                                        <tr>
                                            <th class="fs-7">DATE</th>
                                            <th class="fs-7">DESCRIPTION</th>
                                            <th class="fs-7">RATE</th>
                                            <th class="fs-7">QTY</th>
                                            <th class="fs-7">DL-BT</th>
                                            <th class="fs-7">RC-BT</th>
                                            <th class="fs-7">BAL</th>
                                            <th class="fs-7">AMOUNT</th>
                                            <th class="fs-7">CASH</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        <tr>
                                            <td class="fs-7"></td>
                                            <td class="fs-7"></td>
                                            <td class="fs-7"></td>
                                            <td class="fs-7"></td>
                                            <td class="fs-7"></td>
                                            <td class="fs-7"></td>
                                            <td class="fs-7"></td>
                                            <td class="fs-7"></td>
                                            <td class="fs-7"></td>
                                        </tr>
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div>
                            <!-- end table responsive -->
                        </div>
                        <div class="row">
                            <div class="col col-12">
                                <h5 class="fs-6 fw-bold">Previous Unpaid Bills</h5>
                                <div class="text-dark fw-medium">
                                    <h5 class="fs-6  fw-semibold">1: Last Month: 500Rs</h5>
                                    <h5 class="fs-6 mb-1 fw-semibold">2: From 1/2/24 To 5/2/24: 100Rs</h5>
                                    <h5 class="fs-6 fw-bold">Total Previous Charges: 600Rs</h5>
                                </div>
                            </div>
                            <hr>
                            <h5 class="fs-5 fw-bold">Totals</h5>
                            <div class="col col-12">
                                <h5 class="fs-6 fw-semibold">Total Current Bill: 1200Rs</h5>
                                <h5 class="fs-6 fw-semibold">Total Previous Amount: 600Rs</h5>
                                <h5 class="fs-5 fw-bold">Total Due Amount: 1800Rs</h5>
                            </div>
                            <div class="col col-12 m-0">
                                <h2 class="card-title p-1 m-0 fs-5">PAYABLE WITHIN DUE DATE: 1800Rs</h2>
                            </div>
                            <p class="fs-7">
                                <b>Benefits:</b> <br>
                                {{$item->message }}
                                 </p>
                            <p class="fs-7">
                                <b>Note:</b> <br>
                                {{$item->note }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-xl-7">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <!-- <ul class="nav nav-tabs nav-tabs-bordered">


                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">Sales</button>
                            </li>

                        </ul> -->
                        <div class="tab-content pt-0">
                            <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">
                                <h5 class="card-title">Sales Invoice & Bill Setting</h5>
                                @foreach($invoiceTemp as $item)
                                <form action="{{ route('invoiceTemp.update', $item->id) }}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="profileImage" class="form-label">Logo Image</label>
                                            <div class="d-flex gap-2 align-items-end">
                                                <div class="img-box" id="previewBox">
                                                    <img id="80765imgprofile" class="border" src="{{ asset($item->image ? 'storage/' . $item->image : 'assets/img/productDefault.jpg') }}" alt="Profile" style="max-width:140px; max-height:140px;object-fit:cover;">
                                                </div>

                                                <div class="pt-2">
                                                    <input type="file" onchange="imagePreview(event)" name="image" id="profileImage" class="d-none" value="" readonly>
                                                    <label for="profileImage" class="btn btn-primary text-white btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></label>
                                                    <!-- <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a> -->
                                                    <p class="fs-6 text-danger pt-1" id="errorMsg1"><span class="text-primary">Image size should be < 800kb</span>
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="title" class="col-md-4 col-lg-3 col-form-label">Title</label>
                                        <div class="col-md-8 col-lg-9 d-flex flex-column">
                                            <small class="text-primary">Only Effect on Invoice</small>
                                            <input name="title" required type="text" class="form-control" id="title" value="{{$item->title }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Organization" class="col-md-4 col-lg-3 col-form-label">Organization</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="organization" required type="text" class="form-control" id="Organization" value="{{$item->organization }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="note" class="col-md-4 col-lg-3 col-form-label">Note</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="note" type="text" required class="form-control" id="note" value="{{$item->note }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="message" class="col-md-4 col-lg-3 col-form-label">Benefit</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="message" type="text" required class="form-control" id="message" value="{{$item->message }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                        <div class="col-md-8 col-lg-9">
                                            <textarea name="address" required class="form-control" id="Address" value="{{$item->address }}" style="height: 100px">Near Bombay Bakery St.No 37/B</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="phone" required type="number" class="form-control" id="Phone" value="{{$item->phone}}">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form><!-- End Profile Edit Form -->
                                @endforeach
                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
            <div class="col-xl-5">
                @foreach($invoiceTemp as $item)
                <div class="card invoice-card" id="#invoicecard">
                    <div class="card-title card-title-preview text-center p-1 opacity-75">Invoice Preview</div>
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ asset($invoiceTemp[0]->image ? 'storage/' . $invoiceTemp[0]->image : 'assets/img/productDefault.jpg') }}" id="invoiceProfileImage" alt="Profile" class="rounded-circle border">
                        <h2 class="mb-1">{{$item->organization }}</h2>
                        <h3>{{$item->title }}</h3>
                        <div class="dropdown-divider"></div>
                        <table class="border-0 fs-7 mt-2">
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                            <tr>
                                <td><input type="text" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly value="123" name="id" id=""></td>
                                <td><input type="text" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly value="29-Apr-2024" name="date" id=""></td>
                                <td><input type="text" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly value="8:18:00 pm" name="time" id=""></td>
                            </tr>
                            <tr>
                                <th>CUSTOMER</th>
                                <th>ADMIN</th>
                                <th>ORDER ID</th>
                            </tr>
                            <tr>
                                <td><input type="text" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly value="Testing" name="customer" id=""></td>
                                <td><input type="text" class="border-0 shadow-none form-control p-0 m-0 fs-7" readonly value="Testing" name="admin" id=""></td>
                                <td>124</td>
                            </tr>
                        </table>
                        <span class="mt-4"></span>
                        <caption>Product Details</caption>
                        <table class="fs-7 table border-1">
                            <tr>
                                <th class="border-1">ID</th>
                                <th class="border-1">Product Name</th>
                                <th class="border-1">Qty</th>
                                <th class="border-1">Rate</th>
                                <th class="border-1">Amount</th>
                            </tr>
                            <tr>
                                <td class="border-1">12</td>
                                <td class="border-1">Testing </td>
                                <td class="border-1">2</td>
                                <td class="border-1">200</td>
                                <td class="border-1">400</td>
                            </tr>
                        </table>
                        <span class="mt-2"></span>
                        <div class="billing-details fs-7 w-100 text-start">
                            <div class="detail d-flex align-items-center justify-content-between">
                                <b>TOTAL</b>
                                <b>400</b>
                            </div>
                            <div class="detail d-flex align-items-center justify-content-between">
                                <b>RECIEVED</b>
                                <b>1000</b>
                            </div>
                            <div class="detail d-flex align-items-center justify-content-between">
                                <b>CHANGE RETURNED</b>
                                <b>600</b>
                            </div>
                        </div>
                        <div class="bottom-messages fs-7 py-2 w-100">
                            <span class="note"><b>Note:</b> <br> {{$item->note }}</span>
                            <br>
                            <span class="message"><b>Benefit:</b> <br>{{$item->message }}</span>
                            <br>
                            <p class="address m-0"><b>Address:</b><br>{{$item->address }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>