<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - LLCC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
        }
        
        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            animation: fadeInUp 0.5s ease-out;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .form-header img {
            height: 80px;
            margin-bottom: 15px;
        }
        
        .form-header h2 {
            color: #4a6baf;
            margin: 0;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: #4a6baf;
            box-shadow: 0 0 0 3px rgba(74, 107, 175, 0.1);
        }
        
        .btn-submit {
            background: linear-gradient(to right, #4a6baf, #6a8fd8);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            cursor: pointer;
        }
        
        .password-strength {
            height: 3px;
            background: #eee;
            border-radius: 3px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <img src="{{ asset('admin_assets/img/llcc.png') }}" alt="LLCC Logo" class="logo">
            <h2>Reset Password</h2>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form id="resetForm" method="POST" action="{{ route('password.reset.action') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" required minlength="8">
                <i class="fas fa-eye-slash input-icon" id="togglePassword"></i>
                <div class="password-strength">
                    <div class="strength-bar" id="password-strength-bar"></div>
                </div>
                <small id="password-strength-text" class="text-muted"></small>
            </div>
            
            <div class="mb-3 position-relative">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password" required>
                <i class="fas fa-eye-slash input-icon" id="toggleConfirmPassword"></i>
            </div>
            
            <button type="submit" class="btn btn-submit mb-3" id="submitBtn">
                <span id="submitText">Reset Password</span>
                <span class="spinner-border spinner-border-sm d-none" id="spinner"></span>
            </button>
        </form>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const field = document.getElementById('password');
            if (field.type === "password") {
                field.type = "text";
                this.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                field.type = "password";
                this.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const field = document.getElementById('password_confirmation');
            if (field.type === "password") {
                field.type = "text";
                this.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                field.type = "password";
                this.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            if (password.length === 0) {
                strengthBar.style.width = '0%';
                strengthBar.style.backgroundColor = '';
                strengthText.textContent = '';
                return;
            }
            
            let strength = 0;
            if (password.length > 7) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            const width = (strength * 25) + '%';
            strengthBar.style.width = width;
            
            switch(strength) {
                case 0:
                case 1:
                    strengthBar.style.backgroundColor = '#dc3545';
                    strengthText.textContent = 'Weak';
                    break;
                case 2:
                    strengthBar.style.backgroundColor = '#ffc107';
                    strengthText.textContent = 'Moderate';
                    break;
                case 3:
                    strengthBar.style.backgroundColor = '#17a2b8';
                    strengthText.textContent = 'Strong';
                    break;
                case 4:
                    strengthBar.style.backgroundColor = '#28a745';
                    strengthText.textContent = 'Very strong';
                    break;
            }
        });

        // Form submission
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'The passwords you entered do not match. Please try again.',
                    confirmButtonColor: '#4a6baf'
                });
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const spinner = document.getElementById('spinner');
            
            submitBtn.disabled = true;
            submitText.textContent = 'Processing...';
            spinner.classList.remove('d-none');
        });
    </script>
</body>
</html>