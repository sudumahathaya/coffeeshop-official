<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard - Café Elixir')</title>

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>☕</text></svg>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --coffee-primary: #8B4513;
            --coffee-secondary: #D2691E;
            --coffee-accent: #CD853F;
            --coffee-dark: #2F1B14;
            --coffee-light: #F5F5DC;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--coffee-primary), var(--coffee-secondary));
            min-height: 100vh;
            width: 280px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 260px;
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .sidebar-brand {
                padding: 1rem;
            }
        }
        .sidebar-brand h4 {
            color: white;
            margin: 0;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .sidebar-brand h4 {
                font-size: 1.25rem;
            }
        }
        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        @media (max-width: 768px) {
            .nav-link {
                padding: 0.5rem 1rem;
                margin: 0.25rem 0.5rem;
                font-size: 0.9rem;
            }
        }
        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateX(5px);
        }

        @media (max-width: 768px) {
            .nav-link:hover,
            .nav-link.active {
                transform: none;
            }
        }
        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
        .top-navbar {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        @media (max-width: 768px) {
            .top-navbar {
                padding: 0.75rem 1rem;
            }
        }
        .content-area {
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .content-area {
                padding: 1rem;
            }
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        @media (max-width: 576px) {
            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
        }
        .btn-coffee {
            background: linear-gradient(45deg, var(--coffee-primary), var(--coffee-secondary));
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-coffee:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
            color: white;
        }

        @media (max-width: 768px) {
            .btn-coffee:hover {
                transform: none;
            }
        }
        .text-coffee {
            color: var(--coffee-primary) !important;
        }

        .text-gray-800 {
            color: #2d3748 !important;
        }

        /* Chart container fixes */
        .card-body {
            position: relative;
            overflow: hidden;
        }

        .card-body canvas {
            max-width: 100% !important;
            height: auto !important;
        }

        /* Ensure charts are properly sized */
        .chart-container {
            position: relative;
            width: 100%;
            height: 300px;
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 250px;
            }

            /* Mobile-specific admin improvements */
            .card-body {
                padding: 1rem;
            }

            .card-header {
                padding: 1rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            .badge {
                font-size: 0.7rem;
            }

            /* Dropdown improvements for mobile */
            .dropdown-menu {
                font-size: 0.875rem;
            }

            /* Modal improvements */
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-body {
                padding: 1rem;
            }

            /* Form improvements */
            .form-control,
            .form-select {
                font-size: 16px; /* Prevent zoom on iOS */
            }

            /* Stats card mobile layout */
            .stat-icon {
                margin-bottom: 0.5rem;
            }

            .h4 {
                font-size: 1.25rem;
            }

            .small {
                font-size: 0.8rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-cup-hot-fill me-2"></i>Café Elixir</h4>
            <small class="text-white-50">Admin Dashboard</small>
        </div>

        <ul class="sidebar-nav list-unstyled">
            <li>
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('admin.reservations*') ? 'active' : '' }}" 
                   href="{{ route('admin.reservations') }}">
                    <i class="bi bi-calendar-check"></i>
                    Reservations
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" 
                   href="{{ route('admin.orders') }}">
                    <i class="bi bi-receipt"></i>
                    Orders
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" 
                   href="{{ route('admin.users') }}">
                    <i class="bi bi-people"></i>
                    Users
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('admin.menu*') ? 'active' : '' }}" 
                   href="{{ route('admin.menu') }}">
                    <i class="bi bi-journal-text"></i>
                    Menu Management
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('admin.profile-requests*') ? 'active' : '' }}" 
                   href="{{ route('admin.profile-requests.index') }}">
                    <i class="bi bi-person-gear"></i>
                    Profile Changes
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('admin.reservation-requests*') ? 'active' : '' }}" 
                   href="{{ route('admin.reservation-requests.index') }}">
                    <i class="bi bi-pencil-square"></i>
                    Reservation Changes
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}" 
                   href="{{ route('admin.analytics') }}">
                    <i class="bi bi-graph-up"></i>
                    Analytics
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" 
                   href="{{ route('admin.settings') }}">
                    <i class="bi bi-gear"></i>
                    Settings
                </a>
            </li>
        </ul>

        <div class="mt-auto p-3">
            <div class="d-grid">
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-house me-2"></i>Back to Website
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Notifications -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary position-relative" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><a class="dropdown-item" href="#">New reservation request</a></li>
                            <li><a class="dropdown-item" href="#">Order #CE001 completed</a></li>
                            <li><a class="dropdown-item" href="#">New user registered</a></li>
                        </ul>
                    </div>

                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-coffee dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2"></i>Profile
                <div class="d-flex align-items-center gap-2 gap-md-3">
                            <li><a class="dropdown-item" href="{{ route('admin.settings') }}">
                                <i class="bi bi-gear me-2"></i>Settings
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 250px;">
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

                        <button class="btn btn-coffee dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                            <span class="d-inline d-md-none">{{ substr(auth()->user()->name, 0, 8) }}...</span>
            @yield('content')
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 200px;">
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Pusher for real-time updates (optional) -->
    <!-- Pusher for real-time updates (optional) -->
    <!-- <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script> -->

    <!-- Custom JavaScript -->
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });

        // Global notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} position-fixed notification-toast`;
            notification.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 350px;
                border-radius: 15px;
                animation: slideInRight 0.5s ease;
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay d-md-none" id="sidebarOverlay" style="display: none;"></div>
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            `;

            const iconMap = {
                'success': 'check-circle-fill',
                'error': 'exclamation-triangle-fill',
                'warning': 'exclamation-triangle-fill',
                'info': 'info-circle-fill'
            };

            notification.innerHTML = `
                <div class="d-flex align-items-center">
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('show');
            
            if (sidebar.classList.contains('show')) {
                overlay.style.display = 'block';
                overlay.style.opacity = '0';
                setTimeout(() => overlay.style.opacity = '0.5', 10);
                document.body.style.overflow = 'hidden';
            } else {
                overlay.style.opacity = '0';
                setTimeout(() => {
                    overlay.style.display = 'none';
                    document.body.style.overflow = '';
                }, 300);
            }
        });

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.remove('show');
            this.style.opacity = '0';
            setTimeout(() => {
                this.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
                    <span class="flex-grow-1">${message}</span>
                    <button type="button" class="btn-close ms-2" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideOutRight 0.5s ease';
                min-width: ${window.innerWidth < 768 ? '300px' : '350px'};
                max-width: ${window.innerWidth < 768 ? '90vw' : '400px'};
                }
            }, 5000);
        }

        @media (max-width: 576px) {
            .content-area {
                padding: 0.5rem;
            }

            .card {
                margin-bottom: 1rem;
            }

            .btn-group {
                    <span class="d-none d-lg-inline">Menu Management</span>
                    <span class="d-inline d-lg-none">Menu</span>
            }

            .btn-group .btn {
                border-radius: 0.375rem !important;
                margin-bottom: 0.25rem;
            }
                    <span class="d-none d-lg-inline">Profile Changes</span>
                    <span class="d-inline d-lg-none">Profiles</span>
            /* Hide some columns on very small screens */
            .d-none-xs {
                display: none !important;
            }
        }
        // CSS animations
                    <span class="d-none d-lg-inline">Reservation Changes</span>
                    <span class="d-inline d-lg-none">Changes</span>
        style.textContent = `
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                transition: opacity 0.3s ease;
            }

            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            
            .notification-toast {
                backdrop-filter: blur(10px);
            }

            @media (max-width: 768px) {
                .notification-toast {
                    top: 10px !important;
                    right: 10px !important;
                    left: 10px !important;
                    min-width: auto !important;
                    max-width: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>

    @stack('scripts')
</body>
</html>