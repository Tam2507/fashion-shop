<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Atino Fashion - Thời Trang Cao Cấp')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { 
            --primary: #8B3A3A;
            --secondary: #D4A574;
            --accent: #C41E3A;
            --light: #F5F1E8;
            --dark: #2B2B2B;
            --gold: #B8860B;
        }
        
        * { font-family: 'Nunito', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Nunito', sans-serif; font-weight: 700; }
        
        body { 
            background-color: var(--light); 
            color: var(--dark);
        }
        
        /* Header & Navbar */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 1rem 0;
            border-bottom: 1px solid #e9e4d6;
        }
        
        .navbar-brand {
            font-family: 'Nunito', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            color: var(--primary) !important;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .nav-link {
            color: var(--dark) !important;
            font-weight: 500;
            margin: 0 15px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--primary) !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background-color: var(--primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        /* Search icon in navbar */
        .nav-link i.fa-search {
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover i.fa-search {
            transform: scale(1.2);
            color: var(--primary);
        }
        
        /* Search Dropdown */
        .search-dropdown-menu {
            min-width: 400px;
            padding: 0;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            border-radius: 12px;
            margin-top: 10px;
        }
        
        .search-box-dropdown {
            padding: 20px;
        }
        
        .search-box-dropdown .input-group {
            margin-bottom: 15px;
        }
        
        .search-box-dropdown .form-control {
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            font-size: 15px;
            border-radius: 8px 0 0 8px;
        }
        
        .search-box-dropdown .form-control:focus {
            border-color: var(--primary);
            box-shadow: none;
        }
        
        .search-box-dropdown .btn-primary {
            border-radius: 0 8px 8px 0;
            padding: 12px 20px;
        }
        
        .search-results-dropdown {
            max-height: 400px;
            overflow-y: auto;
            margin: 0 -20px;
        }
        
        .search-result-item-dropdown {
            display: flex;
            gap: 12px;
            padding: 12px 20px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .search-result-item-dropdown:hover {
            background: #f8f9fa;
            transform: translateX(3px);
        }
        
        .search-result-item-dropdown:last-child {
            border-bottom: none;
        }
        
        .search-result-image-dropdown {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            flex-shrink: 0;
        }
        
        .search-result-image-placeholder-dropdown {
            width: 60px;
            height: 60px;
            background: #f8f9fa;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            flex-shrink: 0;
        }
        
        .search-result-info-dropdown {
            flex: 1;
            min-width: 0;
        }
        
        .search-result-title-dropdown {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 3px;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .search-result-category-dropdown {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 3px;
        }
        
        .search-result-price-dropdown {
            font-size: 15px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .search-no-results-dropdown {
            text-align: center;
            padding: 30px 20px;
            color: #6c757d;
        }
        
        .search-no-results-dropdown i {
            font-size: 2rem;
            margin-bottom: 10px;
            opacity: 0.5;
        }
        
        .search-results-dropdown::-webkit-scrollbar {
            width: 6px;
        }
        
        .search-results-dropdown::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .search-results-dropdown::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }
        
        .search-results-dropdown::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }
        
        @media (max-width: 768px) {
            .search-dropdown-menu {
                min-width: 320px;
                max-width: 90vw;
            }
        }
        
        .cart-icon {
            position: relative;
            font-size: 1.3rem;
            color: var(--primary);
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -10px;
            background: var(--accent);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #8B3A3A 0%, #A8563A 50%, #6B4C40 100%);
            color: white;
            padding: 6rem 2rem;
            text-align: center;
            margin-bottom: 4rem;
            border-radius: 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="15" fill="white" opacity="0.03"/><circle cx="80" cy="80" r="20" fill="white" opacity="0.03"/></svg>');
            pointer-events: none;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
            letter-spacing: 2px;
        }
        
        .hero p {
            font-size: 1.3rem;
            font-weight: 300;
            position: relative;
            z-index: 1;
            letter-spacing: 1px;
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary);
            border: none;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 4px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .btn-primary:hover {
            background: var(--accent);
            box-shadow: 0 8px 25px rgba(139, 58, 58, 0.3);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: var(--secondary);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: var(--gold);
            color: white;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 4px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }
        
        .card-img-top {
            height: 250px;
            object-fit: cover;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .card-text {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 4px;
            border-left: 4px solid;
            animation: slideIn 0.3s ease;
        }
        
        .alert-success {
            border-left-color: #27ae60;
            background: #f0f9f5;
            color: #1b5e20;
        }
        
        .alert-danger {
            border-left-color: var(--accent);
            background: #fff0f0;
            color: #c41e3a;
        }
        
        .alert-warning {
            border-left-color: var(--secondary);
            background: #fff9f0;
            color: #8B3A3A;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Section Headers */
        h2 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 3rem;
            position: relative;
            padding-bottom: 1rem;
        }
        
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--secondary);
        }
        
        /* Forms */
        .form-control,
        .form-select {
            border: 1px solid #d9d4c6;
            border-radius: 4px;
            padding: 12px 15px;
            font-size: 0.95rem;
        }
        
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(139, 58, 58, 0.1);
        }
        
        .form-label {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        /* Tables */
        .table {
            background: white;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .table thead {
            background: var(--primary);
            color: white;
        }
        
        .table tbody tr:hover {
            background-color: var(--light);
        }
        
        /* Footer */
        footer {
            background: var(--dark) !important;
            border-top: 3px solid var(--primary);
        }
        
        footer h5, footer h6 {
            color: white !important;
            font-family: 'Nunito', sans-serif;
        }
        
        footer a:hover {
            color: var(--secondary) !important;
            transition: color 0.3s ease;
        }
        
        footer .text-white-50:hover {
            color: white !important;
        }
        
        /* Badges */
        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .badge.bg-danger {
            background: var(--accent) !important;
        }
        
        .badge.bg-success {
            background: #27ae60 !important;
        }
        
        /* User Dropdown Styles */
        .user-dropdown-toggle {
            display: flex !important;
            align-items: center;
            gap: 10px;
            padding: 8px 16px !important;
            border-radius: 50px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .user-dropdown-toggle:hover {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white !important;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 58, 58, 0.3);
        }
        
        .user-dropdown-toggle:hover .user-name {
            color: white !important;
        }
        
        .user-dropdown-toggle:hover .user-avatar-placeholder {
            background: white;
            color: var(--primary);
        }
        
        .user-avatar-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
            transition: all 0.3s ease;
        }
        
        .user-avatar-placeholder {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--dark);
            transition: all 0.3s ease;
        }
        
        .user-dropdown-menu {
            border: none;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            border-radius: 12px;
            padding: 0;
            min-width: 280px;
            margin-top: 10px;
            overflow: hidden;
        }
        
        .user-info-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
        }
        
        .user-avatar-large {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }
        
        .user-avatar-large-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .user-info-header .text-muted {
            color: rgba(255,255,255,0.8) !important;
            font-size: 0.85rem;
        }
        
        .user-dropdown-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            color: var(--dark);
            font-weight: 500;
        }
        
        .user-dropdown-item i {
            width: 20px;
            text-align: center;
            color: var(--primary);
            transition: all 0.3s ease;
        }
        
        .user-dropdown-item:hover {
            background: linear-gradient(90deg, rgba(139, 58, 58, 0.1) 0%, rgba(196, 30, 58, 0.1) 100%);
            color: var(--primary);
            padding-left: 25px;
        }
        
        .user-dropdown-item:hover i {
            transform: scale(1.2);
        }
        
        .logout-item {
            color: #dc3545;
        }
        
        .logout-item i {
            color: #dc3545;
        }
        
        .logout-item:hover {
            background: linear-gradient(90deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.15) 100%);
            color: #dc3545;
        }
        
        /* Admin Dropdown Styles */
        .admin-dropdown-toggle {
            display: flex !important;
            align-items: center;
            gap: 8px;
            padding: 8px 16px !important;
            border-radius: 50px;
            background: linear-gradient(135deg, #8B3A3A 0%, #C41E3A 100%);
            color: white !important;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            font-weight: 600;
        }
        
        .admin-dropdown-toggle:hover {
            background: linear-gradient(135deg, #C41E3A 0%, #8B3A3A 100%);
            border-color: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 58, 58, 0.4);
        }
        
        .admin-dropdown-toggle i {
            font-size: 16px;
        }
        
        .admin-dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border-radius: 12px;
            padding: 0;
            min-width: 300px;
            margin-top: 10px;
            overflow: hidden;
            max-height: 600px;
            overflow-y: auto;
        }
        
        .admin-dropdown-header {
            background: linear-gradient(135deg, #8B3A3A 0%, #C41E3A 100%);
            color: white;
            font-weight: 700;
            font-size: 14px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .admin-dropdown-header i {
            font-size: 18px;
        }
        
        .admin-section-header {
            background: linear-gradient(90deg, rgba(139, 58, 58, 0.1) 0%, rgba(196, 30, 58, 0.05) 100%);
            color: var(--primary);
            font-weight: 600;
            font-size: 12px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-left: 3px solid var(--primary);
        }
        
        .admin-section-header i {
            font-size: 14px;
        }
        
        .admin-dropdown-item {
            padding: 12px 20px 12px 35px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            color: var(--dark);
            font-weight: 500;
            position: relative;
        }
        
        .admin-dropdown-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(139, 58, 58, 0.1) 0%, transparent 100%);
            transition: width 0.3s ease;
        }
        
        .admin-dropdown-item:hover::before {
            width: 100%;
        }
        
        .admin-dropdown-item i {
            width: 20px;
            text-align: center;
            color: var(--primary);
            transition: all 0.3s ease;
            font-size: 15px;
        }
        
        .admin-dropdown-item:hover {
            background: linear-gradient(90deg, rgba(139, 58, 58, 0.08) 0%, rgba(196, 30, 58, 0.05) 100%);
            color: var(--primary);
            padding-left: 40px;
            border-left: 3px solid var(--secondary);
        }
        
        .admin-dropdown-item:hover i {
            transform: scale(1.2) rotate(5deg);
            color: var(--accent);
        }
        
        .admin-dropdown-menu .dropdown-divider {
            margin: 0;
            border-color: rgba(139, 58, 58, 0.1);
        }
        
        .admin-dropdown-menu::-webkit-scrollbar {
            width: 8px;
        }
        
        .admin-dropdown-menu::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .admin-dropdown-menu::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 10px;
        }
        
        .admin-dropdown-menu::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--accent) 0%, var(--primary) 100%);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="{{ route('home') }}">Fashion Shop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang Chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Cửa Hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Về Chúng Tôi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Liên Hệ</a></li>
                
                <!-- Search Dropdown -->
                <li class="nav-item dropdown search-dropdown">
                    <a class="nav-link" href="#" id="searchDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end search-dropdown-menu" aria-labelledby="searchDropdown">
                        <div class="search-box-dropdown">
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control" 
                                       id="searchInputDropdown" 
                                       placeholder="Tìm kiếm..."
                                       autocomplete="off">
                                <button class="btn btn-primary" type="button" id="searchButtonDropdown">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            
                            <div id="searchResultsDropdown" class="search-results-dropdown"></div>
                            
                            <div id="searchLoadingDropdown" class="text-center py-3" style="display: none;">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Đang tìm...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                
                @auth
                    @if(auth()->user()->is_admin)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle admin-dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cogs"></i> Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end admin-dropdown-menu">
                                <li class="dropdown-header admin-dropdown-header">
                                    <i class="fas fa-shield-alt"></i> Quản Trị Hệ Thống
                                </li>
                                <li><a class="dropdown-item admin-dropdown-item" href="{{ route('admin.home') }}"><i class="fas fa-home"></i> Admin Home</a></li>
                                <li><a class="dropdown-item admin-dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line"></i> Dashboard Thống Kê</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header admin-section-header">
                                    <i class="fas fa-store"></i> Quản Lý Cửa Hàng
                                </li>
                                <li><a class="dropdown-item admin-dropdown-item" href="{{ route('admin.products.index') }}"><i class="fas fa-box"></i> Sản Phẩm</a></li>
                                <li><a class="dropdown-item admin-dropdown-item" href="{{ route('admin.categories.index') }}"><i class="fas fa-tags"></i> Danh Mục</a></li>
                                <li><a class="dropdown-item admin-dropdown-item" href="{{ route('admin.orders.index') }}"><i class="fas fa-shopping-cart"></i> Đơn Hàng</a></li>
                                <li><a class="dropdown-item admin-dropdown-item" href="{{ route('admin.users') }}"><i class="fas fa-users"></i> Quản Lý Tài Khoản</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-header admin-section-header">
                                    <i class="fas fa-palette"></i> Giao Diện & Cài Đặt
                                </li>
                                <li><a class="dropdown-item admin-dropdown-item" href="{{ route('admin.banners.index') }}"><i class="fas fa-images"></i> Quản Lý Banner</a></li>
                                <li><a class="dropdown-item admin-dropdown-item" href="{{ route('admin.footer-settings.index') }}"><i class="fas fa-cog"></i> Cài Đặt Footer</a></li>
                            </ul>
                        </li>
                    @endif
                    
                    <li class="nav-item">
                        <a href="{{ route('wishlist.index') }}" class="nav-link position-relative" title="Danh sách yêu thích">
                            <i class="fas fa-heart"></i>
                            @php
                                $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
                            @endphp
                            @if($wishlistCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('cart.index') }}" class="nav-link">
                            <div class="cart-icon">
                                <i class="fas fa-shopping-bag"></i>
                                @php
                                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                                @endphp
                                <span class="cart-count">{{ $cartCount }}</span>
                            </div>
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle user-dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            @if(auth()->user()->avatar)
                                <img src="{{ \App\Services\ImageUploadService::url(auth()->user()->avatar) }}" alt="Avatar" class="user-avatar-img">
                            @else
                                <div class="user-avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <span class="user-name">{{ Str::limit(auth()->user()->name, 15) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                            <li class="dropdown-header">
                                <div class="user-info-header">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ \App\Services\ImageUploadService::url(auth()->user()->avatar) }}" alt="Avatar" class="user-avatar-large">
                                    @else
                                        <div class="user-avatar-large-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                                        <small class="text-muted">{{ auth()->user()->email }}</small>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item user-dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-circle"></i> Thông Tin Cá Nhân</a></li>
                            <li><a class="dropdown-item user-dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-history"></i> Đơn Hàng</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                    @csrf
                                    <button class="dropdown-item user-dropdown-item logout-item" type="submit"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Đăng Nhập</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Đăng Ký</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Alerts -->
