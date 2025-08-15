<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="user-name" content="{{ Auth::user()->name }}">
        <meta name="user-email" content="{{ Auth::user()->email }}">
    @endauth

    <title>@yield('title', 'Café Elixir - Premium Coffee Experience')</title>
    <meta name="description" content="@yield('description', 'Experience premium coffee at Café Elixir. Artisanal coffee, cozy atmosphere, and exceptional service in the heart of Colombo.')">

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

        /* Typewriter animation */
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }

        /* Floating animation */
        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Feature icon styling */
        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
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

        /* Card styling */
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

        /* Stats counter styling */
        .stats-counter {
            background: var(--gradient-primary);
            color: white;
            padding: 4rem 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Navbar styling */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(139, 69, 19, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-custom.scrolled {
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--coffee-primary) !important;
        }

        .nav-link {
            font-weight: 500;
            color: var(--coffee-primary) !important;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--coffee-secondary) !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: var(--coffee-secondary) !important;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: var(--coffee-secondary);
            border-radius: 2px;
        }

        /* Footer styling */
        .footer {
            background: var(--coffee-dark);
            color: white;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: var(--coffee-secondary);
        }

        /* Hero sections */
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
            font-size: 4rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* Responsive design */
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

        /* Loading states */
        .btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-up {
            animation: slideUp 0.6s ease-in-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-cup-hot-fill me-2"></i>Café Elixir
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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
                            <button class="btn btn-outline-coffee position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
                                <i class="bi bi-cart"></i>
                                <span class="cart-counter" style="display: none;">0</span>
                            </button>
                        </li>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
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
                            <a class="btn btn-coffee ms-2" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Sign Up
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-3">
                        <i class="bi bi-cup-hot-fill me-2"></i>Café Elixir
                    </h5>
                    <p class="mb-3">Experience the perfect blend of premium coffee, cozy atmosphere, and exceptional service in the heart of Colombo.</p>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="me-3"><i class="bi bi-youtube fs-4"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h6 class="mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('menu') }}">Menu</a></li>
                        <li class="mb-2"><a href="{{ route('reservation') }}">Reservations</a></li>
                        <li class="mb-2"><a href="{{ route('blog') }}">Blog</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="mb-3">Contact Info</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            No.1, Mahamegawaththa Road, Maharagama
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            <a href="tel:+94771869132">+94 77 186 9132</a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            <a href="mailto:info@cafeelixir.lk">info@cafeelixir.lk</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="mb-3">Newsletter</h6>
                    <p class="mb-3">Subscribe for updates and special offers!</p>
                    <form class="newsletter-form" id="footerNewsletterForm">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your email" required>
                            <button class="btn btn-coffee" type="submit">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.2);">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2024 Café Elixir. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="me-3">Privacy Policy</a>
                    <a href="#" class="me-3">Terms of Service</a>
                    <a href="#">Sitemap</a>
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

    <!-- Coming Soon Features -->
    <script src="{{ asset('js/coming-soon.js') }}"></script>

    <!-- Global JavaScript -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Newsletter form submission
        document.addEventListener('DOMContentLoaded', function() {
            // Footer newsletter form
            const footerNewsletterForm = document.getElementById('footerNewsletterForm');
            if (footerNewsletterForm) {
                footerNewsletterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleNewsletterSubmission(this);
                });
            }

            // Other newsletter forms
            document.querySelectorAll('.newsletter-form:not(#footerNewsletterForm)').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleNewsletterSubmission(this);
                });
            });

            // Contact form submission
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleContactSubmission(this);
                });
            }

            // Reservation form submission
            const reservationForm = document.getElementById('reservationForm');
            if (reservationForm) {
                reservationForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleReservationSubmission(this);
                });
            }
        });

        function handleNewsletterSubmission(form) {
            const email = form.querySelector('input[type="email"]').value;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            if (!email) {
                showNotification('Please enter your email address', 'warning');
                return;
            }

            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
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
                    showNotification(data.message, 'success');
                    form.reset();
                } else {
                    showNotification(data.message || 'Subscription failed', 'error');
                }
            })
            .catch(error => {
                console.error('Newsletter subscription error:', error);
                showNotification('Failed to subscribe. Please try again.', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        function handleContactSubmission(form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            const formData = new FormData(form);
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
            .then(data => {
                if (data.success) {
                    showNotification(`Message sent successfully! Reference: ${data.message_id}`, 'success');
                    form.reset();
                    form.classList.remove('was-validated');
                } else {
                    showNotification(data.message || 'Failed to send message', 'error');
                }
            })
            .catch(error => {
                console.error('Contact form error:', error);
                showNotification('Failed to send message. Please try again.', 'error');
            })
            .finally(() => {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            });
        }

        function handleReservationSubmission(form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            const formData = new FormData(form);
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
            .then(data => {
                if (data.success) {
                    showReservationSuccess(data.reservation_id, data.data);
                    form.reset();
                    form.classList.remove('was-validated');
                } else {
                    showNotification(data.message || 'Failed to make reservation', 'error');
                }
            })
            .catch(error => {
                console.error('Reservation form error:', error);
                showNotification('Failed to make reservation. Please try again.', 'error');
            })
            .finally(() => {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            });
        }

        function showReservationSuccess(reservationId, data) {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-check-circle-fill me-2"></i>Reservation Confirmed!
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="success-animation mb-4">
                                <i class="bi bi-calendar-check-fill text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-success mb-3">Reservation Confirmed!</h4>
                            <p class="lead">Your table has been reserved successfully.</p>
                            <div class="reservation-details bg-light p-4 rounded mt-4">
                                <h6 class="fw-bold mb-3">Reservation Details:</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Reservation ID:</strong><br>
                                        <code class="text-primary">${reservationId}</code>
                                    </div>
                                    <div class="col-6">
                                        <strong>Guest Name:</strong><br>
                                        ${data.first_name} ${data.last_name}
                                    </div>
                                    <div class="col-6 mt-3">
                                        <strong>Date & Time:</strong><br>
                                        ${new Date(data.reservation_date).toLocaleDateString()} at ${data.reservation_time}
                                    </div>
                                    <div class="col-6 mt-3">
                                        <strong>Guests:</strong><br>
                                        ${data.guests} ${data.guests === 1 ? 'Person' : 'People'}
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <strong>What's Next?</strong><br>
                                • You'll receive a confirmation email<br>
                                • Please arrive 10 minutes early<br>
                                • Contact us if you need to make changes<br>
                                • Enjoy your coffee experience!
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <a href="{{ route('menu') }}" class="btn btn-coffee">
                                <i class="bi bi-journal-text me-2"></i>View Menu
                            </a>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();

            modal.addEventListener('hidden.bs.modal', () => {
                document.body.removeChild(modal);
            });
        }

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

        // Typewriter effect for Sinhala text (Home page)
        if (document.getElementById('sinhalaTypewriter')) {
            const sinhalaTexts = [
                "ගුණාත්මක කෝපි අත්දැකීමක්",
                "ප්‍රේමවත් සේවාවක්",
                "සුවපහසු වාතාවරණයක්"
            ];

            const englishTexts = [
                "A Premium Coffee Experience",
                "Exceptional Service",
                "Cozy Atmosphere"
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
                const currentText = currentTexts[currentIndex];

                if (!currentElement) return;

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
                        isEnglish = false;
                    } else {
                        sinhalaIndex = (sinhalaIndex + 1) % sinhalaTexts.length;
                        isEnglish = true;
                    }
                    typeSpeed = 500;
                }

                setTimeout(typeWriter, typeSpeed);
            }

            typeWriter();
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Form validation enhancement
        document.querySelectorAll('.needs-validation').forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });

        // Auto-hide alerts
        document.querySelectorAll('.alert-dismissible').forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Back to top button
        const backToTopButton = document.createElement('button');
        backToTopButton.innerHTML = '<i class="bi bi-arrow-up"></i>';
        backToTopButton.className = 'btn btn-coffee position-fixed';
        backToTopButton.style.cssText = `
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: none;
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
        `;

        document.body.appendChild(backToTopButton);

        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });

        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

    @stack('scripts')
</body>
</html>