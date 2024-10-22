<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    @vite(['resources/scss/user/userlogin.scss', 'resources/scss/footer.scss'])
</head>
<body>
    <main>
        <div class="container">
            <div class="form-default">
                <div class="form-heading">
                    <div class="heading-text">
                        <h1>WELCOME!</h1>
                    </div>
                </div>
                <div class="form-body">
                    <form action="{{ route('user.authenticate')}}" method="POST">
                        @csrf
                        
                        @error('error')
                        <div class="form-group-col">
                            <span class="text-danger">{{ $message }}</span>
                        </div>
                        @enderror
                        <div class="form-group-col">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group-col">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                            
                        </div>  
                        <div class="form-group-col">
                            <span class="forgot-password"><a href="/forgot-password">Forgot Password?</a></span>
                        
                        </div>
                        <div class="form-group-col">
                            <div class="button">
                                <button type="submit" id="submitbtn">Submit</button>
                            </div>
                        </div>
                        <div class="form-group-col">
                            <span>Don't have an account? <a href="{{ route('user.register') }}">Sign Up</a></span>
                        </div>
                    </form>
                </div>
            </div>
    </div>
    </main>

    @include('partials.footer')
</body>
</html>