@extends('layout.admin')

@section('title', 'Admin accounts')

@section('content')
    <div class="admin-page-heading">
        <div><span class="eyebrow">TEAM ACCESS</span>
            <h1>Admin accounts</h1>
            <p>Only the super admin can invite another administrator.</p>
        </div>
    </div>
    <div class="admin-columns">
        <div class="admin-panel">
            <h2>Team members</h2>
            @foreach ($admins as $admin)
                <div class="team-row"><span
                        class="avatar">{{ strtoupper(substr($admin->name, 0, 1)) }}</span><span><strong>{{ $admin->name }}</strong><small>{{ $admin->email }}</small></span><span
                        class="status-pill">{{ str_replace('_', ' ', ucfirst($admin->role)) }}</span></div>
            @endforeach
        </div>
        <aside class="admin-panel">
            <h2>Create an admin account</h2>
            <p class="muted">Share the credentials privately with your team member.</p>
            <form method="post" action="{{ route('admin.accounts.store') }}" class="stack-form">
                @csrf
                <div class="field"><label for="name">Full name</label><input id="name" name="name"
                        value="{{ old('name') }}" required></div>
                <div class="field"><label for="email">Email</label><input id="email" name="email" type="email"
                        value="{{ old('email') }}" required></div>
                <div class="field"><label for="password">Password</label><input id="password" name="password"
                        type="password" autocomplete="new-password" required></div>
                <div class="field"><label for="password_confirmation">Confirm password</label><input
                        id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                        required></div>
                <button class="button button-dark" type="submit">Create admin</button>
            </form>
        </aside>
    </div>
@endsection
