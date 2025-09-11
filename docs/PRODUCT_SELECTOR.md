# Product Selector Component

Komponen Product Selector adalah solusi UI/UX modern untuk pemilihan produk dalam modul sales yang menggantikan dropdown sederhana dengan interface yang lebih intuitif dan powerful.

## Fitur Utama

### 1. Tampilan Visual Modern
- **Grid View**: Tampilan katalog produk dengan gambar dan informasi lengkap
- **List View**: Tampilan kompak untuk navigasi cepat
- **Responsive Design**: Optimal di semua ukuran layar (desktop, tablet, mobile)
- **Dark Mode Support**: Mendukung tema gelap dan terang

### 2. Pencarian Real-time
- **Autocomplete**: Saran produk saat mengetik
- **Multi-field Search**: Pencarian berdasarkan nama, kode, kategori, dan supplier
- **Debounced Search**: Optimasi performa dengan delay pencarian
- **Search Highlighting**: Highlight hasil pencarian

### 3. Sistem Kategori
- **Category Navigation**: Filter cepat berdasarkan kategori
- **Category Count**: Jumlah produk per kategori
- **Hierarchical Categories**: Mendukung kategori bertingkat

### 4. Filter Advanced
- **Stock Filter**: Tersedia, Menipis, Habis
- **Price Range**: Filter berdasarkan rentang harga
- **Supplier Filter**: Filter berdasarkan supplier
- **Custom Filters**: Dapat diperluas sesuai kebutuhan

### 5. Animasi & Interaksi
- **Smooth Transitions**: Animasi halus saat navigasi
- **Hover Effects**: Efek visual saat hover
- **Loading States**: Indikator loading yang informatif
- **Selection Feedback**: Visual feedback saat memilih produk

### 6. Indikator Visual
- **Stock Status**: Badge status stok (Tersedia, Menipis, Habis)
- **Selection Indicator**: Tanda produk yang dipilih
- **Quantity Badge**: Jumlah produk yang dipilih
- **Price Highlighting**: Highlight harga produk

## Struktur File

```
resources/views/components/
├── product-selector.blade.php          # Komponen utama
└── product-selector-modal.blade.php    # Modal popup

public/
├── js/product-selector.js              # Logic Alpine.js
└── css/product-selector.css             # Styling CSS

app/Http/Controllers/Api/
└── ProductController.php                # API endpoints

routes/
└── api.php                             # Route API
```

## API Endpoints

### 1. Get Products
```
GET /api/products
```
**Parameters:**
- `search` - Kata kunci pencarian
- `category_id` - Filter kategori
- `supplier_id` - Filter supplier
- `stock_filter` - Filter stok (available, low, out)
- `min_price` - Harga minimum
- `max_price` - Harga maksimum
- `sort_by` - Urutan (name, price, stock, category, created_at)
- `sort_order` - Arah urutan (asc, desc)
- `per_page` - Jumlah per halaman (max 50)

### 2. Search Products
```
GET /api/products/search?q={query}&limit={limit}
```
**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Pupuk NPK",
      "code": "NPK001",
      "selling_price": 50000,
      "stock": 100,
      "category": {
        "id": 1,
        "name": "Pupuk"
      },
      "image_path": "https://example.com/image.jpg",
      "stock_status": {
        "status": "available",
        "label": "Tersedia",
        "class": "bg-green-100 text-green-800"
      },
      "formatted_price": "Rp 50.000"
    }
  ]
}
```

### 3. Get Categories
```
GET /api/products/categories
```

### 4. Get Popular Products
```
GET /api/products/popular?limit={limit}
```

### 5. Get Product Details
```
GET /api/products/{id}
```

## Penggunaan

### 1. Dalam Blade Template
```blade
<!-- Include modal component -->
<x-product-selector-modal />

<!-- Button to open selector -->
<button onclick="openProductSelector()" class="btn-primary">
    Pilih Produk
</button>
```

### 2. JavaScript Integration
```javascript
// Initialize product selector
window.initProductSelector({
    apiEndpoint: '/api/products',
    searchEndpoint: '/api/products/search',
    categoriesEndpoint: '/api/products/categories',
    multiSelect: true,
    showQuantityInput: true,
    defaultView: 'grid'
});

