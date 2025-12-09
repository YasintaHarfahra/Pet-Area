<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pet Area Clinic</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
        }
        
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 80%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .login-container {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .toggle-switch {
            position: relative;
            display: inline-flex;
            align-items: center;
            width: 200px;
            height: 48px;
            background: #f1f5f9;
            border-radius: 24px;
            cursor: pointer;
            overflow: hidden;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .toggle-switch-label {
            position: relative;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
        }

        .toggle-switch-handle {
            position: absolute;
            width: 50%;
            height: 100%;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            z-index: 1;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-field {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }
        
        .input-label {
            position: absolute;
            left: 20px;
            top: 16px;
            color: #64748b;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
            background: #ffffff;
            padding: 0 4px;
        }
        
        .input-field:focus + .input-label,
        .input-field:not(:placeholder-shown) + .input-label {
            top: -8px;
            font-size: 12px;
            color: #667eea;
            font-weight: 500;
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .error-message {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #f87171;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #64748b;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="gradient-bg"></div>
    
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container p-8 rounded-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
            <p class="text-gray-600">Sign in to Pet Area Clinic</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            {{-- Role Toggle --}}
            <div class="flex justify-center mb-8">
                <div id="roleToggle" class="toggle-switch" data-role="dokter">
                    <span class="toggle-switch-label dokter-label">DOKTER</span>
                    <span class="toggle-switch-label admin-label">ADMIN</span>
                    <div class="toggle-switch-handle" id="toggleHandle"></div>
                </div>
                <input type="hidden" name="role_type" id="roleType" value="dokter">
            </div>

            @if ($errors->any())
                <div class="error-message" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <strong class="font-semibold">Authentication Failed</strong>
                            <p class="text-sm mt-1">Please check your credentials and try again.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="input-group">
                <input type="text" id="login_field" name="login_field" class="input-field @error('login_field') border-red-400 @enderror" 
                       value="{{ old('login_field') }}" required autofocus placeholder=" ">
                <label for="login_field" id="loginFieldLabel" class="input-label">Email Dokter</label>
            </div>

            <div class="input-group">
                <input type="password" id="password" name="password" class="input-field @error('password') border-red-400 @enderror" 
                       required placeholder=" ">
                <label for="password" class="input-label">Password</label>
                <span class="password-toggle" onclick="togglePasswordVisibility()">
                    <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    </svg>
                </span>
            </div>

            <div class="flex items-center justify-between mb-6 text-sm">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                    <span class="ml-2 text-gray-700">Remember me</span>
                </label>
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login">
                <span class="relative z-10">Sign In</span>
            </button>
        </form>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordField.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            const roleToggle = document.getElementById('roleToggle');
            const roleTypeInput = document.getElementById('roleType');
            const loginFieldLabel = document.getElementById('loginFieldLabel');
            const loginField = document.getElementById('login_field');
            const dokterLabel = document.querySelector('.dokter-label');
            const adminLabel = document.querySelector('.admin-label');
            const toggleHandle = document.getElementById('toggleHandle');

            // Function to update visual state
            function updateToggleVisuals(currentRole) {
                roleToggle.setAttribute('data-role', currentRole);

                if (currentRole === 'dokter') {
                    loginFieldLabel.textContent = 'Email Dokter';
                    dokterLabel.style.color = 'white';
                    adminLabel.style.color = '#64748b';
                    toggleHandle.style.left = '0%';
                } else { // admin
                    roleToggle.setAttribute('data-role', 'admin');
                    loginFieldLabel.textContent = 'Username Admin';
                    dokterLabel.style.color = '#64748b';
                    adminLabel.style.color = 'white';
                    toggleHandle.style.left = '50%';
                }
                loginField.value = '';
                loginField.focus();
            }

            // Initial state
            const initialRole = roleTypeInput.value || 'dokter';
            updateToggleVisuals(initialRole);

            // Retain old input value if exists (after validation error)
            if ("{{ old('login_field') }}") {
                loginField.value = "{{ old('login_field') }}";
            }

            // Toggle click event
            roleToggle.addEventListener('click', function() {
                const currentRole = roleTypeInput.value;
                const newRole = (currentRole === 'dokter') ? 'admin' : 'dokter';
                roleTypeInput.value = newRole;
                updateToggleVisuals(newRole);
            });

            // Add smooth animations to form elements
            const inputs = document.querySelectorAll('.input-field');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });
        });
    </script>
</body>
</html>