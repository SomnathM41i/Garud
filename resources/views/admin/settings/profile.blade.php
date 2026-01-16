@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user"></i> My Profile
        </h3>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('settings.updateProfile') }}">
            @csrf

            <div class="form-group">
                <label>Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control">
            </div>

            <button class="btn btn-primary">
                <i class="fas fa-save"></i> Update Profile
            </button>
        </form>
    </div>
</div>
@endsection
