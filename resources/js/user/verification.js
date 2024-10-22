import axios from 'axios';

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('otpForm');
    const otpInput = document.getElementById('otp');
    const generateBtn = document.getElementById('generateBtn');
    const verifyBtn = document.getElementById('verifyBtn');
    let countdown;

    generateBtn.addEventListener('click', function() {
        generateOTP();
    });

    verifyBtn.addEventListener('click', function() {
        verifyOTP();
    });

    function generateOTP() {
        const formData = new FormData(form);

        axios.post("/account/verification/generate", formData)
            .then(function(response) {
                alert('OTP sent successfully!');
                otpInput.disabled = false; // Enable OTP input
                startTimer(60, generateBtn); // Start 60-second timer
            })
            .catch(function(error) {
                alert('Failed to generate OTP. Please try again.');
            });
    }

    function verifyOTP() {
        const formData = new FormData(form);

        axios.post("/account/verification/verify", formData)
            .then(function(response) {
                alert('OTP verified successfully!');
                window.location.href = "{{ route('user.login') }}";
            })
            .catch(function(error) {
                alert('Invalid OTP entered. Please try again.');
            });
    }

    function startTimer(duration, button) {
        let timer = duration, seconds;
        clearInterval(countdown); // Clear any existing countdown

        countdown = setInterval(function () {
            seconds = parseInt(timer % 60, 10);
            seconds = seconds < 10 ? "0" + seconds : seconds;

            button.textContent = 'Generate OTP (' + seconds + 's)';
            button.disabled = true;

            if (--timer < 0) {
                clearInterval(countdown);
                button.textContent = 'Generate OTP';
                button.disabled = false;
            }
        }, 1000);
    }
});