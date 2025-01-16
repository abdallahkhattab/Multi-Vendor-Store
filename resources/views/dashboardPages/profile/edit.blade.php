@extends('layouts.master')

@section('title', 'Edit Profile')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-dark">Edit Profile</h1>
                <p class="text-muted">Update your personal information</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
@include('dashboard.partials.error')

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary text-white">
                <h3 class="card-title">Update Profile</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.profile.update') }}" method="POST">
                    @csrf
                    @method('patch')

                    <!-- First Name and Last Name -->
                    <div class="form-group">
                        <label for="first_name">First Name <span class="text-danger" >*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your first name" value="{{ old('first_name', $user->profile->first_name ?? '') }}" required>
                        @error('first_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter your last name" value="{{ old('last_name', $user->profile->last_name ?? '') }}" required>
                        @error('last_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Birthday -->
                    <div class="form-group">
                        <label for="birthday">Birthday</label>
                        <input type="date" name="birthday" id="birthday" class="form-control" value="{{ old('birthday', $user->profile->birthday ?? '') }}">
                        @error('birthday')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->profile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->profile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Country -->
                  <!-- Country -->
                <div class="form-group">
                    <label for="country">Country <span class="text-danger">*</span></label>
                    <select name="country" id="country" class="form-control" required>
                        @foreach (\Symfony\Component\Intl\Countries::getNames() as $code => $name)
                            <option value="{{ $code }}" {{ old('country', $user->profile->country ?? '') == $code ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('country')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Locale -->
                <div class="form-group">
                    <label for="locale">Preferred Language <span class="text-danger">*</span></label>
                    <select name="locale" id="locale" class="form-control" required>
                        @foreach (\Symfony\Component\Intl\Locales::getNames() as $localeCode => $language)
                            <option value="{{ $localeCode }}" {{ old('locale', $user->profile->locale ?? 'en') == $localeCode ? 'selected' : '' }}>
                                {{ $language }}
                            </option>
                        @endforeach
                    </select>
                    @error('locale')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                    <!-- Submit Button -->
                    <div class="text-right pt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-dark ml-3">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
