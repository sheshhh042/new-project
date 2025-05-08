<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('admin_assets/img/llcc.png') }}" type="image/png">

    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #2e59d9;
            --secondary-color: #f8f9fc;
            --accent-color: #f6c23e;
        }
        
        body {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Nunito', sans-serif;
            overflow: hidden;
        }
        
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            z-index: 1;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(5px);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }
        
        .bg-register-image {
            background: url('{{ asset('admin_assets/img/llccc.jpg') }}');
            background-position: center;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }
        
        .bg-register-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(78, 115, 223, 0.85) 0%, rgba(34, 74, 190, 0.85) 100%);
        }
        
        .form-control-user {
            border-radius: 50px;
            padding: 1.5rem 1.5rem;
            border: 2px solid #e3e6f0;
            transition: all 0.3s ease;
        }
        
        .form-control-user:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary {
            border-radius: 50px;
            padding: 1rem 2rem;
            background: var(--primary-color);
            border: none;
            transition: all 0.3s ease;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .text-gray-900 {
            color: var(--primary-color);
        }
        
        .invalid-feedback {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .register-logo {
            transition: transform 0.5s ease;
            width: 100px;
            height: auto;
        }
        
        .register-logo:hover {
            transform: rotate(15deg) scale(1.1);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(78, 115, 223, 0); }
            100% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0); }
        }
        
        .link-hover {
            position: relative;
            display: inline-block;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .link-hover::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .link-hover:hover::after {
            width: 100%;
        }
        
        .alert {
            border-radius: 50px;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .image-overlay {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            color: white;
            padding: 2rem;
            text-align: center;
            z-index: 1;
        }
        
        .image-overlay h2 {
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .image-overlay p {
            font-weight: 300;
            opacity: 0.9;
        }
        
        .form-group {
            opacity: 0;
            position: relative;
            top: 20px;
        }

        /* Fix for input groups */
        .input-group-prepend {
            margin-right: -1px;
        }
        .input-group-text {
            background-color: #fff;
            border-right: none;
            border-radius: 50px 0 0 50px !important;
            padding-left: 1.5rem;
        }
        .input-group > .form-control:not(:first-child) {
            border-left: none;
            border-radius: 0 50px 50px 0 !important;
        }
    </style>
</head>

<body>
    <!-- Animated Background Particles -->
    <div class="particles" id="particles-js"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-register-image p-0">
                                <div class="image-overlay">
                                    <h2 class="text-white mb-4 font-weight-bold floating">Join Our Community</h2>
                                        <p class="text-white">Set up your account to enjoy all resources.</p>
                                    <i class="fas fa-user-plus fa-4x floating text-white opacity-75 floating" style="animation-delay: 0.2s;"></i>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <img src="{{ asset('admin_assets/img/llcc.png') }}" alt="Logo" class="register-logo mb-3">
                                        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                                    </div>
                                    
                                    @if ($errors->any())
                                        <div class="alert alert-danger pulse mb-4">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    
                                    <form action="{{ route('register.save') }}" method="POST" class="user">
                                        @csrf
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user text-primary"></i></span>
                                                </div>
                                                <input type="text" class="form-control form-control-user @error('name') is-invalid @enderror" 
                                                    id="exampleFirstName" name="name" placeholder="Full Name" value="{{ old('name') }}" autofocus>
                                            </div>
                                            @error('name')
                                                <span class="invalid-feedback ml-4">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope text-primary"></i></span>
                                                </div>
                                                <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" 
                                                    id="exampleInputEmail" name="email" placeholder="Email Address" value="{{ old('email') }}">
                                            </div>
                                            @error('email')
                                                <span class="invalid-feedback ml-4">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
                                                    </div>
                                                    <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" 
                                                        id="exampleInputPassword" name="password" placeholder="Password">
                                                </div>
                                                @error('password')
                                                    <span class="invalid-feedback ml-4">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-check-circle text-primary"></i></span>
                                                    </div>
                                                    <input type="password" class="form-control form-control-user @error('password_confirmation') is-invalid @enderror" 
                                                        id="exampleRepeatPassword" name="password_confirmation" placeholder="Repeat Password">
                                                </div>
                                                @error('password_confirmation')
                                                    <span class="invalid-feedback ml-4">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block mt-4">
                                            <i class="fas fa-user-plus mr-2"></i> Register Account
                                        </button>
                                    </form>
                                    <hr class="my-4">
                                    <div class="text-center">
                                        <a class="link-hover" href="{{ route('login') }}">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>

    <script>
        // Initialize particles.js
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('particles-js')) {
                particlesJS('particles-js', {
                    "particles": {
                        "number": {
                            "value": 80,
                            "density": {
                                "enable": true,
                                "value_area": 800
                            }
                        },
                        "color": {
                            "value": "#ffffff"
                        },
                        "shape": {
                            "type": "circle",
                            "stroke": {
                                "width": 0,
                                "color": "#000000"
                            },
                            "polygon": {
                                "nb_sides": 5
                            }
                        },
                        "opacity": {
                            "value": 0.3,
                            "random": false,
                            "anim": {
                                "enable": false,
                                "speed": 1,
                                "opacity_min": 0.1,
                                "sync": false
                            }
                        },
                        "size": {
                            "value": 3,
                            "random": true,
                            "anim": {
                                "enable": false,
                                "speed": 40,
                                "size_min": 0.1,
                                "sync": false
                            }
                        },
                        "line_linked": {
                            "enable": true,
                            "distance": 150,
                            "color": "#ffffff",
                            "opacity": 0.2,
                            "width": 1
                        },
                        "move": {
                            "enable": true,
                            "speed": 2,
                            "direction": "none",
                            "random": false,
                            "straight": false,
                            "out_mode": "out",
                            "bounce": false,
                            "attract": {
                                "enable": false,
                                "rotateX": 600,
                                "rotateY": 1200
                            }
                        }
                    },
                    "interactivity": {
                        "detect_on": "canvas",
                        "events": {
                            "onhover": {
                                "enable": true,
                                "mode": "grab"
                            },
                            "onclick": {
                                "enable": true,
                                "mode": "push"
                            },
                            "resize": true
                        },
                        "modes": {
                            "grab": {
                                "distance": 140,
                                "line_linked": {
                                    "opacity": 1
                                }
                            },
                            "bubble": {
                                "distance": 400,
                                "size": 40,
                                "duration": 2,
                                "opacity": 8,
                                "speed": 3
                            },
                            "repulse": {
                                "distance": 200,
                                "duration": 0.4
                            },
                            "push": {
                                "particles_nb": 4
                            },
                            "remove": {
                                "particles_nb": 2
                            }
                        }
                    },
                    "retina_detect": true
                });
            }
            
            // Add animation to form elements on load
            $('.form-group').each(function(i) {
                $(this).delay(i * 200).animate({
                    opacity: 1,
                    top: 0
                }, 500);
            });
            
            // Add hover effect to card
            $('.card').hover(
                function() {
                    $(this).css('transform', 'translateY(-10px)');
                },
                function() {
                    $(this).css('transform', 'translateY(0)');
                }
            );

            // Password strength indicator
            $('#exampleInputPassword').on('keyup', function() {
                var password = $(this).val();
                var strength = 0;
                
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/)) strength++;
                if (password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;
                
                var strengthText = [ 'Weak', 'Medium', 'Strong', 'Very Strong'][strength];
                var strengthColor = ['#dc3545', '#fd7e14', '#ffc107', '#28a745', '#20c997'][strength];
                
                if (password.length > 0) {
                    $('.password-strength').remove();
                    $(this).after('<div class="password-strength small ml-4 mt-1" style="color: ' + strengthColor + '">Strength: ' + strengthText + '</div>');
                } else {
                    $('.password-strength').remove();
                }
            });
        });
    </script>
</body>
</html>