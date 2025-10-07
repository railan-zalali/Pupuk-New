<?php

return [
    'sidebar' => [
        [
            'title' => 'Dashboard',
            'icon' => 'ti ti-dashboard',
            'route' => 'dashboard',
            'permission' => 'access-dashboard',
            'active_routes' => ['dashboard']
        ],
        [
            'title' => 'Penjualan',
            'icon' => 'ti ti-shopping-cart',
            'route' => 'sales.index',
            'permission' => 'manage-sales',
            'active_routes' => ['sales.*']
        ],
        [
            'title' => 'Draft Penjualan',
            'icon' => 'ti ti-file-text',
            'route' => 'sales.drafts',
            'permission' => 'manage-sales',
            'active_routes' => ['sales.drafts']
        ],
        [
            'title' => 'Pembelian',
            'icon' => 'ti ti-shopping-bag',
            'route' => 'purchases.index',
            'permission' => 'manage-purchases',
            'active_routes' => ['purchases.*']
        ],
        [
            'title' => 'Produk',
            'icon' => 'ti ti-package',
            'route' => 'products.index',
            'permission' => 'manage-products',
            'active_routes' => ['products.*']
        ],
        [
            'title' => 'Pelanggan',
            'icon' => 'ti ti-user-heart',
            'route' => 'customers.index',
            'permission' => 'manage-customers',
            'active_routes' => ['customers.*']
        ],
        [
            'title' => 'Kategori',
            'icon' => 'ti ti-tags',
            'route' => 'categories.index',
            'permission' => 'manage-categories',
            'active_routes' => ['categories.*']
        ],
        [
            'title' => 'Pemasok',
            'icon' => 'ti ti-truck-delivery',
            'route' => 'suppliers.index',
            'permission' => 'manage-suppliers',
            'active_routes' => ['suppliers.*']
        ],
        [
            'title' => 'Pengguna',
            'icon' => 'ti ti-users',
            'route' => 'users.index',
            'permission' => 'manage-users',
            'active_routes' => ['users.*']
        ],
        [
            'title' => 'Hak Akses',
            'icon' => 'ti ti-shield-lock',
            'route' => 'roles.index',
            'permission' => 'manage-roles',
            'active_routes' => ['roles.*']
        ],
        [
            'title' => 'Pengaturan',
            'icon' => 'ti ti-settings',
            'route' => 'settings.index',
            'permission' => 'manage-users', // Admin only
            'active_routes' => ['settings.*']
        ],
        
        // Reports - Individual items for better flexibility
        [
            'title' => 'Semua Laporan',
            'icon' => 'ti ti-report',
            'route' => 'reports.index',
            'permission' => 'access-reports',
            'active_routes' => ['reports.index']
        ],
        [
            'title' => 'Laporan Penjualan',
            'icon' => 'ti ti-chart-bar',
            'route' => 'reports.sales',
            'permission' => 'access-reports',
            'active_routes' => ['reports.sales']
        ],
        [
            'title' => 'Laporan Stok',
            'icon' => 'ti ti-chart-area',
            'route' => 'reports.stock',
            'permission' => 'access-reports',
            'active_routes' => ['reports.stock']
        ],
        [
            'title' => 'Stok Masuk',
            'icon' => 'ti ti-arrow-bar-to-down',
            'route' => 'reports.stock-in',
            'permission' => 'access-reports',
            'active_routes' => ['reports.stock-in']
        ],
        [
            'title' => 'Stok Keluar',
            'icon' => 'ti ti-arrow-bar-to-up',
            'route' => 'reports.stock-out',
            'permission' => 'access-reports',
            'active_routes' => ['reports.stock-out']
        ],
        [
            'title' => 'Laporan Laba Rugi',
            'icon' => 'ti ti-chart-pie',
            'route' => 'reports.profit-loss',
            'permission' => 'access-reports',
            'active_routes' => ['reports.profit-loss']
        ]
    ]
];