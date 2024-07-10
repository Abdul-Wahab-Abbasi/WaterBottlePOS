<x-app-layout>
<div class="pagetitle">
        <h1>Inventory</h1>
        <nav>
            <ol class="breadcrumb">
                 
                <li class="breadcrumb-item active">Inventory</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard mt-5">
         <!-- Display flash message -->
         @if(session('success'))
                        <div class="alert alert-{{ session('success')[1] }} alert-dismissible fade show" role="alert">
                            <i class="bi bi-{{ session('success')[2] }} me-1"></i>
                            {{ session('success')[0] }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
        <div class="row justify-content-center">
            <div class="col-lg-4">
            @foreach($inventory as $item)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Update Product</h5>
                        <form action="{{ route('inventory.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $item->name }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="size" class="form-label">Size in Litres</label>
                                    <input type="text" class="form-control" id="size" name="size" value="{{ $item->size }}" required>
                                </div>
                                
                                <div class="col-12">
                                    <label for="profileImage" class="form-label">Product Image <sup>Optional</sup></label>
                                    <div class="">
                                        <div class="img-box" id="previewBox">
                                            <img id="80765imgprofile" class="border" src="{{ asset($item->image ? 'storage/' . $item->image : 'assets/img/productDefault.jpg') }}" alt="Profile" style="max-width:90px; max-height:90px;object-fit:cover;">
                                        </div>

                                        <div class="pt-2">
                                            <input type="file" onchange="imagePreview(event)" name="image" id="profileImage" class="d-none @error('image') is-invalid @enderror" value="" readonly>
                                            <label for="profileImage" class="btn btn-primary text-white btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></label>
                                            <!-- <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a> -->
                                            <p class="fs-6 text-danger pt-1" id="errorMsg1"><span class="text-primary">Image size should be < 800kb</span>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-sm btn-primary">UPDATE</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Check if the session variable exists -->
    </section>

</x-app-layout>
