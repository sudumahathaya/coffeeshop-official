<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- User data for JavaScript -->
    @auth
        <meta name="user-name" content="{{ Auth::user()->name }}">
        <meta name="user-email" content="{{ Auth::user()->email }}">
        <meta name="user-id" content="{{ Auth::user()->id }}">
    @endauth

    <title>@yield('title', 'Café Elixir - Premium Coffee Experience')</title>
    <meta name="description" content="@yield('description', 'Experience premium coffee at Café Elixir. Artisanal blends, cozy atmosphere, and exceptional service in the heart of Colombo.')">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>☕</text></svg>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Sinhala:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS Variables -->
    <style>
        :root {
            --coffee-primary: #8B4513;
            --coffee-secondary: #D2691E;
            --coffee-accent: #CD853F;
            --coffee-dark: #2F1B14;
            --coffee-light: #F5F5DC;
            --gradient-primary: linear-gradient(135deg, var(--coffee-primary), var(--coffee-secondary));
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        .text-coffee {
            color: var(--coffee-primary) !important;
        }

        .bg-coffee {
            background: var(--gradient-primary) !important;
        }

        .btn-coffee {
            background: var(--gradient-primary);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-coffee:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
        }

        .btn-outline-coffee {
            border: 2px solid var(--coffee-primary);
            color: var(--coffee-primary);
            background: transparent;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-coffee:hover {
            background: var(--coffee-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--coffee-primary);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--coffee-secondary);
        }

        /* Loading states */
        .btn-loading {
            display: none;
        }

        .btn.loading .btn-text {
            display: none;
        }

        .btn.loading .btn-loading {
            display: inline-block;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .display-1 { font-size: 2.5rem !important; }
            .display-2 { font-size: 2.25rem !important; }
            .display-3 { font-size: 2rem !important; }
            .display-4 { font-size: 1.75rem !important; }
            .display-5 { font-size: 1.5rem !important; }
            .display-6 { font-size: 1.25rem !important; }

            .lead {
                font-size: 1.1rem !important;
                line-height: 1.5 !important;
            }

            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }

            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        @media (max-width: 576px) {
            h1 { font-size: 1.75rem !important; }
            h2 { font-size: 1.5rem !important; }
            h3 { font-size: 1.25rem !important; }
            h4 { font-size: 1.1rem !important; }
            h5 { font-size: 1rem !important; }
            h6 { font-size: 0.9rem !important; }

            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .btn-mobile-full {
                width: 100% !important;
                margin-bottom: 0.5rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-coffee fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="bi bi-cup-hot-fill me-2"></i>Café Elixir
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}" href="{{ route('menu') }}">
                            <i class="bi bi-journal-text me-1"></i>Menu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reservation') ? 'active' : '' }}" href="{{ route('reservation') }}">
                            <i class="bi bi-calendar-check me-1"></i>Reservations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('blog') ? 'active' : '' }}" href="{{ route('blog') }}">
                            <i class="bi bi-newspaper me-1"></i>Blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('features') ? 'active' : '' }}" href="{{ route('features') }}">
                            <i class="bi bi-star me-1"></i>Features
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="bi bi-envelope me-1"></i>Contact
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @auth
                        <!-- Cart Icon -->
                        <li class="nav-item me-3">
                            <button class="btn btn-outline-light position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
                                <i class="bi bi-cart"></i>
                                <span class="cart-counter" style="display: none;">0</span>
                            </button>
                        </li>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    <span>{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('user.orders') }}">
                                    <i class="bi bi-receipt me-2"></i>My Orders
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.view') }}">
                                    <i class="bi bi-person me-2"></i>Profile
                                </a></li>
                                @if(Auth::user()->role === 'admin')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-gear me-2"></i>Admin Panel
                                    </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light ms-2" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Sign Up
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="margin-top: 76px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-3">
                        <i class="bi bi-cup-hot-fill me-2"></i>Café Elixir
                    </h5>
                    <p class="mb-3">Your premium coffee destination in Colombo. We're passionate about serving exceptional coffee and creating memorable experiences.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('menu') }}" class="text-white-50 text-decoration-none">Menu</a></li>
                        <li><a href="{{ route('reservation') }}" class="text-white-50 text-decoration-none">Reservations</a></li>
                        <li><a href="{{ route('blog') }}" class="text-white-50 text-decoration-none">Blog</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white-50 text-decoration-none">Contact</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="mb-3">Contact Info</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            No.1, Mahamegawaththa Road<br>
                            <span class="ms-4">Maharagama, Sri Lanka</span>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            <a href="tel:+94771869132" class="text-white-50 text-decoration-none">+94 77 186 9132</a>
                        </li>
                        <li>
                            <i class="bi bi-envelope me-2"></i>
                            <a href="mailto:info@cafeelixir.lk" class="text-white-50 text-decoration-none">info@cafeelixir.lk</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="mb-3">Business Hours</h6>
                    <ul class="list-unstyled">
                        <li>Monday - Friday: 6:00 AM - 10:00 PM</li>
                        <li>Saturday: 6:00 AM - 11:00 PM</li>
                        <li>Sunday: 7:00 AM - 10:00 PM</li>
                    </ul>
                </div>
            </div>

            <hr class="my-4">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2024 Café Elixir. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-white-50 text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Include Payment Modal -->
    @include('partials.payment-modal')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Cart Management -->
    <script src="{{ asset('js/cart.js') }}"></script>

    <!-- Payment System -->
    <script src="{{ asset('js/simulation-payment.js') }}"></script>

    <!-- Receipt Generator -->
    <script src="{{ asset('js/receipt-generator.js') }}"></script>

    <!-- Coming Soon Features -->
    <script src="{{ asset('js/coming-soon.js') }}"></script>

    <!-- Initialize AOS -->
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    </script>

    <!-- Global JavaScript -->
    <script>
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
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                backdrop-filter: blur(10px);
            `;

            const iconMap = {
                'success': 'check-circle-fill',
                'error': 'exclamation-triangle-fill',
                'warning': 'exclamation-triangle-fill',
                'info': 'info-circle-fill'
            };

            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-${iconMap[type]} me-2"></i>
                    <span class="flex-grow-1">${message}</span>
                    <button type="button" class="btn-close ms-2" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideOutRight 0.5s ease';
                    setTimeout(() => notification.remove(), 500);
                }
            }, 5000);
        }

        // Contact form submission
        document.addEventListener('DOMContentLoaded', function() {
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!this.checkValidity()) {
                        this.classList.add('was-validated');
                        return;
                    }

                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;

                    submitButton.classList.add('loading');
                    submitButton.disabled = true;

                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData.entries());

                    fetch('/contact', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            showNotification(`Thank you! Your message has been sent. Reference: ${result.message_id}`, 'success');
                            this.reset();
                            this.classList.remove('was-validated');
                        } else {
                            showNotification('Failed to send message. Please try again.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred. Please try again.', 'error');
                    })
                    .finally(() => {
                        submitButton.classList.remove('loading');
                        submitButton.disabled = false;
                    });
                });
            }

            // Reservation form submission
            const reservationForm = document.getElementById('reservationForm');
            if (reservationForm) {
                reservationForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!this.checkValidity()) {
                        this.classList.add('was-validated');
                        return;
                    }

                    const submitButton = this.querySelector('button[type="submit"]');
                    submitButton.classList.add('loading');
                    submitButton.disabled = true;

                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData.entries());

                    fetch('/reservation', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            showNotification(`Reservation confirmed! ID: ${result.reservation_id}`, 'success');
                            this.reset();
                            this.classList.remove('was-validated');

                            // Redirect to dashboard if logged in
                            setTimeout(() => {
                                @auth
                                    window.location.href = '{{ route("user.dashboard") }}';
                                @else
                                    window.location.href = '{{ route("login") }}';
                                @endauth
                            }, 2000);
                        } else {
                            showNotification('Failed to make reservation. Please try again.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred. Please try again.', 'error');
                    })
                    .finally(() => {
                        submitButton.classList.remove('loading');
                        submitButton.disabled = false;
                    });
                });
            }

            // Newsletter subscription
            document.querySelectorAll('.newsletter-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const email = this.querySelector('input[type="email"]').value;
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    if (!email) {
                        showNotification('Please enter your email address', 'warning');
                        return;
                    }

                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Subscribing...';
                    submitBtn.disabled = true;

                    fetch('/newsletter/subscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ email: email })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Thank you for subscribing to our newsletter!', 'success');
                            this.reset();
                        } else {
                            showNotification('Subscription failed. Please try again.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred. Please try again.', 'error');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
                });
            });

            // Sinhala typewriter effect for home page
            if (document.getElementById('sinhalaTypewriter')) {
                const sinhalaTexts = [
                    "ආයුබෝවන් කැෆේ එලික්සර් වෙත",
                    "ප්‍රිමියම් කෝපි අත්දැකීමක්",
                    "ගුණාත්මක සේවාවක් සමඟ"
                ];

                const englishTexts = [
                    "Welcome to Café Elixir",
                    "Premium Coffee Experience", 
                    "With Quality Service"
                ];

                let sinhalaIndex = 0;
                let englishIndex = 0;
                let charIndex = 0;
                let isDeleting = false;
                let isEnglish = false;

                function typeWriter() {
                    const sinhalaElement = document.getElementById('sinhalaTypewriter');
                    const englishElement = document.getElementById('englishTypewriter');
                    
                    if (!sinhalaElement) return;

                    const currentTexts = isEnglish ? englishTexts : sinhalaTexts;
                    const currentIndex = isEnglish ? englishIndex : sinhalaIndex;
                    const currentElement = isEnglish ? englishElement : sinhalaElement;
                    
                    if (!currentElement) return;

                    const currentText = currentTexts[currentIndex];

                    if (isDeleting) {
                        currentElement.textContent = currentText.substring(0, charIndex - 1);
                        charIndex--;
                    } else {
                        currentElement.textContent = currentText.substring(0, charIndex + 1);
                        charIndex++;
                    }

                    let typeSpeed = isDeleting ? 50 : 100;

                    if (!isDeleting && charIndex === currentText.length) {
                        typeSpeed = 2000;
                        isDeleting = true;
                    } else if (isDeleting && charIndex === 0) {
                        isDeleting = false;
                        if (isEnglish) {
                            englishIndex = (englishIndex + 1) % englishTexts.length;
                        } else {
                            sinhalaIndex = (sinhalaIndex + 1) % sinhalaTexts.length;
                        }
                        isEnglish = !isEnglish;
                        typeSpeed = 500;
                    }

                    setTimeout(typeWriter, typeSpeed);
                }

                typeWriter();
            }
        });

        // CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }

            @keyframes blink {
                0%, 50% { opacity: 1; }
                51%, 100% { opacity: 0; }
            }

            .notification-toast {
                backdrop-filter: blur(10px);
            }

            .user-avatar {
                width: 32px;
                height: 32px;
                background: linear-gradient(45deg, var(--coffee-primary), var(--coffee-secondary));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
                font-size: 0.875rem;
            }

            .hero-section {
                background: linear-gradient(135deg,
                            rgba(139, 69, 19, 0.9),
                            rgba(210, 105, 30, 0.8)),
                            url('https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=1920&h=1080&fit=crop') center/cover;
                min-height: 100vh;
                display: flex;
                align-items: center;
                position: relative;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.3);
            }

            .hero-section .container {
                position: relative;
                z-index: 2;
            }

            .hero-title {
                font-size: 3.5rem;
                font-weight: 700;
                color: white;
                margin-bottom: 1.5rem;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            }

            .hero-subtitle {
                font-size: 1.25rem;
                color: rgba(255, 255, 255, 0.9);
                margin-bottom: 2rem;
                line-height: 1.6;
            }

            .floating {
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }

            .feature-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(45deg, var(--coffee-primary), var(--coffee-secondary));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                color: white;
                font-size: 2rem;
                transition: all 0.3s ease;
            }

            .feature-icon:hover {
                transform: scale(1.1);
            }

            .card-coffee {
                border: none;
                border-radius: 20px;
                overflow: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            }

            .card-coffee:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 35px rgba(139, 69, 19, 0.15);
            }

            .card-coffee .card-img-top {
                height: 250px;
                object-fit: cover;
                transition: all 0.3s ease;
            }

            .card-coffee:hover .card-img-top {
                transform: scale(1.05);
            }

            .stats-counter {
                background: var(--gradient-primary);
                color: white;
                padding: 4rem 0;
            }

            .stat-item {
                text-align: center;
            }

            .stat-number {
                font-size: 3rem;
                font-weight: 700;
                display: block;
                margin-bottom: 0.5rem;
            }

            .stat-label {
                font-size: 1.1rem;
                opacity: 0.9;
            }

            @media (max-width: 768px) {
                .hero-title {
                    font-size: 2.5rem;
                }

                .hero-subtitle {
                    font-size: 1.1rem;
                }

                .stat-number {
                    font-size: 2rem;
                }

                .feature-icon {
                    width: 60px;
                    height: 60px;
                    font-size: 1.5rem;
                }

                .card-coffee .card-img-top {
                    height: 200px;
                }
            }
        `;
        document.head.appendChild(style);
    </script>

    @stack('scripts')
</body>
</html>