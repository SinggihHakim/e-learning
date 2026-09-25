<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - {{ config('app.name', 'E-Learning') }}</title>
        <meta name="description" content="Masuk ke akun E-Learning Anda untuk mengakses materi pembelajaran.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
            body { background-color: #f0f4ff; min-height: 100vh; margin: 0; }

            .login-wrapper {
                min-height: 100vh;
                display: flex;
                align-items: stretch;
            }

            /* Left Panel */
            .login-left-panel {
                display: none;
                width: 50%;
                background: linear-gradient(145deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);
                position: relative;
                overflow: hidden;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 3rem;
            }
            @media (min-width: 1024px) { .login-left-panel { display: flex; } }

            .lp-circle {
                position: absolute;
                border-radius: 50%;
                background: rgba(255,255,255,0.06);
            }
            .lp-c1 { width: 420px; height: 420px; top: -120px; right: -100px; }
            .lp-c2 { width: 300px; height: 300px; bottom: -90px; left: -80px; }
            .lp-c3 { width: 180px; height: 180px; top: 45%; left: 18%; transform: translateY(-50%); background: rgba(255,255,255,0.04); }

            .lp-content { position: relative; z-index: 2; text-align: center; color: white; }

            .lp-icon-wrap {
                width: 78px; height: 78px;
                background: rgba(255,255,255,0.18);
                border-radius: 20px;
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 1.5rem;
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255,255,255,0.25);
            }

            .lp-title {
                font-size: 2rem; font-weight: 800;
                letter-spacing: -0.5px; line-height: 1.25;
                margin-bottom: 0.75rem;
            }

            .lp-subtitle {
                font-size: 0.9375rem; font-weight: 400;
                opacity: 0.82; line-height: 1.65;
                max-width: 340px; margin: 0 auto 2.25rem;
            }

            .lp-features { display: flex; flex-direction: column; gap: 0.875rem; width: 100%; max-width: 320px; margin: 0 auto; }

            .lp-feature {
                display: flex; align-items: center; gap: 0.875rem;
                background: rgba(255,255,255,0.12);
                border: 1px solid rgba(255,255,255,0.2);
                border-radius: 12px;
                padding: 0.875rem 1.125rem;
                backdrop-filter: blur(6px);
                text-align: left;
                transition: background 0.2s;
            }
            .lp-feature:hover { background: rgba(255,255,255,0.18); }

            .lp-feature-icon {
                width: 38px; height: 38px;
                background: rgba(255,255,255,0.22);
                border-radius: 9px;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            }

            .lp-feature-title { font-size: 0.875rem; font-weight: 600; color: white; }
            .lp-feature-desc { font-size: 0.75rem; color: rgba(255,255,255,0.72); }

            /* Right Panel */
            .login-right-panel {
                flex: 1;
                display: flex; align-items: center; justify-content: center;
                padding: 2rem 1.5rem;
                background-color: #f0f4ff;
            }

            .login-card {
                width: 100%; max-width: 430px;
                background: white;
                border-radius: 24px;
                box-shadow: 0 10px 40px rgba(79,70,229,0.12), 0 2px 8px rgba(0,0,0,0.06);
                padding: 2.5rem 2.25rem;
                position: relative;
                overflow: hidden;
                animation: fadeInUp 0.4s ease both;
            }
            .login-card::before {
                content: '';
                position: absolute; top: 0; left: 0; right: 0; height: 4px;
                background: linear-gradient(90deg, #1d4ed8, #4f46e5, #7c3aed);
                border-radius: 24px 24px 0 0;
            }

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(18px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes spin { to { transform: rotate(360deg); } }
        </style>
    </head>
    <body>
        <div class="login-wrapper">

            <!-- Left Panel -->
            <div class="login-left-panel">
                <div class="lp-circle lp-c1"></div>
                <div class="lp-circle lp-c2"></div>
                <div class="lp-circle lp-c3"></div>

                <div class="lp-content">
                    <div class="lp-icon-wrap">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>

                    <h1 class="lp-title">Platform E-Learning<br>Terbaik untuk Anda</h1>
                    <p class="lp-subtitle">Akses ribuan materi pembelajaran berkualitas, pantau progres belajar, dan raih prestasi bersama kami.</p>

                    <div class="lp-features">
                        <div class="lp-feature">
                            <div class="lp-feature-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="lp-feature-title">Materi Lengkap</div>
                                <div class="lp-feature-desc">Kursus terstruktur &amp; video pembelajaran</div>
                            </div>
                        </div>

                        <div class="lp-feature">
                            <div class="lp-feature-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                                </svg>
                            </div>
                            <div>
                                <div class="lp-feature-title">Pantau Progres</div>
                                <div class="lp-feature-desc">Dashboard analitik perkembangan belajar</div>
                            </div>
                        </div>

                        <div class="lp-feature">
                            <div class="lp-feature-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                                </svg>
                            </div>
                            <div>
                                <div class="lp-feature-title">Sertifikat Resmi</div>
                                <div class="lp-feature-desc">Sertifikat kelulusan yang diakui</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="login-right-panel">
                <div class="login-card">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
