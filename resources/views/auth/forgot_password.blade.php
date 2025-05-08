<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - LLCC</title>
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
            transform: translateY(20px);
            opacity: 0;
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .form-header img {
            height: 80px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }
        
        .form-header img:hover {
            transform: scale(1.05);
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
            transition: all 0.3s ease;
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
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(74, 107, 175, 0.2);
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .back-link a {
            color: #4a6baf;
            text-decoration: none;
            font-weight: 500;
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
            <h2>Forgot Password</h2>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any()))
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form id="passwordResetForm" method="POST" action="{{ route('password.forgot.action') }}">
            @csrf
            <div class="mb-3">
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your @llcc.edu.ph email" required>
            </div>
            
            <button type="submit" class="btn btn-submit">
                Send Reset Link
            </button>
            
            <div class="back-link">
                Remember your password? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('passwordResetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            
            if (!email.endsWith('@llcc.edu.ph')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Email Domain',
                    html: 'Please use your <b>@llcc.edu.ph</b> email address to forgot your password.<br></i>',
                    confirmButtonColor: '#4a6baf',
                    confirmButtonText: 'Got it!',
                    focusConfirm: false,
                    allowOutsideClick: false
                }).then(() => {
                    document.getElementById('email').focus();
                });
                return false;
            }
            
            // If validation passes, submit the form
            this.submit();
        });
    </script>
</body>
</html>