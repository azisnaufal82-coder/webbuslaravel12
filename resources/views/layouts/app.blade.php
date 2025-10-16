<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - BusTicket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #00a2ff;
            --primary-dark: #0088cc;
            --secondary: #6c757d;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
            --border: #dee2e6;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary) !important;
        }
        
        .nav-link {
            font-weight: 500;
            color: #495057 !important;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s ease;
        }
        
        .nav-link:hover {
            color: var(--primary) !important;
        }
        
        .btn-primary {
            background: var(--primary);
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
        }
        
        .table td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        .badge {
            font-weight: 500;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
        }
        
        .search-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
        }
        
        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .schedule-card {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .schedule-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }
        
        .price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .route-arrow {
            color: var(--primary);
            margin: 0 0.5rem;
        }
        
        .footer {
            background: #f8f9fa;
            border-top: 1px solid var(--border);
            padding: 2rem 0;
            margin-top: 4rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ Auth::check() ? (Auth::user()->isAdmin() ? '/admin/dashboard' : '/dashboard') : '/' }}">
                <i class="fas fa-bus me-2"></i>BusTicket
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="/">Home</a>
                        </li>
                    @endguest
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/schedules">
                            <i class="fas fa-search me-1"></i>Cari Tiket
                        </a>
                    </li>
                    
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/dashboard">
                                    <i class="fas fa-cog me-1"></i>Admin
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="/dashboard">
                                    <i class="fas fa-user me-1"></i>Dashboard
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user me-2"></i>Profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="/logout">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="/login">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary ms-2" href="/register">
                                Daftar
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
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-3"><i class="fas fa-bus me-2"></i>BusTicket</h6>
                    <p class="text-muted mb-0">Platform pemesanan tiket bus modern dan terpercaya.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">&copy; 2024 BusTicket. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    @stack('scripts')
</body>
</html>