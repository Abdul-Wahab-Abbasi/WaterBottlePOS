<x-app-layout>
<div class="pagetitle">
        <h1>Customer Detail</h1>
        <nav>
            <ol class="breadcrumb">
                 
                <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                <li class="breadcrumb-item active">Customer Detail</li>
            </ol>
        </nav>
    </div>  
<section class="section contact profile">
    <div class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body profile-card text-start pt-4 d-flex flex-column align-items-center">
                <h2 class="w-100">Customer Details</h2>
                <h3 class="w-100 mt-3">Customer Account No:<b>&nbsp;&nbsp;{{ $customer ->id }}</b></h3>
                <h3 class=" w-100">Customer Name: <b>&nbsp;&nbsp;{{$customer ->party_name}}</b></h3>
                <h3 class=" w-100">Account Start Date & Time: <b>&nbsp;&nbsp;{{$customer ->created_at}}</b></h3>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="info-box card d-flex align-items-center flex-row justify-content-between">
              <div>
                <i class="bi bi-geo-alt"></i>
                <h3>Address</h3>
              </div>
              <p class="p-2 ms-2">{{ $customer ->address }}</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <div class="info-box card d-flex align-items-center flex-row justify-content-between">
              <div>
                <i class="ri-shopping-cart-fill"></i>
                <h3>Total Orders</h3>
              </div>
              <b class="fs-3">{{ $ordersCount }}</b>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="info-box card d-flex align-items-center flex-row justify-content-between">
              <div>
                <i class="bi bi-cart-check-fill"></i>
                <h3>Bottles Delivered</h3>
              </div>
              <b class="fs-3">{{ $totalBottlesOrdered }}</b>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="info-box card d-flex align-items-center flex-row justify-content-between">
              <div class="_">
                <i class="bi bi-cart-plus-fill"></i>
                <h3>Bottles Recieved</h3>
              </div>
              <b class="fs-3">{{ $recievedBottlesOrdered }}</b>
            </div>
          </div>
        </div>



      </div>
      <div class="col-12">

        <div class="card">
          <div class="card-body pt-3">
            <h5 class="card-title">Order Records</h5>
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">

              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#this-week">This Week</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#this-month">This Month</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#this-year">This Year</button>
              </li>

            </ul>
            <div class="tab-content pt-2">

              <div class="tab-pane fade show active profile-overview" id="this-week">
                <h5 class="card-title">This Week</h5>
                <table class="table table-borderless datatable">
                  <thead>
                    <tr>
                      <th scope="col">Or.ID</th>
                      <th scope="col">Date</th>
                      <th scope="col" title="delivered Bottles">Dl Bottles</th>
                      <th scope="col" title="Recieved Bottles">Recieved</th>
                      <th scope="col" title="Pending Bottles">Pending</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Paid Status</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($ordersThisWeek as $order)
                    <tr>
                      <td>{{ $order->id }}</td>
                      <td>{{ $order->order_date }}</td>
                      <td class="text-center">{{ $order->dl_bottles }}</td>
                      <td class="text-center">{{ $order->rc_bottles ?? '0' }}</td>
                      <td class="text-center">{{ $order->dl_bottles - $order->rc_bottles }}</td>
                      <td>Rs {{$order->total_amount}}</td>
                      <td><span class="text-capitalize">{{$order->paid_status}}</span></td>
                      <td><span class="badge bg-{{ $order->status == 'Completed' ? 'success' : ($order->status == 'Pending' ? 'warning' : 'danger') }}">{{$order->status}}</span></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <div class="tab-pane fade profile-edit pt-3" id="this-month">
                <h5 class="card-title">This Month</h5>
                <table class="table table-borderless datatable">
                  <thead>
                    <tr>
                      <th scope="col">Date</th>
                      <th scope="col" title="delivered Bottles">Dl Bottles</th>
                      <th scope="col" title="Recieved Bottles">Recieved</th>
                      <th scope="col" title="Pending Bottles">Pending</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($ordersThisMonth as $order)
                    <tr>
                      <td><a href="#" class="text-primary">{{ $order->order_date }}</a></td>
                      <td class="text-center">{{ $order->dl_bottles }}</td>
                      <td class="text-center">{{ $order->rc_bottles ?? '0' }}</td>
                      <td class="text-center">{{ $order->dl_bottles - $order->rc_bottles }}</td>
                      <td>Rs {{$order->total_amount}}</td>
                      <td><span class="badge bg-{{ $order->status == 'Completed' ? 'success' : ($order->status == 'Pending' ? 'warning' : 'danger') }}">{{$order->status}}</span></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <div class="tab-pane fade pt-3" id="this-year">
                <h5 class="card-title">This Year</h5>
                <table class="table table-borderless datatable">
                  <thead>
                    <tr>
                      <th scope="col">Date</th>
                     
                      <th scope="col" title="delivered Bottles">Dl Bottles</th>
                      <th scope="col" title="Recieved Bottles">Recieved</th>
                      <th scope="col" title="Pending Bottles">Pending</th>
                      <th scope="col">Amount</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($ordersThisYear as $order)
                    <tr>
                      <td><a href="#" class="text-primary">{{ $order->order_date }}</a></td>
                 
                      <td class="text-center">{{ $order->dl_bottles }}</td>
                      <td class="text-center">{{ $order->rc_bottles ?? '0' }}</td>
                      <td class="text-center">{{ $order->dl_bottles - $order->rc_bottles }}</td>
                      <td>Rs {{$order->total_amount}}</td>
                      <td><span class="badge bg-{{ $order->status == 'Completed' ? 'success' : ($order->status == 'Pending' ? 'warning' : 'danger') }}">{{$order->status}}</span></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

            </div><!-- End Bordered Tabs -->

          </div>
        </div>

      </div>
    </div>
  </section>
  <script>
    function updateBadge(table) {
      let badge = document.querySelectorAll(`${table} tbody tr td .badge`);
      console.log(badge)
    }
  </script>
</x-app-layout>