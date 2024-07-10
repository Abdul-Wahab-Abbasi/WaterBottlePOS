<x-app-layout>
  <div class="pagetitle">
        <h1>Customers</h1>
        <nav>
            <ol class="breadcrumb">
                 
                <li class="breadcrumb-item active">Customers</li>
            </ol>
        </nav>
    </div>
  <section class="section dashboard">
    <div class="row">
      <div class="col-lg-9">
        <div class="card top-selling overflow-auto">
          <div class="card-body pb-0">
            <h5 class="card-title">Customers</h5>
            <!-- Display flash message -->
            @if(session('success'))
            <div class="alert alert-{{ session('success')[1] }} alert-dismissible fade show" role="alert">
              <i class="bi bi-{{ session('success')[2] }} me-1"></i>
              {{ session('success')[0] }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if ($errors->any())
    <div class="alert d-flex align-items-center justify-content-between alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close fs-4" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
            <table class="table datatable">
              <thead>
                <tr>
                  <th scope="col">Account No</th>
                  <th scope="col">Party Name</th>
                  <th scope="col">Phone</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($customer as $item)
                <tr>
                  <td class="fw-bold">{{ $item->id }}</td>
                  <td><a href="{{ route('customers.show', $item->id) }}" class="text-primary fw-bold">{{ $item->party_name }}</a></td>
                  <td>0{{ $item->phone }}</td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#editProductModal{{$item->id}}" class="btn btn-sm btn-primary">Edit</a>
                    <form onsubmit="return confirm('Are You Sure?')" action="{{ route('customers.destroy', $item->id) }}" method="POST" style="display: inline-block;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                    <a href="{{ route('customers.show', $item->id) }}" class="btn btn-sm btn-info">View More</a>
                  </td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>

        </div>

      </div>
      <div class="col-lg-3">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Add New Customer</h5>
            <form class="row g-3" action="{{ route('customers.store') }}" method="POST">
              @csrf
              <div class="col-md-12">
                <label for="inputEmail3" class="form-label">Account No</label>
                <input type="text" class="form-control" readonly value="Auto Generated" disabled>
              </div>
              <div class="col-md-12">
                <label for="inputName5" class="form-label">Customer Name</label>
                <input type="text" name="party_name" class="form-control" id="inputName5">
              </div>
              <div class="col-md-12">
                <label for="phone" class="form-label">Phone No <sup>Start:03</sup></label>
                <input type="number" name="phone" pattern="03[0-9]{9}" title="Enter a valid Pakistani phone number starting with 03 and followed by 9 digits" class="form-control" id="phone">
              </div>
              <div class="col-md-12">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" placeholder="Apartment, studio, or floor" class="form-control" id="address">
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary">Add</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
              </div>
            </form>

            <!-- Edit Customer Modals -->
            @foreach($customer as $item)
            <div class="modal fade" id="editCustomerModal{{$item->id}}" tabindex="-1" aria-labelledby="editCustomerModalLabel{{$item->id}}" aria-hidden="true">
              <!-- Modal content -->
            </div>
            @endforeach


          </div>
        </div>
      </div>
    </div>
  </section>
  @foreach($customer as $item)
  <div class="modal fade" id="editProductModal{{$item->id}}" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProductModalLabel">Edit Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form  action="{{ route('customers.update', $item->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-12">
                <label for="inputEmail3" class="form-label">Account No</label>
                <input type="text" class="form-control" readonly value="{{ $item->id }}" disabled>
              </div>
              <div class="col-md-12">
                <label for="inputName5" class="form-label">Customer Name</label>
                <input type="text" name="party_name" value="{{ $item->party_name }}" class="form-control" id="inputName5">
              </div>
              <div class="col-md-12">
                <label for="phone" class="form-label">Phone No <sup>Start:03</sup></label>
                <input type="number" name="phone" value="0{{ $item->phone }}" pattern="03[0-9]{9}" title="Enter a valid Pakistani phone number starting with 03 and followed by 9 digits" class="form-control" id="phone">
              </div>
              <div class="col-md-12">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" value="{{ $item->address }}" placeholder="Apartment, studio, or floor" class="form-control" id="address">
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
  @endforeach
</x-app-layout>