<div class="container-fluid px-4">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-check-circle"></i> <strong>Thành công!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-exclamation-circle"></i> <strong>Lỗi!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <strong>Vui lòng kiểm tra:</strong>
                <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
</div>

<!-- Main Content -->
<div class="container-fluid px-4">
    <div class="container">
        @yield('content')
    </div>
</div>

<!-- Footer -->
@php
    $footerSettings = \App\Models\FooterSetting::first();
@endphp

<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase fw-bold mb-3">{{ $footerSettings->company_name ?? 'Fashion Shop' }}</h5>
                @if($footerSettings)
                    @if($footerSettings->company_description)
                        <p class="mb-2">{{ $footerSettings->company_description }}</p>
                    @endif
                    @if($footerSettings->business_license)
                        <p class="mb-1">{{ $footerSettings->business_license }}</p>
                    @endif
                    @if($footerSettings->address)
                        <p class="mb-1">Địa chỉ: {{ $footerSettings->address }}</p>
                    @endif
                    @if($footerSettings->phone)
                        <p class="mb-1">Điện thoại: <strong>{{ $footerSettings->phone }}</strong></p>
                    @endif
                    @if($footerSettings->hotline)
                        <p class="mb-1">Hotline: <strong>{{ $footerSettings->hotline }}</strong></p>
                    @endif
                    @if($footerSettings->email)
                        <p class="mb-0">Email: <a href="mailto:{{ $footerSettings->email }}" class="text-white">{{ $footerSettings->email }}</a></p>
                    @endif
                @else
                    <p class="mb-2"><strong>Công ty TNHH Fashion Shop</strong></p>
                    <p class="mb-1">Số ĐKKD: 0123456789 do Sở KH&ĐT TP.HCM cấp ngày 01/01/2026</p>
                    <p class="mb-1">Địa chỉ: Lầu 1-2, 123 Nguyễn Văn Cừ, Quận 1, TP.HCM</p>
                @endif
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="text-uppercase fw-bold mb-3">Giới thiệu</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-white-50 text-decoration-none">Về Fashion Shop</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}" class="text-white-50 text-decoration-none">Liên hệ showroom</a></li>
                    <li class="mb-2"><a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none">Sản phẩm</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="text-uppercase fw-bold mb-3">Chăm sóc khách hàng</h6>
                @if($footerSettings && $footerSettings->working_hours)
                    <p class="mb-2"><strong>Giờ làm việc:</strong></p>
                    <p class="mb-2 text-white-50">{!! nl2br(e($footerSettings->working_hours)) !!}</p>
                @endif
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Hướng dẫn thanh toán</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Tra cứu đơn hàng</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Quy định đổi hàng</a></li>
                </ul>
            </div>

            <!-- Payment & Social -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="text-uppercase fw-bold mb-3">Phương thức thanh toán</h6>
                @php
                    $paymentMethods = \App\Models\PaymentMethod::active()->ordered()->get();
                @endphp
                
                @if($paymentMethods->count() > 0)
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($paymentMethods as $method)
                            @if($method->logo)
                                <img src="{{ \App\Services\ImageUploadService::url($method->logo) }}" alt="{{ $method->name }}" 
                                     class="border rounded" style="height: 24px; max-width: 60px; object-fit: contain;">
                            @else
                                <span class="badge bg-secondary">{{ $method->name }}</span>
                            @endif
                        @endforeach
                    </div>
                @endif
                
                @if($footerSettings)
                    <div class="mt-3">
                        <h6 class="text-uppercase fw-bold mb-2">Kết nối với chúng tôi</h6>
                        <div class="d-flex gap-3">
                            @if($footerSettings->social_facebook)
                                <a href="{{ $footerSettings->social_facebook }}" target="_blank" class="text-white-50"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if($footerSettings->social_instagram)
                                <a href="{{ $footerSettings->social_instagram }}" target="_blank" class="text-white-50"><i class="fab fa-instagram"></i></a>
                            @endif
                            @if($footerSettings->social_youtube)
                                <a href="{{ $footerSettings->social_youtube }}" target="_blank" class="text-white-50"><i class="fab fa-youtube"></i></a>
                            @endif
                            @if($footerSettings->social_tiktok)
                                <a href="{{ $footerSettings->social_tiktok }}" target="_blank" class="text-white-50"><i class="fab fa-tiktok"></i></a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bottom Bar -->
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-12 text-center">
                <p class="mb-0 small">
                    @if($footerSettings && $footerSettings->copyright_text)
                        {{ $footerSettings->copyright_text }}
                    @else
                        &copy; {{ date('Y') }} <strong>Fashion Shop</strong> - Thời Trang Cao Cấp. All rights reserved.
                    @endif
                </p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@yield('extra_js')

