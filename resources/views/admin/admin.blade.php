<!-- resources/views/layouts/admin.blade.php -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #34495e;
            --accent: #3498db;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        #sidebar {
            min-height: 100vh;
            background: var(--primary);
            color: white;
            transition: all 0.3s;
        }
        
        #sidebar .sidebar-header {
            padding: 1.5rem;
            background: var(--secondary);
        }
        
        #sidebar .components {
            padding: 1rem 0;
        }
        
        #sidebar .components .nav-link {
            color: #b7c0cd;
            padding: 0.8rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        
        #sidebar .components .nav-link:hover,
        #sidebar .components .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 3px solid var(--accent);
        }
        
        #sidebar .components .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .content {
            padding: 2rem;
        }
        
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        
        .stat-card {
            text-align: center;
            padding: 1.5rem;
        }
        
        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-card.primary { background: linear-gradient(135deg, #3498db, #2980b9); color: white; }
        .stat-card.success { background: linear-gradient(135deg, #27ae60, #229954); color: white; }
        .stat-card.warning { background: linear-gradient(135deg, #f39c12, #e67e22); color: white; }
        .stat-card.danger { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4><i class="fas fa-bus me-2"></i>BusTicket Admin</h4>
            </div>
            
            <div class="components">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="/admin/dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/buses*') ? 'active' : '' }}" href="/admin/buses">
                            <i class="fas fa-bus"></i> Manajemen Bus
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/routes*') ? 'active' : '' }}" href="/admin/routes">
                            <i class="fas fa-route"></i> Manajemen Rute
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/schedules*') ? 'active' : '' }}" href="/admin/schedules">
                            <i class="fas fa-calendar-alt"></i> Manajemen Jadwal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/bookings*') ? 'active' : '' }}" href="/admin/bookings">
                            <i class="fas fa-ticket-alt"></i> Manajemen Pemesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="/admin/users">
                            <i class="fas fa-users"></i> Manajemen Pengguna
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link" href="/dashboard">
                            <i class="fas fa-arrow-left"></i> Kembali ke User
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content" style="margin-left: 250px;">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <form method="POST" action="/logout">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                const content = document.getElementById('content');
                
                if (sidebar.style.marginLeft === '0px' || !sidebar.style.marginLeft) {
                    sidebar.style.marginLeft = '-250px';
                    content.style.marginLeft = '0';
                } else {
                    sidebar.style.marginLeft = '0';
                    content.style.marginLeft = '250px';
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>