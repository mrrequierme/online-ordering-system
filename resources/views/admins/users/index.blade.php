@extends('layouts.authenticated')
@section('title', 'Users ' . config('app.name'))
@section('content')
    <div class="container">
        @if (auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-2">Create Staff</a>
        @endif
        <div class="table-responsive-sm">
            <table class="table table-striped">
                <thead class="border">
                    <tr>
                        <th>Name</th>
                        <th>Birthday</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-capitalize">{{ $user->name }}</td>
                            <td>{{ $user->birthday->format('d-M-Y') }}</td>
                            <td class="text-capitalize">{{ $user->gender }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->contact }}</td>
                            <td class="text-capitalize">{{ $user->address }}</td>
                            <td class="text-capitalize">{{ $user->role }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