// Open product selector
function openProductSelector() {
    if (window.productSelectorStore) {
        window.productSelectorStore.openModal();
    }
}

// Listen for product selection
window.addEventListener('product-selected', function(event) {
    const selectedProducts = event.detail.products;
    // Handle selected products
});
```

### 3. Konfigurasi
```javascript
const config = {
    apiEndpoint: '/api/products',           // Endpoint utama
    searchEndpoint: '/api/products/search', // Endpoint pencarian
    categoriesEndpoint: '/api/products/categories', // Endpoint kategori
    multiSelect: true,                      // Multi-selection
    showQuantityInput: true,                // Input quantity
    defaultView: 'grid',                    // View default (grid/list)
    perPage: 12,                           // Items per page
    searchDelay: 300,                      // Delay pencarian (ms)
    cacheTimeout: 300000,                  // Cache timeout (ms)
    showPopularProducts: true,             // Tampilkan produk populer
    enableKeyboardNavigation: true         // Navigasi keyboard
};
```

## Customization

### 1. Styling
Edit file `public/css/product-selector.css` untuk mengubah tampilan:

```css
/* Custom product card styling */
.product-card {
    @apply bg-white rounded-lg border shadow-sm;
}

.product-card:hover {
    @apply shadow-lg transform scale-105;
}
```

### 2. Layout
Modifikasi template `resources/views/components/product-selector.blade.php`:

```blade
<!-- Custom product card layout -->
<div class="product-card" @click="selectProduct(product)">
    <img :src="product.image_path || '/images/no-image.png'" 
         :alt="product.name" class="product-image">
    <div class="product-info">
        <h3 class="product-name" x-text="product.name"></h3>
        <p class="product-price" x-text="product.formatted_price"></p>
    </div>
</div>
```

### 3. Behavior
Modifikasi `public/js/product-selector.js` untuk mengubah behavior:

```javascript
// Custom product selection logic
selectProduct(product) {
    if (this.multiSelect) {
        this.toggleProductSelection(product);
    } else {
        this.selectedProducts = [product];
        this.closeModal();
    }
    
    // Custom event
    this.dispatchEvent('product-selected', {
        products: this.selectedProducts
    });
}
```

## Performance Optimization

### 1. Caching
- API responses di-cache selama 5 menit
- Search results di-cache per query
- Categories di-cache selama 1 jam

### 2. Lazy Loading
- Images dimuat secara lazy
- Pagination untuk mengurangi load time
- Debounced search untuk mengurangi API calls

### 3. Database Optimization
- Index pada kolom yang sering dicari
- Eager loading untuk relasi
- Query optimization dengan select specific columns

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Dependencies

- **Alpine.js 3.x** - Reactive framework
- **Tailwind CSS 3.x** - Styling framework
- **Laravel 10.x** - Backend framework
- **jQuery 3.x** - DOM manipulation (existing dependency)

## Troubleshooting

### 1. Modal tidak muncul
```javascript
// Check if Alpine.js is loaded
if (typeof Alpine === 'undefined') {
    console.error('Alpine.js not loaded');
}

// Check if product selector is initialized
if (!window.productSelectorStore) {
    console.error('Product selector not initialized');
}
```

### 2. API tidak merespons
```javascript
// Check API endpoints
fetch('/api/products')
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error('API Error:', error));
```

### 3. Styling tidak muncul
```html
<!-- Ensure CSS is loaded -->
<link href="{{ asset('css/product-selector.css') }}" rel="stylesheet" />
```

## Future Enhancements

1. **Barcode Scanner Integration**
2. **Voice Search**
3. **Product Recommendations**
4. **Bulk Operations**
5. **Export/Import Functionality**
6. **Advanced Analytics**
7. **Mobile App Integration**
8. **Offline Support**

## Contributing

Untuk berkontribusi pada pengembangan komponen ini:

1. Fork repository
2. Buat feature branch
3. Commit changes
4. Push ke branch
5. Create Pull Request

## License

MIT License - see LICENSE file for details.