<script>
// Function to update cart count
function updateCartCount() {
    @auth
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const cartCountElement = document.querySelector('.cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = data.count || 0;
        }
    })
    .catch(error => console.log('Error updating cart count:', error));
    @endauth
}

// Update cart count when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});

// Listen for form submissions to cart
document.addEventListener('submit', function(e) {
    if (e.target.action && e.target.action.includes('/cart/add/')) {
        // Wait a bit for the server to process, then update count
        setTimeout(updateCartCount, 500);
    }
});

// Search functionality (Dropdown)
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInputDropdown');
    const searchButton = document.getElementById('searchButtonDropdown');
    const searchResults = document.getElementById('searchResultsDropdown');
    const searchLoading = document.getElementById('searchLoadingDropdown');
    const searchDropdown = document.querySelector('.search-dropdown');
    
    if (!searchInput || !searchButton) return;
    
    // Focus input when dropdown opens
    searchDropdown.addEventListener('shown.bs.dropdown', function() {
        searchInput.focus();
    });
    
    // Clear results when dropdown closes
    searchDropdown.addEventListener('hidden.bs.dropdown', function() {
        searchInput.value = '';
        searchResults.innerHTML = '';
    });
    
    // Search on button click
    searchButton.addEventListener('click', performSearchDropdown);
    
    // Search on Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearchDropdown();
        }
    });
    
    // Live search (debounced)
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchResults.innerHTML = '';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            performSearchDropdown();
        }, 500);
    });
    
    function performSearchDropdown() {
        const query = searchInput.value.trim();
        
        if (query.length < 2) {
            searchResults.innerHTML = '<div class="search-no-results-dropdown"><i class="fas fa-search"></i><p class="mb-0">Nhập ít nhất 2 ký tự</p></div>';
            return;
        }
        
        // Show loading
        searchLoading.style.display = 'block';
        searchResults.innerHTML = '';
        
        // Perform AJAX search
        fetch(`/api/search?q=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            searchLoading.style.display = 'none';
            
            if (data.products && data.products.length > 0) {
                displaySearchResultsDropdown(data.products);
            } else {
                searchResults.innerHTML = '<div class="search-no-results-dropdown"><i class="fas fa-search"></i><p class="mb-0">Không tìm thấy sản phẩm</p></div>';
            }
        })
        .catch(error => {
            searchLoading.style.display = 'none';
            searchResults.innerHTML = '<div class="search-no-results-dropdown"><i class="fas fa-exclamation-circle"></i><p class="mb-0">Có lỗi xảy ra</p></div>';
            console.error('Search error:', error);
        });
    }
    
    function displaySearchResultsDropdown(products) {
        let html = '';
        
        products.forEach(product => {
            const imageUrl = product.image 
                ? `/storage/${product.image}` 
                : (product.first_image ? `/storage/${product.first_image}` : null);
            
            html += `
                <a href="/products/${product.id}" class="search-result-item-dropdown">
                    ${imageUrl 
                        ? `<img src="${imageUrl}" alt="${product.name}" class="search-result-image-dropdown">`
                        : `<div class="search-result-image-placeholder-dropdown"><i class="fas fa-image"></i></div>`
                    }
                    <div class="search-result-info-dropdown">
                        <div class="search-result-title-dropdown">${product.name}</div>
                        <div class="search-result-category-dropdown">${product.category_name || 'Chưa phân loại'}</div>
                        <div class="search-result-price-dropdown">${formatPrice(product.price)} ₫</div>
                    </div>
                </a>
            `;
        });
        
        searchResults.innerHTML = html;
    }
    
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
});
</script>

<!-- Page Transition Effect -->
<style>
/* Page fade in animation only - no white overlay */
body {
    animation: fadeIn 0.4s ease;
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
.container, .container-fluid {
    animation: contentFadeIn 0.5s ease;
}

@keyframes contentFadeIn {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Simple fade in on page load - no transition overlay
document.addEventListener('DOMContentLoaded', function() {
    // Just let the CSS animation handle the fade in
    // No need to intercept clicks or show overlay
});
</script>

<!-- Chat Box -->
@include('components.chat-box')

</body>
</html>