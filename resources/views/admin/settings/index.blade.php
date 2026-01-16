@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users-cog"></i> User Management
            </h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Current Role</th>
                            <th>Change Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $u->role === 'admin' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($u->role) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('settings.updateRole', $u->id) }}">
                                        @csrf

                                        <select name="role" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="user" {{ $u->role == 'user' ? 'selected' : '' }}>
                                                User
                                            </option>
                                            <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>
                                                Admin
                                            </option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No users found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection