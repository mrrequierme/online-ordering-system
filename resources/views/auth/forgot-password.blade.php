<x-guest-layout>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow border-0 rounded-3">
                    <div class="card-header bg-warning text-dark text-center fw-bold">
                        <i class="bi bi-key me-2"></i> Forgot Password
                    </div>

                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">
                            Forgot your password? No problem. Just let us know your email address and we’ll email you a password reset link.
                        </p>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input type="email" name="email" id="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" placeholder="Email address" required autofocus>
                                <label for="email">Email address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary fw-semibold">
                                    <i class="bi bi-envelope-check me-1"></i> Email Password Reset Link
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer text-center bg-light">
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-box-arrow-in-left"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
