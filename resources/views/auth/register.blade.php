<x-guest-layout>
    <div class="container py-2">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow border-0 rounded-3">
                    <div class="card-header bg-primary text-white text-center fw-bold">
                        <i class="bi bi-person-plus me-2"></i> Register
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="form-floating mb-1">
                                <input type="text" name="name" id="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name') }}" placeholder="Full Name" required>
                                <label for="name">Full Name</label>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Gender -->
                            <div class="form-floating mb-1">
                                <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="" disabled selected>Choose gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                <label for="gender">Gender</label>
                                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Birthday -->
                            <div class="form-floating mb-1">
                                <input type="date" name="birthday" id="birthday" 
                                    class="form-control @error('birthday') is-invalid @enderror" 
                                    value="{{ old('birthday') }}" placeholder="Birthday" max="{{ date('Y-m-d') }}"  required>
                                <label for="birthday">Birthday</label>
                                @error('birthday') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Address -->
                            <div class="form-floating mb-1">
                                <input type="text" name="address" id="address" 
                                    class="form-control @error('address') is-invalid @enderror" 
                                    value="{{ old('address') }}" placeholder="Address" required>
                                <label for="address">Address</label>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Contact -->
                            <div class="form-floating mb-1">
                                <input type="text" name="contact" id="contact" 
                                    class="form-control @error('contact') is-invalid @enderror" 
                                    value="{{ old('contact') }}" placeholder="Contact Number" required>
                                <label for="contact">Contact Number</label>
                                @error('contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-1">
                                <input type="email" name="email" id="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}" placeholder="Email Address" required>
                                <label for="email">Email Address</label>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-floating mb-1">
                                <input type="password" name="password" id="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    placeholder="Password" required>
                                <label for="password">Password</label>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-floating mb-1">
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                    class="form-control" placeholder="Confirm Password" required>
                                <label for="password_confirmation">Confirm Password</label>
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success fw-semibold">
                                    <i class="bi bi-person-check me-1"></i> Register
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer text-center bg-light">
                        <small class="text-muted">Already have an account?</small>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm ms-2">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
