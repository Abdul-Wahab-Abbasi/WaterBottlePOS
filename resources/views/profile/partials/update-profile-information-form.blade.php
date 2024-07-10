<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="email">
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-success">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <!-- <div class="mb-3">
            <label for="image" class="form-label">{{ __('Profile Image') }}</label>
            <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div> -->
        <div class="mb-3">
            <label for="profileImage" class="form-label">{{ __('Profile Image') }}</label>
            <div class="">
                <div class="img-box" id="previewBox">
                    <img id="80765imgprofile" src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('assets/img/profile-img.jpg') }}" alt="Profile" style="max-width:200px; max-height:300px;object-fit:cover;">
                </div>

                <div class="pt-2">
                    <input type="file" onchange="imagePreview(event)" name="image" id="profileImage" class="d-none @error('image') is-invalid @enderror" value="" readonly>
                    <label for="profileImage" class="btn btn-primary text-white btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></label>
                    <!-- <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a> -->
                    <p class="fs-6 text-danger pt-1" id="errorMsg1"><span class="text-primary">Image size should be < 800kb</span>
                    </p>
                    @error('image')
                    <p class="fs-6 text-danger pt-1" id="errorMsg2"><span class="text-primary">{{$message}}</span>
                    </p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                {{ __('Profile Updated') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>
    </form>
</section>