@if (session('success'))
    <div class="container notice notice-success" role="status">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="container notice notice-error" role="alert">
        <strong>Please check the following:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
