<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – SA EarniX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: #fff; border-radius: 20px; padding: 40px; width: 100%; max-width: 400px; box-shadow: 0 25px 50px rgba(0,0,0,.3); }
        .login-logo { width: 64px; height: 64px; background: linear-gradient(135deg, #1e3a8a, #3b82f6); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo"><i class="fas fa-crown text-white fa-xl"></i></div>
    <h4 class="text-center fw-bold mb-1">SA EarniX Admin</h4>
    <p class="text-center text-muted small mb-4">Control Panel Login</p>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
            <i class="fas fa-sign-in-alt me-2"></i>Login to Admin Panel
        </button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
