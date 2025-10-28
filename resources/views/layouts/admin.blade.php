<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentalFlow Admin</title>
    <style>
        body { font-family: system-ui; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .nav { margin-bottom: 30px; padding-bottom: 10px; border-bottom: 2px solid #ddd; }
        .nav a { margin-right: 15px; color: #3490dc; text-decoration: none; }
        .btn { padding: 8px 16px; background: #3490dc; color: white; text-decoration: none; 
               border: none; border-radius: 4px; cursor: pointer; display: inline-block; }
        .btn:hover { background: #2779bd; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f7fafc; font-weight: 600; }
        .success { padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
        .form-group input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .error { color: #e3342f; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="{{ route('admin.properties.index') }}">Properties</a>
    </div>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @yield('content')
</body>
</html>