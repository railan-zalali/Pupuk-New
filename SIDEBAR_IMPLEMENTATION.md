# Dynamic Sidebar Implementation

## Overview
Sistem sidebar telah diubah menjadi dinamis dan otomatis menyesuaikan dengan hak akses (permissions) pengguna. Sidebar akan menampilkan menu sesuai dengan permission yang dimiliki oleh user yang sedang login.

## Komponen Utama

### 1. Menu Configuration (`config/menu.php`)
File konfigurasi yang mendefinisikan struktur menu sidebar:
- **title**: Nama menu yang ditampilkan
- **icon**: Icon menggunakan Tabler Icons (ti ti-*)
- **route**: Route Laravel untuk menu
- **permission**: Permission yang diperlukan untuk mengakses menu
- **active_routes**: Array pattern route yang membuat menu aktif

### 2. MenuHelper (`app/Helpers/MenuHelper.php`)
Class helper yang mengelola logika menu:
- `getSidebarMenu()`: Mengambil menu yang sesuai dengan permission user
- `userHasPermission()`: Mengecek apakah user memiliki permission tertentu
- `isMenuActive()`: Mengecek apakah menu sedang aktif berdasarkan route

### 3. Dynamic Sidebar (`resources/views/layouts/sidebar.blade.php`)
Template sidebar yang menggunakan MenuHelper untuk generate menu secara dinamis:
- Mengelompokkan menu ke dalam kategori (Main, Admin, System, Reports)
- Mempertahankan styling dan animasi yang ada
- Mendukung submenu (jika diperlukan di masa depan)

## Cara Kerja

1. **User Login**: Sistem mengidentifikasi user dan role/permissions-nya
2. **Menu Generation**: MenuHelper memfilter menu berdasarkan permissions
3. **Grouping**: Menu dikelompokkan berdasarkan kategori
4. **Rendering**: Sidebar menampilkan menu sesuai dengan permissions

## Contoh Permission Mapping

### Admin User
- Memiliki akses ke semua menu (17 items)
- Termasuk: Dashboard, Sales, Products, Users, Roles, Settings, Reports

### Cashier User  
- Akses terbatas (10 items)
- Termasuk: Dashboard, Sales, Customers, Reports
- Tidak dapat akses: Products, Categories, Suppliers, Users, Roles, Settings

## Menambah Menu Baru

1. **Tambahkan ke config/menu.php**:
```php
[
    'title' => 'Menu Baru',
    'icon' => 'ti ti-icon-name',
    'route' => 'new-menu.index',
    'permission' => 'manage-new-feature',
    'active_routes' => ['new-menu.*']
]
```

2. **Pastikan permission ada di database**
3. **Assign permission ke role yang sesuai**

## Keuntungan Implementasi

1. **Otomatis**: Menu menyesuaikan dengan permission tanpa hardcode
2. **Fleksibel**: Mudah menambah/mengubah menu melalui konfigurasi
3. **Aman**: Hanya menampilkan menu yang boleh diakses user
4. **Maintainable**: Logika terpusat di MenuHelper dan config
5. **Scalable**: Mudah dikembangkan untuk fitur yang lebih kompleks

## Testing

Sistem telah ditest dengan:
- **Admin user**: Menampilkan 17 menu items
- **Cashier user**: Menampilkan 10 menu items sesuai permission

Implementasi ini memastikan sidebar otomatis fleksibel mengikuti settingan hak akses.