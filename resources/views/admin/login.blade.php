<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Login</title>

    @vite(['resources/scss/admin/adminlogin.scss', 'resources/scss/footer.scss'])
</head>
<body>
    <div class="container">
            <div class="form-default">
                <div class="form-heading">
                    <div class="heading-text">
                        <h1>ADMIN LOGIN</h1>
                    </div>
                </div>
                <div class="form-body">
                    <form action="{{ route('admin.authenticate') }}" method="POST">
                        @csrf
                        <div class="form-control">
                            @if(session('error'))
                                <p class="invalid-feedback">{{ session('error') }}</p>
                            @endif
                        </div>
                        <div class="form-control">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-control">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>  
                        <div class="form-control">
                            <div class="button">
                                <button type="submit">Log In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
    @include('partials.footer')
</body>
</html>