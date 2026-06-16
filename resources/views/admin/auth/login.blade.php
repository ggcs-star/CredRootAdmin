<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CredRoot — Login</title>

    <!-- Tailwind + Font Awesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
        }

        .brand-gradient {
            background: linear-gradient(135deg, #0f172a, #1e293b);
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e3a5f, #0f2b4a);
            transition: all 0.3s ease;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.25);
        }
        .btn-primary:active {
            transform: translateY(0px);
        }

        .input-field {
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s ease;
            background: #fafbfc;
        }
        .input-field:focus {
            border-color: #1e3a5f;
            box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.15);
            background: white;
        }

        .side-image {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92), rgba(30, 58, 95, 0.92)),
                        url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');
            background-size: cover;
            background-position: center;
        }

        .auth-card {
            background: white;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .text-brand {
            color: #1e3a5f;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .input-with-icon {
            padding-left: 44px !important;
        }

        .brand-logo-circle {
            background: linear-gradient(135deg, #1e3a5f, #0f172a);
            box-shadow: 0 4px 15px rgba(30, 58, 95, 0.3);
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(4px);
        }
    </style>
</head>

<body>

    <div class="min-h-screen flex">
        <!-- LEFT SIDE: Branding -->
        <div class="hidden lg:flex lg:w-1/2 side-image text-white p-12 flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-shield-halved text-white text-2xl"></i>
                    </div>
                    <span class="text-3xl font-bold tracking-tight">CredRoot</span>
                </div>

                <div class="max-w-md">
                    <h1 class="text-4xl font-bold mb-6 leading-tight">Secure &amp; Smart<br />Credential Management</h1>
                    <p class="text-lg text-white/90 mb-8 leading-relaxed">
                        Manage access, track authentication, and control permissions
                        with powerful admin tools — all from one dashboard.
                    </p>

                    <div class="space-y-4">
                        <div class="feature-card rounded-xl p-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-shield-alt text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Bank-grade Security</h4>
                                <p class="text-sm text-white/80">256-bit encryption &amp; audit logs</p>
                            </div>
                        </div>
                        <div class="feature-card rounded-xl p-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-fingerprint text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Biometric Authentication</h4>
                                <p class="text-sm text-white/80">Multi-factor &amp; identity verification</p>
                            </div>
                        </div>
                        <div class="feature-card rounded-xl p-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-users-cog text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white">Role-based Access</h4>
                                <p class="text-sm text-white/80">Manage admins, users, and groups</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-white/70 text-sm">
                <p>© 2025 CredRoot — all rights reserved.</p>
            </div>
        </div>

        <!-- RIGHT SIDE: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
            <div class="w-full max-w-md">

                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="flex items-center gap-3">
                        <div class="brand-gradient w-14 h-14 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <span class="text-3xl font-bold text-slate-900">CredRoot</span>
                    </div>
                </div>

                <!-- Login Card -->
                <div class="auth-card p-8 lg:p-10">

                    <!-- Header -->
                    <div class="text-center mb-10">
                        <div class="brand-logo-circle w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl mx-auto mb-4 shadow-lg">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">Welcome Back</h2>
                        <p class="text-slate-500 text-sm mt-1">Sign in to your admin account</p>
                    </div>

                    <!-- Error Message -->
                    @if(session('error'))
                    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-red-600 text-sm flex items-center gap-3">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                            <div class="relative">
                                <i class="fas fa-envelope input-icon"></i>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="admin@credroot.com"
                                    class="input-field w-full rounded-xl py-3.5 input-with-icon focus:outline-none placeholder:text-slate-400"
                                />
                            </div>
                            @error('email')
                            <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle text-xs"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                            <div class="relative">
                                <i class="fas fa-key input-icon"></i>
                                <input
                                    type="password"
                                    name="password"
                                    required
                                    placeholder="••••••••"
                                    class="input-field w-full rounded-xl py-3.5 input-with-icon pr-12 focus:outline-none placeholder:text-slate-400"
                                />
                                <button
                                    type="button"
                                    onclick="togglePassword()"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                                >
                                    <i id="passwordToggle" class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                            <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle text-xs"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="flex justify-between items-center">
                            <label class="flex items-center text-sm text-slate-600 cursor-pointer hover:text-slate-800 transition-colors">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand focus:ring-brand/20" />
                                <span class="ml-2">Remember me</span>
                            </label>
                            <a href="#" class="text-sm text-brand hover:underline font-medium">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-semibold text-base">
                            <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                        </button>
                    </form>

                    <!-- Sign up link -->
                    <div class="text-center pt-6 mt-6 border-t border-slate-100">
                        <p class="text-sm text-slate-600">
                            Don't have an account?
                            <a href="#" class="text-brand font-semibold hover:underline ml-1 transition-colors">
                                Contact Administrator
                            </a>
                        </p>
                    </div>

                    <!-- Security Badge -->
                    <div class="mt-6 text-center text-xs text-slate-400 flex items-center justify-center gap-4">
                        <span><i class="fas fa-lock mr-1"></i> 256-bit encrypted</span>
                        <span class="w-px h-4 bg-slate-200"></span>
                        <span><i class="fas fa-shield-alt mr-1"></i> SSL secured</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.querySelector('input[name="password"]');
            const icon = document.getElementById('passwordToggle');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</html>