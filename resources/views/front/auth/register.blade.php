<x-front-layout title="Register">
    <!-- Start Account Register Area -->
    <div class="account-login section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 col-md-10 offset-md-1 col-12">
                    <form class="card register-form" method="post" action="{{ route('register') }}">
                        @csrf

                        <div class="card-body">
                            <div class="title">
                                <h3>No Account? Register</h3>
                                <p>Registration takes less than a minute but gives you full control over your orders.</p>
                            </div>

                            <!-- Display Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Full Name Field -->
                            <div class="form-group">
                                <label for="reg-fn">Full Name</label>
                                <input 
                                    class="form-control" 
                                    type="text" 
                                    id="reg-fn" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    placeholder="Enter your full name"
                                >
                            </div>

                            <!-- Email Field -->
                            <div class="form-group">
                                <label for="reg-email">Email Address</label>
                                <input 
                                    class="form-control" 
                                    type="email" 
                                    id="reg-email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    placeholder="Enter your email address"
                                >
                            </div>

                            <!-- Password Field -->
                            <div class="form-group">
                                <label for="reg-pass">Password</label>
                                <input 
                                    class="form-control" 
                                    type="password" 
                                    id="reg-pass" 
                                    name="password" 
                                    required 
                                    placeholder="Enter your password"
                                >
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="form-group">
                                <label for="reg-pass-confirm">Confirm Password</label>
                                <input 
                                    class="form-control" 
                                    type="password" 
                                    id="reg-pass-confirm" 
                                    name="password_confirmation" 
                                    required 
                                    placeholder="Re-enter your password"
                                >
                            </div>

                            <!-- Submit Button -->
                            <div class="button">
                                <button class="btn" type="submit">Register</button>
                            </div>

                            <!-- Already Registered Link -->
                            <p class="outer-link">
                                Already have an account? 
                                <a href="{{ route('login') }}">Login Now</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Account Register Area -->
</x-front-layout>