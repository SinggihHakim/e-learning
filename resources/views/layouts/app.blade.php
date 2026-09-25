<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS - EduLearn')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --bg: #f0f4ff;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --card-bg: #ffffff;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --sidebar-width: 260px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }
        .sidebar { width: var(--sidebar-width); background: linear-gradient(180deg, #0f172a 0%, #1e293b 60%, #1a2744 100%); position: fixed; top: 0; left: 0; bottom: 0; display: flex; flex-direction: column; z-index: 100; box-shadow: 4px 0 24px rgba(0,0,0,0.18); }
        .sidebar-logo { padding: 20px 18px 18px; border-bottom: 1px solid rgba(255,255,255,0.07); }
        .sidebar-logo-inner { display: flex; align-items: center; gap: 10px; }
        .sidebar-logo-icon { width: 34px; height: 34px; background: linear-gradient(135deg, #2563eb, #7c3aed); border-radius: 9px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 3px 10px rgba(79,70,229,0.45); }
        .sidebar-logo h1 { font-family: 'Poppins', sans-serif; font-size: 1.12rem; font-weight: 700; color: #fff; letter-spacing: -0.3px; }
        .sidebar-logo span { display: block; font-size: 0.67rem; color: #475569; margin-top: 6px; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 600; padding-left: 44px; }
        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .nav-section-label { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #475569; padding: 10px 20px 4px; }
        .nav-item { display: flex; align-items: center; gap: 11px; padding: 9px 12px 9px 18px; color: #94a3b8; text-decoration: none; font-size: 0.84rem; font-weight: 500; border-left: 3px solid transparent; transition: all 0.18s; margin: 1px 8px; border-radius: 8px; }
        .nav-item:hover { color: #e2e8f0; background: rgba(255,255,255,0.06); border-left-color: transparent; }
        .nav-item.active { color: #fff; background: rgba(37,99,235,0.2); border-left-color: #3b82f6; }
        .nav-item.active svg { color: #60a5fa; }
        .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
        .sidebar-footer { padding: 14px 20px; border-top: 1px solid rgba(255,255,255,0.08); }
        .user-info { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #2563eb, #7c3aed); display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; color: #fff; flex-shrink: 0; box-shadow: 0 2px 8px rgba(79,70,229,0.4); }
        .user-name { font-size: 0.82rem; font-weight: 600; color: #f1f5f9; line-height: 1.2; }
        .user-role { font-size: 0.67rem; color: #475569; text-transform: capitalize; margin-top: 1px; }
        .btn-logout { display: flex; align-items: center; gap: 8px; width: 100%; padding: 8px 12px; background: rgba(239,68,68,0.15); color: #f87171; border: none; border-radius: 8px; font-size: 0.78rem; font-weight: 500; cursor: pointer; text-decoration: none; transition: all 0.2s; }
        .btn-logout:hover { background: rgba(239,68,68,0.3); color: #fff; }
        .main-content { margin-left: var(--sidebar-width); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { background: #fff; padding: 14px 28px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 50; box-shadow: 0 1px 8px rgba(0,0,0,0.06); }
        .page-title { font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 700; color: #0f172a; letter-spacing: -0.3px; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .notif-btn { position: relative; background: none; border: none; cursor: pointer; padding: 7px; border-radius: 8px; color: var(--text-muted); transition: background 0.2s; }
        .notif-btn:hover { background: var(--bg); }
        .notif-badge { position: absolute; top: 2px; right: 2px; background: var(--danger); color: #fff; font-size: 0.58rem; font-weight: 700; width: 15px; height: 15px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1.5px solid white; }
        .page-content { padding: 28px; flex: 1; }
        .card { background: var(--card-bg); border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.05); border: 1px solid var(--border); }
        .card-header { padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-family: 'Poppins', sans-serif; font-size: 0.95rem; font-weight: 700; color: #0f172a; }
        .card-body { padding: 22px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 24px; }
        .stat-card { background: var(--card-bg); border-radius: 14px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.06); border: 1px solid var(--border); display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .stat-card-accent { height: 4px; }
        .stat-card-accent.blue   { background: linear-gradient(90deg, #2563eb, #60a5fa); }
        .stat-card-accent.green  { background: linear-gradient(90deg, #10b981, #34d399); }
        .stat-card-accent.yellow { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
        .stat-card-accent.purple { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
        .stat-card-accent.red    { background: linear-gradient(90deg, #ef4444, #f87171); }
        .stat-card-inner { display: flex; align-items: center; gap: 16px; padding: 20px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon svg { width: 22px; height: 22px; }
        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.green { background: #d1fae5; color: #10b981; }
        .stat-icon.yellow { background: #fef3c7; color: #f59e0b; }
        .stat-icon.purple { background: #ede9fe; color: #8b5cf6; }
        .stat-icon.red { background: #fee2e2; color: #ef4444; }
        .stat-info h3 { font-size: 1.9rem; font-weight: 800; line-height: 1; font-family: 'Poppins', sans-serif; color: #0f172a; }
        .stat-info p { font-size: 0.77rem; color: var(--text-muted); margin-top: 5px; font-weight: 500; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; border: none; text-decoration: none; transition: all 0.2s; }
        .btn svg { width: 16px; height: 16px; }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #4f46e5); color: #fff; box-shadow: 0 2px 8px rgba(79,70,229,0.3); }
        .btn-primary:hover { background: linear-gradient(135deg, #1d4ed8, #4338ca); box-shadow: 0 4px 14px rgba(79,70,229,0.4); transform: translateY(-1px); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-warning:hover { background: #d97706; }
        .btn-secondary { background: var(--border); color: var(--text); }
        .btn-secondary:hover { background: #cbd5e1; }
        .btn-outline { background: transparent; border: 1.5px solid var(--border); color: var(--text-muted); }
        .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; color: var(--text); }
        .btn-sm { padding: 5px 10px; font-size: 0.78rem; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; font-size: 0.875rem; }
        th { background: var(--bg); font-weight: 600; color: var(--text-muted); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border); }
        td { border-bottom: 1px solid var(--border); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }
        .badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.01em; }
        .badge-primary { background: var(--primary-light); color: var(--primary); }
        .badge-success { background: #d1fae5; color: #059669; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-secondary { background: #f1f5f9; color: #64748b; }
        [x-cloak] { display: none !important; }
        .badge-purple { background: #ede9fe; color: #7c3aed; }
        .alert { padding: 12px 16px; border-radius: 8px; font-size: 0.875rem; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-info { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 6px; color: var(--text); }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="date"], input[type="time"], input[type="datetime-local"], input[type="url"], select, textarea { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-size: 0.875rem; font-family: 'Inter', sans-serif; background: #fff; color: var(--text); transition: border-color 0.2s, box-shadow 0.2s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .form-error { font-size: 0.78rem; color: var(--danger); margin-top: 4px; }
        .progress-bar-wrap { background: #e2e8f0; border-radius: 20px; height: 8px; overflow: hidden; }
        .progress-bar-fill { height: 100%; border-radius: 20px; background: linear-gradient(90deg, var(--primary), #60a5fa); transition: width 0.6s ease; }
        .grid { display: grid; }
        .grid-2 { grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .flex { display: flex; }
        .flex-between { justify-content: space-between; }
        .flex-center { align-items: center; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .mt-4 { margin-top: 16px; }
        .text-muted { color: var(--text-muted); }
        .text-sm { font-size: 0.875rem; }
        .text-xs { font-size: 0.75rem; }
        .font-semibold { font-weight: 600; }
        .course-card { background: var(--card-bg); border-radius: 14px; border: 1px solid var(--border); overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; }
        .course-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
        .course-card-header { height: 6px; background: linear-gradient(90deg, #2563eb, #7c3aed); }
        .course-card-body { padding: 18px; }
        .course-card-title { font-family: 'Poppins', sans-serif; font-size: 0.95rem; font-weight: 700; margin-bottom: 6px; color: #0f172a; }
        .courses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 20px; }
        .leaderboard-item { display: flex; align-items: center; gap: 14px; padding: 14px; border-radius: 10px; background: var(--bg); margin-bottom: 10px; }
        .rank-badge { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; }
        .rank-1 { background: #fbbf24; color: #78350f; }
        .rank-2 { background: #94a3b8; color: #fff; }
        .rank-3 { background: #cd7c2f; color: #fff; }
        .rank-other { background: #e2e8f0; color: var(--text-muted); }
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 20px; }
        .breadcrumb a { color: var(--primary); text-decoration: none; }
        .empty-state { text-align: center; padding: 48px 24px; color: var(--text-muted); }
        .empty-state svg { width: 56px; height: 56px; margin: 0 auto 16px; opacity: 0.4; }
        .empty-state h3 { font-weight: 600; font-size: 1rem; color: var(--text); }
        .empty-state p { font-size: 0.875rem; margin-top: 6px; }
        .sidebar { transition: transform 0.3s ease; }
        .mobile-menu-btn { display: none; }
        @media (max-width: 768px) { 
            .sidebar { transform: translateX(-100%); } 
            .sidebar.open { transform: translateX(0); }
            .mobile-menu-btn { display: block; }
            .main-content { margin-left: 0; } 
            .grid-2, .grid-3 { grid-template-columns: 1fr; } 
            .stats-grid { grid-template-columns: 1fr; }
            .topbar { padding: 12px 16px; }
            .page-content { padding: 16px; }
        }
    </style>
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false }" :class="{ 'overflow-hidden': sidebarOpen }">
    <aside class="sidebar" :class="{ 'open': sidebarOpen }">
        <div class="sidebar-logo">
            <div class="sidebar-logo-inner">
                <div class="sidebar-logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <h1>EduLearn LMS</h1>
            </div>
            <span>{{ __('app.' . auth()->user()->role) }} Panel</span>
        </div>
        <nav class="sidebar-nav">
            @include('layouts.partials.sidebar-nav')
        </nav>
        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" class="user-info" style="text-decoration:none;display:flex;align-items:center;gap:10px;margin-bottom:10px;padding:8px;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                @if(auth()->user()->profile_photo_path)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                @else
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                @endif
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->role }}</div>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    {{ __('app.logout') }}
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Sidebar Overlay for Mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity style="position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:90;" x-cloak></div>

    <div class="main-content">
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 12px;">
                <button class="mobile-menu-btn" @click="sidebarOpen = true" style="background:none; border:none; color:var(--text); cursor:pointer;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="page-title">@yield('page-title', 'Dashboard')</div>
            </div>
            <div class="topbar-right">
                <div x-data="{ langOpen: false }" style="position: relative;">
                    <button @click="langOpen = !langOpen" @click.outside="langOpen = false" class="notif-btn" style="font-weight:600; font-size:0.85rem; padding: 6px 12px; border: 1px solid var(--border); border-radius: 8px; display:flex; align-items:center; gap:6px;">
                        {{ strtoupper(app()->getLocale()) }}
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="langOpen" x-transition x-cloak style="position: absolute; right: 0; top: 100%; margin-top: 8px; width: 130px; background: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid var(--border); overflow: hidden; z-index: 100;">
                        <a href="{{ route('lang.switch', 'id') }}" style="display:block; padding:10px 16px; text-decoration:none; color:var(--text); font-size:0.85rem; border-bottom:1px solid #f1f5f9; transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">🇮🇩 Indonesia</a>
                        <a href="{{ route('lang.switch', 'en') }}" style="display:block; padding:10px 16px; text-decoration:none; color:var(--text); font-size:0.85rem; transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">🇬🇧 English</a>
                    </div>
                </div>

                @auth
                @if(isset($notifications) && isset($unreadCount))
                <div x-data="{ open: false }" style="position: relative;">
                    <button @click="open = !open" @click.outside="open = false" class="notif-btn">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        @if($unreadCount > 0)
                            <span class="notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </button>
                    <div x-show="open" x-transition x-cloak style="position: absolute; right: 0; top: 100%; margin-top: 8px; width: 340px; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); border: 1px solid var(--border); overflow: hidden; z-index: 100;">
                        {{-- Header --}}
                        <div style="padding: 12px 16px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 6px;">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                Notifikasi
                                @if($unreadCount > 0)
                                    <span style="display:inline-flex;align-items:center;justify-content:center;background:var(--danger);color:#fff;font-size:0.65rem;font-weight:700;width:18px;height:18px;border-radius:50%;margin-left:4px;">{{ $unreadCount }}</span>
                                @endif
                            </span>
                            @if($unreadCount > 0)
                                <form method="POST" action="{{ route('notifications.read-all') }}" style="margin:0;">
                                    @csrf
                                    <button type="submit" style="background:none;border:none;color:var(--primary);font-size:0.75rem;font-weight:500;cursor:pointer;padding:2px 6px;border-radius:4px;transition:background 0.2s;" onmouseover="this.style.background='var(--primary-light)'" onmouseout="this.style.background='none'">
                                        Tandai semua dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                        {{-- List --}}
                        <div style="max-height: 340px; overflow-y: auto;">
                            @forelse($notifications as $notif)
                                <a href="{{ route('notifications.read', $notif->id) }}"
                                   style="
                                       display: block;
                                       padding: 12px 16px;
                                       border-bottom: 1px solid #f1f5f9;
                                       text-decoration: none;
                                       background: {{ $notif->is_read ? 'transparent' : '#eff6ff' }};
                                       transition: background 0.15s;
                                       position: relative;
                                   "
                                   onmouseover="this.style.background='{{ $notif->is_read ? '#f8fafc' : '#dbeafe' }}'"
                                   onmouseout="this.style.background='{{ $notif->is_read ? 'transparent' : '#eff6ff' }}'"
                                >
                                    {{-- Unread dot --}}
                                    @if(!$notif->is_read)
                                        <span style="position:absolute;top:14px;right:12px;width:8px;height:8px;background:var(--primary);border-radius:50%;"></span>
                                    @endif

                                    {{-- Icon berdasarkan tipe --}}
                                    <div style="display:flex;align-items:flex-start;gap:12px;">
                                        <div style="width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #fff; border: 1px solid var(--border); color: var(--text-muted);">
                                            @if($notif->type === 'assignment')
                                                <svg style="width:20px;height:20px;color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            @elseif($notif->type === 'material')
                                                <svg style="width:20px;height:20px;color:#8b5cf6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                            @elseif($notif->type === 'quiz')
                                                <svg style="width:20px;height:20px;color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                            @elseif($notif->type === 'grade')
                                                <svg style="width:20px;height:20px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                                            @elseif($notif->type === 'attendance')
                                                <svg style="width:20px;height:20px;color:#6366f1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                            @else
                                                <svg style="width:20px;height:20px;color:#94a3b8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                            @endif
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-size: 0.84rem; font-weight: {{ $notif->is_read ? '500' : '600' }}; color: var(--text); white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                {{ $notif->title }}
                                            </div>
                                            <div style="font-size: 0.75rem; margin-top: 3px; color: var(--text-muted); line-height: 1.4; display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                                {{ $notif->message }}
                                            </div>
                                            <div style="font-size: 0.65rem; margin-top: 5px; color: #94a3b8;">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div style="padding: 32px 20px; text-align: center;">
                                    <svg style="width:40px;height:40px;margin:0 auto 12px;color:#cbd5e1;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <div style="font-size: 0.82rem; color: var(--text-muted);">Belum ada notifikasi</div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif
                @endauth
            </div>
        </div>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('status'))
                <div class="alert alert-info">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ session('status') === 'profile-updated' ? 'Profile updated successfully!' : (session('status') === 'password-updated' ? 'Password updated successfully!' : session('status')) }}
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="list-style:none;padding:0;margin:0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>
