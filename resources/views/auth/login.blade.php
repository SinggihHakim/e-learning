<x-guest-layout>
    <style>
        /* Card header */
        .login-card-header { text-align: center; margin-bottom: 2rem; }

        .login-logo-wrap {
            width: 58px; height: 58px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.125rem;
            box-shadow: 0 6px 18px rgba(79,70,229,0.35);
        }

        .login-heading {
            font-size: 1.5rem; font-weight: 800;
            color: #0f172a; letter-spacing: -0.4px;
            margin: 0 0 0.3rem;
        }

        .login-subheading {
            font-size: 0.875rem; color: #64748b; font-weight: 400; margin: 0;
        }

        /* Session status */
        .session-alert {
            background: #f0fdf4; border: 1px solid #bbf7d0;
            color: #15803d; border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.8125rem; font-weight: 500;
            margin-bottom: 1.25rem;
        }

        /* Form */
        .lf-group { margin-bottom: 1.125rem; }
        .lf-label {
            display: block;
            font-size: 0.8125rem; font-weight: 600;
            color: #374151; margin-bottom: 0.45rem;
            letter-spacing: 0.01em;
        }
        .lf-input-wrap { position: relative; }
        .lf-input-icon {
            position: absolute; left: 0.875rem; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; pointer-events: none;
            display: flex; align-items: center;
        }
        .lf-input {
            width: 100%;
            padding: 0.75rem 0.875rem 0.75rem 2.75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9375rem; font-weight: 400;
            color: #0f172a;
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none; box-sizing: border-box;
        }
        .lf-input:focus {
            border-color: #4f46e5; background: white;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
        }
        .lf-input::placeholder { color: #c1c9d4; }
        .lf-input-error { font-size: 0.75rem; color: #ef4444; margin-top: 0.4rem; font-weight: 500; }

        /* Password toggle */
        .lf-pw-input { padding-right: 3rem; }
        .lf-pw-toggle {
            position: absolute; right: 0.875rem; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer; color: #94a3b8;
            padding: 0.2rem; display: flex; align-items: center;
            border-radius: 5px;
            transition: color 0.2s;
        }
        .lf-pw-toggle:hover { color: #4f46e5; }

        /* Row remember/forgot */
        .lf-row-bottom {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .lf-remember-label {
            display: flex; align-items: center; gap: 0.5rem; cursor: pointer;
        }
        .lf-remember-check {
            width: 15px; height: 15px;
            accent-color: #4f46e5; cursor: pointer;
        }
        .lf-remember-text {
            font-size: 0.8125rem; color: #475569;
            font-weight: 500; user-select: none;
        }
        .lf-forgot-link {
            font-size: 0.8125rem; color: #4f46e5;
            font-weight: 600; text-decoration: none;
            transition: color 0.2s;
        }
        .lf-forgot-link:hover { color: #2563eb; text-decoration: underline; }

        /* Submit button */
        .lf-submit-btn {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: white; font-size: 0.9375rem; font-weight: 700;
            border: none; border-radius: 10px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            letter-spacing: 0.01em;
            box-shadow: 0 4px 14px rgba(79,70,229,0.35);
        }
        .lf-submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79,70,229,0.45);
            background: linear-gradient(135deg, #1d4ed8, #4338ca);
        }
        .lf-submit-btn:active { transform: translateY(0); box-shadow: 0 2px 8px rgba(79,70,229,0.3); }
        .lf-submit-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

        /* Spinner */
        .lf-spinner {
            display: none;
            width: 18px; height: 18px;
            border: 2.5px solid rgba(255,255,255,0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: lf-spin 0.7s linear infinite;
        }
        @keyframes lf-spin { to { transform: rotate(360deg); } }

        /* Error badge */
        .lf-error-banner {
            background: #fef2f2; border: 1px solid #fecaca;
            color: #b91c1c; border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.8125rem; font-weight: 500;
            margin-bottom: 1.25rem;
            display: flex; align-items: flex-start; gap: 0.5rem;
        }
    </style>

    <!-- Card Header -->
    <div class="login-card-header">
        <div class="login-logo-wrap">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
        </div>
        <h1 class="login-heading">Selamat Datang</h1>
        <p class="login-subheading">Masuk ke akun E-Learning Anda</p>
    </div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="session-alert">{{ session('status') }}</div>
    @endif

    {{-- Error Banner --}}
    @if ($errors->any())
        <div class="lf-error-banner">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span>Email atau password yang Anda masukkan salah. Silakan coba lagi.</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf

        {{-- Email --}}
        <div class="lf-group">
            <label for="email" class="lf-label">Alamat Email</label>
            <div class="lf-input-wrap">
                <span class="lf-input-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="lf-input @error('email') lf-input-err @enderror"
                    placeholder="nama@email.com"
                    required autofocus autocomplete="username"
                >
            </div>
            @error('email')
                <p class="lf-input-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="lf-group">
            <label for="password" class="lf-label">Password</label>
            <div class="lf-input-wrap">
                <span class="lf-input-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="lf-input lf-pw-input @error('password') lf-input-err @enderror"
                    placeholder="Masukkan password"
                    required autocomplete="current-password"
                >
                <button type="button" class="lf-pw-toggle" id="toggle-pw" aria-label="Tampilkan/sembunyikan password">
                    {{-- Eye icon (show) --}}
                    <svg id="icon-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    {{-- Eye-off icon (hide) --}}
                    <svg id="icon-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="lf-input-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember & Forgot --}}
        <div class="lf-row-bottom">
            <label for="remember_me" class="lf-remember-label">
                <input id="remember_me" type="checkbox" class="lf-remember-check" name="remember">
                <span class="lf-remember-text">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a
                    class="lf-forgot-link"
                    href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20lupa%20password%20akun%20e-learning%20saya.%20Mohon%20bantuannya!"
                    target="_blank"
                    rel="noopener noreferrer"
                    title="Hubungi Admin via WhatsApp"
                >
                    Lupa Password?
                </a>
            @endif
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="lf-submit-btn" id="btn-login">
            <span class="lf-spinner" id="login-spinner"></span>
            <span id="btn-login-text">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:5px;margin-top:-2px">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Masuk ke Akun
            </span>
        </button>
    </form>

    <script>
        // Show / hide password toggle
        (function() {
            var toggleBtn  = document.getElementById('toggle-pw');
            var pwInput    = document.getElementById('password');
            var iconEye    = document.getElementById('icon-eye');
            var iconEyeOff = document.getElementById('icon-eye-off');

            if (toggleBtn && pwInput) {
                toggleBtn.addEventListener('click', function() {
                    var isHidden = pwInput.type === 'password';
                    pwInput.type = isHidden ? 'text' : 'password';
                    iconEye.style.display    = isHidden ? 'none' : '';
                    iconEyeOff.style.display = isHidden ? '' : 'none';
                });
            }
        })();

        // Loading state on form submit
        (function() {
            var form    = document.getElementById('login-form');
            var btn     = document.getElementById('btn-login');
            var spinner = document.getElementById('login-spinner');
            var btnText = document.getElementById('btn-login-text');

            if (form) {
                form.addEventListener('submit', function() {
                    if (btn) btn.disabled = true;
                    if (spinner) spinner.style.display = 'block';
                    if (btnText) btnText.style.opacity = '0.6';
                });
            }
        })();
    </script>
</x-guest-layout>
