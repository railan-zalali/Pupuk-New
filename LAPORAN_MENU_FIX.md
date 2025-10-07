# Fix Menu Laporan Tidak Muncul

## Masalah
Menu laporan tidak muncul untuk user admin, cashier, dan pimpinan meskipun mereka memiliki permission yang sesuai.

## Penyebab
Terdapat ketidakcocokan antara nama permission di konfigurasi menu (`config/menu.php`) dengan format permission di database:

- **Di config/menu.php**: menggunakan format kebab-case seperti `access-reports`, `manage-sales`
- **Di database**: menggunakan format Title Case seperti `Access Reports`, `Manage Sales`
- **Sistem permission checking**: menggunakan field `slug` yang berformat kebab-case

## Solusi
1. **Mengidentifikasi format yang benar**: Permission checking menggunakan field `slug` dari tabel permissions
2. **Update config/menu.php**: Mengubah semua permission dari format Title Case ke format slug (kebab-case)

### Perubahan yang dilakukan:
```php
// Sebelum
'permission' => 'Access Reports'

// Sesudah  
'permission' => 'access-reports'
```

### Daftar permission yang diperbaiki:
- `Access Dashboard` → `access-dashboard`
- `Manage Sales` → `manage-sales`
- `Manage Products` → `manage-products`
- `Manage Categories` → `manage-categories`
- `Manage Suppliers` → `manage-suppliers`
- `Manage Customers` → `manage-customers`
- `Manage Purchases` → `manage-purchases`
- `Access Reports` → `access-reports`
- `Manage Users` → `manage-users`
- `Manage Roles` → `manage-roles`

## Hasil
Setelah perbaikan:
- **Admin**: Dapat melihat 17 menu items termasuk 6 menu laporan
- **Cashier**: Dapat melihat menu laporan sesuai dengan permission yang dimiliki
- **Semua role**: Menu laporan sekarang muncul dengan benar

## Testing
```bash
php artisan tinker --execute="
use App\Helpers\MenuHelper;
\$admin = App\Models\User::where('email', 'admin@example.com')->first();
auth()->login(\$admin);
\$menuItems = MenuHelper::getSidebarMenu();
echo 'Menu items: ' . count(\$menuItems);
"
```

Menu laporan yang tersedia:
1. Semua Laporan
2. Laporan Penjualan  
3. Laporan Stok
4. Stok Masuk
5. Stok Keluar
6. Laporan Laba Rugi