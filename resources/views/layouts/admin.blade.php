<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Lato', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        body {
            display: flex;
            background-color: #f8f9fa;
            margin: 0;
            font-family: 'Lato', sans-serif;
        }

        /* Main Content */
        .admin-content {
            margin-left: 260px;
            flex: 1;
            padding: 20px;
            min-height: 100vh;
            background: #f8f9fa;
        }

        /* Sidebar Navigation */
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #1a252f 0%, #2c3e50 50%, #34495e 100%);
            padding: 0;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .admin-sidebar .logo {
            background: linear-gradient(135deg, #8B3A3A 0%, #C41E3A 100%);
            text-align: center;
            padding: 30px 20px;
            border-bottom: 3px solid rgba(212, 165, 116, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .admin-sidebar .logo::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: logoShine 3s ease-in-out infinite;
        }
        
        @keyframes logoShine {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-20%, -20%); }
        }

        .admin-sidebar .logo .logo-icon {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            color: #8B3A3A;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .admin-sidebar .logo:hover .logo-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .admin-sidebar .logo h3 {
            color: white;
            font-size: 22px;
            margin: 0;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }

        .admin-sidebar .logo p {
            color: rgba(255,255,255,0.9);
            font-size: 13px;
            margin: 8px 0 0 0;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        .nav-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .nav-menu li a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 25px;
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            border-left: 4px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .nav-menu li a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(212, 165, 116, 0.2) 0%, transparent 100%);
            transition: width 0.3s ease;
            z-index: 0;
        }
        
        .nav-menu li a:hover::before {
            width: 100%;
        }

        .nav-menu li a i {
            font-size: 17px;
            width: 24px;
            text-align: center;
            color: #95a5a6;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .nav-menu li a:hover {
            background: linear-gradient(90deg, rgba(52, 73, 94, 0.8) 0%, rgba(44, 62, 80, 0.5) 100%);
            color: white;
            border-left-color: #D4A574;
            padding-left: 30px;
        }
        
        .nav-menu li a:hover i {
            color: #D4A574;
            transform: scale(1.15);
        }

        .nav-menu li a.active {
            background: linear-gradient(90deg, #8B3A3A 0%, #C41E3A 100%);
            color: white;
            border-left-color: #D4A574;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.2);
        }

        .nav-menu li a.active i {
            color: #D4A574;
            animation: iconPulse 2s ease-in-out infinite;
        }
        
        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .nav-menu li hr {
            border-color: rgba(212, 165, 116, 0.2);
            margin: 15px 20px;
        }
        
        .nav-menu li form button {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 14px 25px;
            color: #bdc3c7;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .nav-menu li form button::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(220, 53, 69, 0.2) 0%, transparent 100%);
            transition: width 0.3s ease;
            z-index: 0;
        }
        
        .nav-menu li form button:hover::before {
            width: 100%;
        }
        
        .nav-menu li form button i {
            font-size: 17px;
            width: 24px;
            text-align: center;
            color: #e74c3c;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .nav-menu li form button:hover {
            background: linear-gradient(90deg, rgba(231, 76, 60, 0.2) 0%, rgba(192, 57, 43, 0.1) 100%);
            color: #e74c3c;
            border-left-color: #e74c3c;
            padding-left: 30px;
        }
        
        .nav-menu li form button:hover i {
            transform: scale(1.15) rotate(-10deg);
        }

        /* Main Content */
        .admin-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            min-height: 100vh;
        }

        /* Header */
        .admin-header {
            background: white;
            padding: 15px 30px;
            border-radius: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-bottom: 1px solid #e9ecef;
        }

        .admin-header h1 {
            font-size: 24px;
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .admin-header h1 i {
            font-size: 20px;
            color: #3498db;
        }

        .admin-user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-user-menu .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #7f8c8d;
            font-size: 14px;
        }

        .admin-user-menu .user-avatar {
            width: 35px;
            height: 35px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .logout-btn {
            background-color: #8B3A3A;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 500;
        }

        .logout-btn:hover {
            background-color: #6B2C2C;
            color: #D4A574;
        }

        /* Tabs & Content */
        .nav-tabs {
            border-bottom: 2px solid #D4A574;
            margin-bottom: 30px;
        }

        .nav-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-tabs .nav-link:hover {
            color: #8B3A3A;
            border-bottom-color: #D4A574;
        }

        .nav-tabs .nav-link.active {
            color: #8B3A3A;
            border-bottom-color: #8B3A3A;
            background: none;
        }

        /* Table Styles */
        .table-responsive {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f5f1e8;
            color: #8B3A3A;
            border-bottom: 2px solid #D4A574;
            font-weight: 600;
            padding: 15px;
        }

        .table tbody tr {
            transition: background-color 0.3s;
        }

        .table tbody tr:hover {
            background-color: #f9f7f3;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 6px 10px;
            font-size: 13px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            color: white;
        }

        .btn-edit {
            background-color: #5B9BD5;
        }

        .btn-edit:hover {
            background-color: #3B7FB8;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: #C5504B;
        }

        .btn-delete:hover {
            background-color: #A53D38;
            transform: translateY(-2px);
        }

        .btn-add {
            background-color: #70AD47;
            padding: 10px 20px;
            font-size: 14px;
        }

        .btn-add:hover {
            background-color: #56963D;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 60px;
            color: #D4A574;
            margin-bottom: 20px;
        }

        .empty-state p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
        }

        .badge-status-active {
            background-color: #70AD47;
            color: white;
        }

        .badge-status-inactive {
            background-color: #C5504B;
            color: white;
        }

        /* Scrollbar */
        .admin-sidebar::-webkit-scrollbar {
            width: 10px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #D4A574 0%, #8B3A3A 100%);
            border-radius: 10px;
            border: 2px solid rgba(0, 0, 0, 0.2);
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #8B3A3A 0%, #C41E3A 100%);
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
            }

            .admin-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .mobile-menu-toggle {
                display: block !important;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1001;
                background: #3498db;
                color: white;
                border: none;
                width: 45px;
                height: 45px;
                border-radius: 8px;
                font-size: 20px;
                cursor: pointer;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        .mobile-menu-toggle {
            display: none;
        }
    </style>
    @yield('extra_css')
</head>
<body>
    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-store"></i>
            </div>
            <h3>SyncPOS</h3>
            <p>Boutique Super Shop<br>Admin Panel</p>
        </div>

        <ul class="nav-menu">
            <li><a href="{{ route('admin.home') }}" class="{{ request()->is('admin') ? 'active' : '' }}"><i class="fas fa-home"></i> Trang Chủ Admin</a></li>
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Dashboard Thống Kê</a></li>
            <li><a href="{{ route('admin.products.index') }}" class="{{ request()->is('admin/products*') ? 'active' : '' }}"><i class="fas fa-box"></i> Sản Phẩm</a></li>
            <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->is('admin/categories*') ? 'active' : '' }}"><i class="fas fa-tags"></i> Danh Mục</a></li>
            <li><a href="{{ route('admin.orders.index') }}" class="{{ request()->is('admin/orders*') ? 'active' : '' }}"><i class="fas fa-shopping-cart"></i> Đơn Hàng</a></li>
            <li><a href="{{ route('admin.users') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}"><i class="fas fa-users"></i> Khách Hàng</a></li>
            <li><a href="{{ route('admin.admins.index') }}" class="{{ request()->is('admin/admins*') ? 'active' : '' }}"><i class="fas fa-user-shield"></i> Quản Lý Admin</a></li>
            <li><a href="{{ route('profile.edit') }}" class="{{ request()->is('profile*') ? 'active' : '' }}"><i class="fas fa-user-circle"></i> Thông Tin Cá Nhân</a></li>
            <li><hr style="border-color: rgba(212, 165, 116, 0.2); margin: 20px 0;"></li>
            <li><a href="{{ route('admin.banners.index') }}" class="{{ request()->is('admin/banners*') ? 'active' : '' }}"><i class="fas fa-images"></i> Quản Lý Banner</a></li>
            <li><a href="{{ route('admin.features.index') }}" class="{{ request()->is('admin/features*') ? 'active' : '' }}"><i class="fas fa-star"></i> Quản Lý Tính Năng</a></li>
            <li><a href="{{ route('admin.payment-methods.index') }}" class="{{ request()->is('admin/payment-methods*') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> Phương Thức Thanh Toán</a></li>
            <li><a href="{{ route('admin.coupons.index') }}" class="{{ request()->is('admin/coupons*') ? 'active' : '' }}"><i class="fas fa-ticket-alt"></i> Quản Lý Mã Giảm Giá</a></li>
            <li><a href="{{ route('admin.product-sections.index') }}" class="{{ request()->is('admin/product-sections*') ? 'active' : '' }}"><i class="fas fa-layer-group"></i> Section Sản Phẩm</a></li>
            <li><a href="{{ route('admin.posts.index') }}" class="{{ request()->is('admin/posts*') ? 'active' : '' }}"><i class="fas fa-newspaper"></i> Quản Lý Bài Viết</a></li>
            <li><a href="{{ route('admin.settings.momo') }}" class="{{ request()->is('admin/settings/momo*') ? 'active' : '' }}"><i class="fas fa-wallet"></i> Cấu Hình MoMo QR</a></li>
            <li><a href="{{ route('admin.messages.index') }}" class="{{ request()->is('admin/messages*') ? 'active' : '' }}"><i class="fas fa-comments"></i> Tin Nhắn Khách Hàng</a></li>
            <li><a href="{{ route('admin.contacts.index') }}" class="{{ request()->is('admin/contacts') ? 'active' : '' }}"><i class="fas fa-envelope"></i> Tin Nhắn Liên Hệ</a></li>
            <li><a href="{{ route('admin.contact-info.edit') }}" class="{{ request()->is('admin/contact-info*') ? 'active' : '' }}"><i class="fas fa-address-card"></i> Thông Tin Liên Hệ</a></li>
            <li><a href="{{ route('admin.about.edit') }}" class="{{ request()->is('admin/about*') ? 'active' : '' }}"><i class="fas fa-info-circle"></i> Trang Về Chúng Tôi</a></li>
            <li><a href="{{ route('admin.footer-settings.index') }}" class="{{ request()->is('admin/footer-settings*') ? 'active' : '' }}"><i class="fas fa-cog"></i> Cài Đặt Footer</a></li>
            <li><hr style="border-color: rgba(212, 165, 116, 0.2); margin: 20px 0;"></li>
            <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Về Trang Chủ</a></li>
            <li><a href="{{ route('admin.unified') }}"><i class="fas fa-tachometer-alt"></i> Dashboard Unified</a></li>
            <li>
                <form method="POST" action="/logout" style="margin: 0;">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="admin-content">
        <!-- Header -->
        <div class="admin-header">
            <h1>
                <i class="@yield('header_icon', 'fas fa-cogs')"></i>
                @yield('page_title', 'Admin Dashboard')
            </h1>
            <div class="admin-user-menu">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        <!-- Content -->
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            adminSidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        }

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', toggleSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleSidebar);
        }

        // Close sidebar when clicking a menu item on mobile
        const menuLinks = document.querySelectorAll('.nav-menu a');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    adminSidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });
    </script>
    
    <!-- Page Transition Effect for Admin -->
    <style>
    /* Page fade in animation */
    body {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Content fade in */
    .admin-content, .container-fluid {
        animation: contentFadeIn 0.6s ease;
    }

    @keyframes contentFadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
    
    @yield('extra_js')
</body>
</html>
