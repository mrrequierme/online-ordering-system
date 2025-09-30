<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4">
             <a href="{{route('home')}}" class="btn btn-outline-primary btn-sm mb-2">Home</a>
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-white border-0 text-center">
                   
                    <h4 class="fw-bold text-primary mb-0">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login
                    </h4>
                </div>

                <div class="card-body p-4">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-3 text-center text-success" :status="session('status')" />

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="form-floating mb-3">
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                placeholder="Enter your email"
                            >
                            <label for="email">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                required 
                                placeholder="Enter your password"
                            >
                            <label for="password">Password</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                            <label class="form-check-label" for="remember_me">Remember Me</label>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none small text-danger" href="{{ route('password.request') }}">
                                    Forgot Password?
                                </a>
                            @endif

                            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </button>
                        </div>
                    </form>

                    <!-- Sign Up -->
                    <div class="mt-4 text-center">
                        <span class="text-muted">Don’t have an account?</span>
                        <a href="{{ route('register') }}" class="btn btn-outline-success ms-2">
                            <i class="bi bi-person-plus"></i> Sign Up
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
