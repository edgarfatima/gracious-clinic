<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>

    @vite(['resources/scss/user/userregister.scss', 'resources/scss/footer.scss'])
</head>
<body>
    <main>
        <div class="container">
            <div class="form-default">
                <div class="form-heading">
                    <div class="heading-text">
                        <h1>CREATE ACCOUNT</h1>
                    </div>
                </div>
                <div class="form-body">
                    <form action="{{ route('user.register.process') }}" method="POST">
                        @csrf
                        @if ($errors->any())
                        <div class="form-group-col">
                                <ul class="text-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group-col">
                            <label for="">User Information</label>
                        </div>
                        <div class="form-group-col">
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" @if($errors->has('first_name')) autofocus @endif required>
                        </div>
                        <div class="form-group-col">
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" @if($errors->has('last_name')) autofocus @endif required>
                        </div>
                        <div class="form-group-col">
                            <div class="phone-number">
                                <span class="input-group-addon">+63</span>
                                <input type="text" id="number" name="number" value="{{ old('number') }}" placeholder="Number" @if($errors->has('number')) autofocus @endif required>
                            </div>
                        </div>
                        <div class="form-group-col">
                            <input type="text" id="street_address" name="street_address" value="{{ old('street_address') }}"  placeholder="Street Address"@if($errors->has('street_address')) autofocus @endif required>
                        </div>
                        <div class="form-group-row">
                            <div class="city">
                                <select name="city" id="city">
                                    <option value="">Select City</option>
                                    @foreach(App\Models\City::orderBy('name')->get() as $city)
                                        <option value="{{ $city->id }}" {{ old('city') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="province">
                                <select name="province" id="province">
                                    <option value="">Select Province</option>
                                    @foreach(App\Models\Province::orderBy('name')->get() as $province)
                                        <option value="{{ $province->id }}" {{ old('province') == $province->id ? 'selected' : ''}}>{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="country">
                                <select name="country" id="country">
                                    <option value="Philippines" default>Philippines</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group-col">
                            <label for="">Login Details</label>
                        </div>
                        <div class="form-group-col">
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Email" @if($errors->has('email')) autofocus @endif required>
                        </div>
                        <div class="form-group-col">
                            <input type="password" id="password" name="password" placeholder="Password" @if($errors->has('password')) autofocus @endif required>
                        </div>  
                        <div class="form-group-col">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                        </div>
                        <div class="form-group-row">
                                <input type="checkbox" id="accepted" name="accepted"><span> Do you accept our</span> <a href="#">Terms and Conditions</a>
                            </div>
                        <div class="form-group-col">
                            <div class="button">
                                <button type="submit" id="submitbtn">Submit</button>
                            </div>
                        </div>
                        <div class="form-group-row">
                            <span>Already have an account?</span><a href="{{ route('user.login') }}">Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @if (Session::has('success'))
    <script>
        alert("Registration successful. Please log in.");
        window.location = "{{ route('user.login') }}";
    </script>
@endif
<script>
    $(document).ready(function() {
        $('#number').on('input', function() {
            // Remove non-numeric characters
            $(this).val($(this).val().replace(/\D/g,''));

            // Limit to 11 digits
            var maxDigits = 11;
            if ($(this).val().length > maxDigits) {
                $(this).val($(this).val().slice(0, maxDigits));
            }
        });
    });
</script>
</body>
</html>
