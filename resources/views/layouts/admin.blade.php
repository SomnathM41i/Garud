<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>

    <!-- Laravel UI / Vite Assets -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            /* Light Theme Colors */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --sidebar-text: #cbd5e1;
            --sidebar-text-active: #ffffff;
            --navbar-bg: #ffffff;
            --navbar-shadow: rgba(0, 0, 0, 0.05);
            --card-bg: #ffffff;
            --card-shadow: rgba(0, 0, 0, 0.04);
            --accent-primary: #d4af37;
            --accent-secondary: #b8941e;
            --accent-gradient: linear-gradient(135deg, #d4af37 0%, #ffd700 100%);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
        }

        [data-theme="dark"] {
            /* Dark Theme Colors */
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --sidebar-bg: linear-gradient(180deg, #020617 0%, #0c1220 100%);
            --sidebar-hover: rgba(255, 255, 255, 0.15);
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #f1f5f9;
            --navbar-bg: #1e293b;
            --navbar-shadow: rgba(0, 0, 0, 0.3);
            --card-bg: #1e293b;
            --card-shadow: rgba(0, 0, 0, 0.2);
            --input-bg: #1e293b;
            --input-border: #334155;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            width: 280px;
            background: var(--sidebar-bg);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-container {
            width: 48px;
            height: 48px;
            background: var(--accent-gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .logo-container i {
            color: white;
            font-size: 1.5rem;
        }

        .brand-text {
            flex: 1;
        }

        .brand-text h4 {
            color: var(--sidebar-text-active);
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .brand-text span {
            color: var(--sidebar-text);
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-section {
            padding: 1.5rem 0;
        }

        .nav-section-title {
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }

        .nav {
            list-style: none;
            padding: 0;
        }

        .nav-item {
            margin: 0.25rem 0;
        }

        .nav-link {
            color: var(--sidebar-text);
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            font-weight: 500;
            font-size: 0.9375rem;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: var(--accent-gradient);
            transition: height 0.2s ease;
            border-radius: 0 3px 3px 0;
        }

        .nav-link:hover,
        .nav-link.active {
            background: var(--sidebar-hover);
            color: var(--sidebar-text-active);
        }

        .nav-link.active::before {
            height: 60%;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.125rem;
        }

        .nav-badge {
            margin-left: auto;
            padding: 0.25rem 0.625rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(212, 175, 55, 0.2);
            color: var(--accent-primary);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Navbar */
        .navbar {
            background: var(--navbar-bg) !important;
            box-shadow: 0 1px 3px var(--navbar-shadow);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 999;
            backdrop-filter: blur(10px);
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .search-bar {
            position: relative;
            max-width: 400px;
            flex: 1;
        }

        .search-bar input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.75rem;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 0.9375rem;
            transition: all 0.2s ease;
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: var(--bg-primary);
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .icon-btn:hover {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            transform: translateY(-2px);
        }

        .icon-btn .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            font-size: 0.625rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid var(--border-color);
        }

        .user-profile:hover {
            background: var(--bg-secondary);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.2;
        }

        /* Content Area */
        main {
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 0.9375rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px var(--card-shadow);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent-gradient);
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px var(--card-shadow);
            transform: translateY(-4px);
        }

        .stat-card.primary::before {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .stat-card.success::before {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stat-card.warning::before {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .stat-card.danger::before {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .stat-icon.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .stat-icon.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .stat-icon.gold {
            background: var(--accent-gradient);
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }

        .stat-trend.up {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-trend.down {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .stat-body {
            margin-bottom: 0.5rem;
        }

        .stat-title {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-footer {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Regular Cards */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 1px 3px var(--card-shadow);
            transition: all 0.2s ease;
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--input-border);
            border-radius: 10px;
            background: var(--input-bg);
            color: var(--text-primary);
            font-size: 0.9375rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid var(--input-border);
            border-radius: 4px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background: var(--accent-gradient);
            border-color: var(--accent-primary);
        }

        .form-check-label {
            font-size: 0.9375rem;
            color: var(--text-primary);
            cursor: pointer;
        }

        .input-group {
            display: flex;
            gap: 0;
        }

        .input-group .form-control {
            border-radius: 10px 0 0 10px;
        }

        .input-group-text {
            padding: 0.75rem 1rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--input-border);
            border-left: none;
            border-radius: 0 10px 10px 0;
            color: var(--text-secondary);
        }

        /* Buttons */
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background: var(--info);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-gold {
            background: var(--accent-gradient);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--border-color);
            color: var(--text-primary);
        }

        .btn-outline:hover {
            background: var(--bg-tertiary);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1rem;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            border-left: 4px solid;
        }

        .alert-icon {
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: var(--success);
            color: var(--success);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--warning);
            color: var(--warning);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-color: var(--danger);
            color: var(--danger);
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            border-color: var(--info);
            color: var(--info);
        }

        .alert-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0;
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Toast */
        /* .toast-container {
            position: fixed;
            top: 5rem;
            right: 2rem;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            pointer-events: none;
        } */

        /* Toast */
        .toast-container {
            position: fixed !important;
            top: 5rem !important;
            right: 2rem !important;
            z-index: 99999 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 1rem !important;
            pointer-events: none !important;
        }

        .toast {
            pointer-events: auto !important;
            background: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 12px !important;
            padding: 1rem 1.25rem !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5) !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            min-width: 320px !important;
            animation: slideInRight 0.3s ease forwards !important;
            opacity: 1 !important;
            transform: translateX(0) !important;
            visibility: visible !important;
        }

        /* Override universal transitions for toast */
        .toast,
        .toast *,
        .toast-container,
        .toast-container * {
            transition: none !important;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(400px);
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
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .toast-success .toast-icon {
            background: var(--success);
        }

        .toast-warning .toast-icon {
            background: var(--warning);
        }

        .toast-danger .toast-icon {
            background: var(--danger);
        }

        .toast-info .toast-icon {
            background: var(--info);
        }

        .toast-body {
            flex: 1;
        }

        .toast-title {
            font-weight: 600;
            font-size: 0.9375rem;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .toast-message {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .toast-success .toast-icon {
            background: var(--success);
        }

        .toast-warning .toast-icon {
            background: var(--warning);
        }

        .toast-danger .toast-icon {
            background: var(--danger);
        }

        .toast-info .toast-icon {
            background: var(--info);
        }

        .toast-body {
            flex: 1;
        }

        .toast-title {
            font-weight: 600;
            font-size: 0.9375rem;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .toast-message {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: var(--bg-tertiary);
        }

        .table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-color);
        }

        .table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .table tbody tr:hover {
            background: var(--bg-secondary);
        }

        /* Badges */
        .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .badge-info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .badge-gold {
            background: rgba(212, 175, 55, 0.1);
            color: var(--accent-primary);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: var(--bg-primary);
            color: var(--text-primary);
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.25rem;
            z-index: 1001;
        }

        /* Mobile Responsiveness */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .search-bar {
                display: none;
            }

            .navbar {
                padding: 1rem 1.5rem;
            }

            main {
                padding: 1.5rem;
            }

            .user-info {
                display: none;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }

            .toast-container {
                right: 1rem;
                left: 1rem;
                top: 4rem;
                /* Adjust for mobile navbar */
            }
        }

        @media (max-width: 640px) {
            .sidebar {
                width: 100%;
            }

            main {
                padding: 1rem;
            }

            .navbar {
                padding: 1rem;
            }

            .navbar-actions {
                gap: 0.5rem;
            }

            .icon-btn {
                width: 36px;
                height: 36px;
            }

            .page-header {
                margin-bottom: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 1.25rem;
            }

            .toast-container {
                right: 1rem;
                left: 1rem;
            }

            .toast {
                min-width: auto;
            }
        }

        /* Overlay for mobile menu */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, color, border-color;
            transition-duration: 0.3s;
            transition-timing-function: ease;
        }

        a,
        button,
        .nav-link,
        .icon-btn,
        .user-profile {
            transition-property: all;
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <i class="fas fa-gem"></i>
            </div>
            <div class="brand-text">
                <h4>Garud Jewellers</h4>
                <span>Admin Panel</span>
            </div>
        </div>

        <nav class="nav-section">
            <div class="nav-section-title">Main Menu</div>
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}"
                        href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-list"></i>
                        <span>Jewellery Categories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}"
                        href="{{ route('admin.products.index') }}">
                        <i class="fas fa-gem"></i>
                        <span>Jewelry Products</span>
                        <!-- <span class="nav-badge">245</span> -->
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/cart*') ? 'active' : '' }}"
                        href="{{ route('admin.cart.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Cart</span>
                        <!-- <span class="nav-badge">245</span> -->
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}"
                        href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                        <!-- <span class="nav-badge">245</span> -->
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin.reports.profit_loss') ? 'active' : '' }}"
                        href="{{ route('admin.reports.profit_loss') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>profit loss</span>
                        <!-- <span class="nav-badge">245</span> -->
                    </a>
                </li>

            </ul>
        </nav>



        <nav class="nav-section">
            <div class="nav-section-title">Settings</div>
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="#">
                        <i class="fas fa-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Store</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-content">
                <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                    <button class="mobile-menu-toggle" id="menuToggle" type="button">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <div class="navbar-actions">
                    <button class="icon-btn" id="themeToggle" title="Toggle Theme" type="button">
                        <i class="fas fa-sun"></i>
                    </button>

                    <!-- <button class="icon-btn" title="Notifications" type="button">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </button>

                    <button class="icon-btn" title="Messages" type="button">
                        <i class="fas fa-envelope"></i>
                        <span class="badge">5</span>
                    </button> -->

                    <div class="user-profile">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-role">Administrator</span>
                        </div>
                        <i class="fas fa-chevron-down" style="color: var(--text-muted); font-size: 0.75rem;"></i>
                    </div>
                </div>
            </div>
        </nav>



        <!-- Main Content Area -->
        <main>
            @yield('content')
        </main>
    </div>


    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Theme Toggle
            const html = document.documentElement;
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = themeToggle.querySelector('i');

            const savedTheme = localStorage.getItem('theme') ||
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            html.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);

            themeToggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            });

            function updateThemeIcon(theme) {
                themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }

            // Mobile Menu Toggle
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            menuToggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
            });

            sidebarOverlay.addEventListener('click', function () {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });

            // Close sidebar when clicking a link on mobile
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 1024) {
                        sidebar.classList.remove('active');
                        sidebarOverlay.classList.remove('active');
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function () {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                }
            });

            // Toast Function (Global)
            // Toast Function (Global) - with better error handling
            window.showToast = function (title, message, type = 'info') {
                //  console.log('showToast called:', { title, message, type });

                const toastContainer = document.getElementById('toastContainer');
                if (!toastContainer) {
                    console.error('Toast container not found!');
                    return;
                }

                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;

                const icons = {
                    success: 'fa-check-circle',
                    warning: 'fa-exclamation-triangle',
                    danger: 'fa-times-circle',
                    info: 'fa-info-circle'
                };

                toast.innerHTML = `
                    <div class="toast-icon">
                        <i class="fas ${icons[type] || icons.info}"></i>
                    </div>
                    <div class="toast-body">
                        <div class="toast-title">${title}</div>
                        <div class="toast-message">${message}</div>
                    </div>
                    <button class="alert-close" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                `;

                toastContainer.appendChild(toast);
                console.log('Toast appended to container');

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.style.animation = 'slideOutRight 0.3s ease';
                        setTimeout(() => toast.remove(), 300);
                    }
                }, 5000);
            };

            // Add slideOut animation
            // const style = document.createElement('style');
            // style.textContent = `
            //     @keyframes slideOutRight {
            //         from {
            //             transform: translateX(0);
            //             opacity: 1;
            //         }
            //         to {
            //             transform: translateX(400px);
            //             opacity: 0;
            //         }
            //     }
            // `;
            // document.head.appendChild(style);
            // Alert close buttons
            // Alert and Toast close buttons - More specific handling
            document.addEventListener('click', function (e) {
                const closeBtn = e.target.closest('.alert-close');
                if (closeBtn) {
                    const alert = closeBtn.closest('.alert');
                    const toast = closeBtn.closest('.toast');

                    if (alert) {
                        alert.remove();
                    } else if (toast) {
                        toast.remove();
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                showToast('Success!', '{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showToast('Error!', '{{ session('error') }}', 'danger');
            @endif

            @if (session('warning'))
                showToast('Warning!', '{{ session('warning') }}', 'warning');
            @endif

            @if (session('info'))
                showToast('Info', '{{ session('info') }}', 'info');
            @endif

            @if ($errors->any())
                showToast('Validation Error', 'Please fix the highlighted errors.', 'danger');
            @endif
    });
    </script>
</body>

</html>