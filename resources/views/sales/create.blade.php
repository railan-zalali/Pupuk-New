<x-app-layout>
    @push('scripts')
        @vite(['resources/js/sales-pos.js'])
    @endpush

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 font-sans" 
         x-data="posSystem(
            {{ json_encode($products) }}, 
            {{ json_encode($customers) }}, 
            '{{ $invoiceNumber }}', 
            {{ $draft ? json_encode($draft) : 'null' }}
         )">
        
        <!-- Navbar / Top Bar -->
        <header class="sticky top-0 z-20 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm h-16">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 h-full">
                <div class="flex items-center justify-between h-full gap-4">
                    <!-- Brand / Back -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 transition-colors" title="Kembali">
                            <i class="ti ti-arrow-left text-xl"></i>
                        </a>
                        <div class="hidden sm:block">
                            <h1 class="text-lg font-bold text-gray-900 dark:text-white leading-none">POS System</h1>
                            <span class="text-xs text-gray-500 font-mono" x-text="invoiceNumber"></span>
                        </div>
                    </div>

                    <!-- Center Search (Global) -->
                    <div class="flex-1 max-w-xl relative">
                        <div class="relative">
                            <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" 
                                   x-ref="searchInput"
                                   x-model="searchQuery"
                                   @input.debounce.300ms="handleSearch()"
                                   @keydown.down.prevent="activeSearchIndex = Math.min(activeSearchIndex + 1, searchResults.length - 1)"
                                   @keydown.up.prevent="activeSearchIndex = Math.max(activeSearchIndex - 1, 0)"
                                   @keydown.enter.prevent="if(activeSearchIndex >= 0) selectSearchResult(searchResults[activeSearchIndex])"
                                   @keydown.escape="showSearchResults = false"
                                   placeholder="Cari produk cepat (Alt + A)..." 
                                   class="w-full pl-10 pr-4 py-2 bg-gray-100 dark:bg-gray-700 border-none rounded-full focus:ring-2 focus:ring-emerald-500 text-sm transition-all">
                            
                            <!-- Dropdown Results -->
                             <div x-show="showSearchResults && searchResults.length > 0" 
                                 @click.away="showSearchResults = false"
                                 x-transition.opacity.duration.200ms
                                 class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50">
                                <ul class="max-h-[60vh] overflow-y-auto py-1">
                                    <template x-for="(product, index) in searchResults" :key="product.id">
                                        <li @click="selectSearchResult(product)"
                                            @mouseenter="activeSearchIndex = index"
                                            :class="{'bg-emerald-50 dark:bg-emerald-900/30': activeSearchIndex === index}"
                                            class="px-4 py-3 cursor-pointer border-b border-gray-50 dark:border-gray-700/50 last:border-0 flex justify-between items-center group">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white" x-text="product.name"></div>
                                                <div class="text-xs text-gray-500 flex gap-2">
                                                    <span x-text="product.code"></span>
                                                    <span :class="product.stock > 0 ? 'text-emerald-600' : 'text-red-500'" x-text="product.stock > 0 ? 'Stok: ' + product.stock : 'Habis'"></span>
                                                </div>
                                            </div>
                                            <div class="font-bold text-emerald-600" x-text="formatCurrency(product.selling_price)"></div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Right Actions -->
                    <div class="flex items-center gap-3">
                        <button type="button" @click="showProductModal = true" class="hidden md:flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-full text-sm font-medium transition-colors">
                            <i class="ti ti-layout-grid"></i>
                            Katalog Produk
                        </button>
                        <button @click="processTransaction()" 
                                :disabled="!isValid || isProcessing"
                                class="flex items-center gap-2 px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full font-medium shadow-md shadow-emerald-600/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-text="isProcessing ? 'Memproses...' : 'Bayar'"></span>
                            <i class="ti ti-check" x-show="!isProcessing"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Layout -->
        <main class="max-w-full mx-auto p-4 sm:p-6 lg:p-8">
            <form x-ref="form" action="{{ route('sales.store') }}" method="POST" class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-8rem)]">
                @csrf
                <input type="hidden" name="invoice_number" :value="invoiceNumber">
                <input type="hidden" name="date" :value="date">

                <!-- Left Panel: Cart (Flexible height) -->
                <div class="flex-1 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
                    <!-- Cart Header -->
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h2 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                            Keranjang
                        </h2>
                        <span class="text-xs font-mono text-gray-400" x-text="cart.length + ' Items'"></span>
                    </div>

                    <!-- Cart List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar">
                        <template x-for="(item, index) in cart" :key="index">
                            <div class="group flex items-center gap-4 p-3 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-emerald-200 dark:hover:border-emerald-800 hover:bg-emerald-50/30 dark:hover:bg-emerald-900/10 transition-all bg-white dark:bg-gray-800/50">
                                <!-- Inputs -->
                                <input type="hidden" :name="'product_id[' + index + ']'" :value="item.product_id">
                                <input type="hidden" :name="'unit_id[' + index + ']'" :value="item.unit_id">
                                <input type="hidden" :name="'quantity[' + index + ']'" :value="item.quantity">
                                <input type="hidden" :name="'selling_price[' + index + ']'" :value="item.price">

                                <!-- Number -->
                                <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-500" x-text="index + 1"></div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900 dark:text-white truncate" x-text="item.name"></h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <select @change="updateItemUnit(item, $event.target.value)"
                                                class="text-xs py-0.5 pl-2 pr-6 border-gray-200 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 focus:ring-0 focus:border-emerald-500">
                                            <template x-for="unit in item.units" :key="unit.id">
                                                <option :value="unit.id" :selected="unit.id == item.unit_id" x-text="unit.unit.name"></option>
                                            </template>
                                        </select>
                                        <span class="text-xs text-gray-400">@ <span x-text="formatCurrency(item.price)"></span></span>
                                    </div>
                                </div>

                                <!-- Qty -->
                                <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                                    <button type="button" @click="updateItemQuantity(item, -1)" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-white dark:hover:bg-gray-600 text-gray-500 transition-colors">
                                        <i class="ti ti-minus text-xs"></i>
                                    </button>
                                    <input type="number" x-model="item.quantity" @input="updateItemSubtotal(item)" class="w-10 text-center bg-transparent border-none p-0 text-sm font-semibold focus:ring-0">
                                    <button type="button" @click="updateItemQuantity(item, 1)" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-white dark:hover:bg-gray-600 text-gray-500 transition-colors">
                                        <i class="ti ti-plus text-xs"></i>
                                    </button>
                                </div>

                                <!-- Subtotal -->
                                <div class="text-right min-w-[100px]">
                                    <div class="font-bold text-gray-900 dark:text-white" x-text="formatCurrency(item.subtotal)"></div>
                                </div>

                                <!-- Remove -->
                                <button type="button" @click="removeItem(index)" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-center p-8 opacity-60">
                            <img src="https://coresg-normal.trae.ai/api/ide/v1/text_to_image?prompt=Empty%20shopping%20cart%20illustration%20minimalist%20flat%20design%20gray%20colors&image_size=square" alt="Empty Cart" class="w-48 h-48 mb-4 opacity-50 grayscale">
                            <p class="text-gray-500 font-medium">Keranjang masih kosong</p>
                            <button type="button" @click="showProductModal = true" class="mt-2 text-emerald-600 hover:underline text-sm">Buka Katalog Produk</button>
                        </div>
                    </div>

                    <!-- Cart Footer -->
                    <div class="p-6 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-200 dark:border-gray-700 space-y-4">
                        <textarea name="notes" x-model="notes" rows="2" placeholder="Catatan tambahan..." class="w-full bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 resize-none"></textarea>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Total Akhir</span>
                            <span class="text-3xl font-bold text-gray-900 dark:text-white" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Sidebar (Fixed width) -->
                <div class="w-full lg:w-96 flex flex-col gap-6">
                    
                    <!-- Customer Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                        <div class="flex justify-between items-center mb-4">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Pelanggan</label>
                            <button type="button" @click="openCustomerModal()" class="text-xs font-medium text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                                <i class="ti ti-plus"></i> Baru
                            </button>
                        </div>
                        
                        <div class="relative">
                            <select name="customer_id" x-model="customer" class="w-full pl-4 pr-10 py-3 bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-sm font-medium focus:ring-emerald-500 focus:border-emerald-500 appearance-none">
                                <option value="">-- Pilih Pelanggan --</option>
                                <template x-for="cust in customers" :key="cust.id">
                                    <option :value="cust.id" x-text="cust.nama"></option>
                                </template>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="ti ti-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        <!-- New Customer Tag -->
                        <div x-show="!customer && newCustomerName" class="mt-3 flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <div class="w-8 h-8 rounded-full bg-emerald-200 text-emerald-700 flex items-center justify-center text-xs font-bold">
                                    <i class="ti ti-user"></i>
                                </div>
                                <div class="truncate">
                                    <div class="text-xs text-emerald-600 font-medium">Pelanggan Baru</div>
                                    <div class="text-sm font-bold text-emerald-800 dark:text-emerald-300 truncate" x-text="newCustomerName"></div>
                                    <input type="hidden" name="new_customer_name" x-model="newCustomerName">
                                </div>
                            </div>
                            <button type="button" @click="newCustomerName = ''" class="text-gray-400 hover:text-red-500 p-1">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>

                        <!-- Date -->
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                             <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2 block">Tanggal</label>
                             <input type="date" name="date" x-model="date" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl text-sm focus:ring-emerald-500">
                        </div>

                         <!-- Vehicle Accordion -->
                         <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <div @click="showVehicleInfo = !showVehicleInfo" class="flex justify-between items-center cursor-pointer group">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide group-hover:text-emerald-600 transition-colors">Info Kendaraan</label>
                                <i class="ti ti-chevron-down text-gray-400 transition-transform duration-200" :class="{'rotate-180': showVehicleInfo}"></i>
                            </div>
                            <div x-show="showVehicleInfo" x-collapse class="mt-3 space-y-3">
                                <select name="vehicle_type" x-model="vehicleType" class="w-full rounded-xl border-gray-200 text-sm py-2 bg-gray-50">
                                    <option value="Truk">Truk</option>
                                    <option value="Pickup">Pickup</option>
                                </select>
                                <input type="text" name="vehicle_number" x-model="vehicleNumber" placeholder="Plat Nomor (KB ...)" class="w-full rounded-xl border-gray-200 text-sm py-2 bg-gray-50 uppercase">
                            </div>
                         </div>
                    </div>

                    <!-- Payment Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 flex-1 flex flex-col">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Metode Pembayaran</label>
                        
                        <div class="grid grid-cols-3 gap-2 mb-6">
                            <button type="button" @click="paymentMethod = 'cash'" :class="paymentMethod === 'cash' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'" class="py-2.5 rounded-xl text-sm font-medium transition-all">Tunai</button>
                            <button type="button" @click="paymentMethod = 'transfer'" :class="paymentMethod === 'transfer' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'" class="py-2.5 rounded-xl text-sm font-medium transition-all">Transfer</button>
                            <button type="button" @click="paymentMethod = 'credit'" :class="paymentMethod === 'credit' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200'" class="py-2.5 rounded-xl text-sm font-medium transition-all">Kredit</button>
                        </div>
                        <input type="hidden" name="payment_method" :value="paymentMethod">

                        <div class="space-y-1 mb-6">
                             <label class="text-xs text-gray-500" x-text="paymentMethod === 'credit' ? 'Uang Muka (DP)' : 'Jumlah Diterima'"></label>
                             <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                <input type="number" name="paid_amount" x-model="paidAmount" class="w-full pl-10 pr-4 py-3 text-xl font-bold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 rounded-xl border-gray-200 dark:border-gray-600 focus:ring-emerald-500 focus:border-emerald-500" placeholder="0">
                             </div>
                        </div>

                        <!-- Quick Amounts -->
                        <div class="grid grid-cols-3 gap-2 mb-6">
                            <button type="button" @click="setQuickAmount('exact')" class="py-2 px-1 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-600 hover:border-emerald-500 hover:text-emerald-600 transition-colors">Uang Pas</button>
                            <button type="button" @click="setQuickAmount(50000)" class="py-2 px-1 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-600 hover:border-emerald-500 hover:text-emerald-600 transition-colors">50.000</button>
                            <button type="button" @click="setQuickAmount(100000)" class="py-2 px-1 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-600 hover:border-emerald-500 hover:text-emerald-600 transition-colors">100.000</button>
                        </div>

                        <div class="mt-auto pt-4 border-t border-dashed border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500" x-text="paymentMethod === 'credit' ? 'Sisa Tagihan' : 'Kembalian'"></span>
                                <span class="text-xl font-bold" :class="paymentMethod === 'credit' ? 'text-red-500' : 'text-emerald-600'" x-text="formatCurrency(paymentMethod === 'credit' ? remaining : change)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>

        <!-- SLIDE-OVER PRODUCT CATALOG (Modern Drawer) -->
        <div x-show="showProductModal" 
             style="display: none;"
             class="relative z-50" 
             aria-labelledby="slide-over-title" 
             role="dialog" 
             aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="showProductModal"
                 x-transition:enter="ease-in-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-500"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity"
                 @click="showProductModal = false"></div>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        
                        <!-- Panel -->
                        <div x-show="showProductModal"
                             x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:enter-start="translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="translate-x-full"
                             class="pointer-events-auto w-screen max-w-5xl">
                            
                            <div class="flex h-full flex-col bg-white dark:bg-gray-800 shadow-2xl">
                                <!-- Drawer Header -->
                                <div class="px-4 py-6 sm:px-6 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 z-10">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white" id="slide-over-title">Katalog Produk</h2>
                                            <p class="text-sm text-gray-500 mt-1">Pilih produk untuk ditambahkan ke keranjang.</p>
                                        </div>
                                        <div class="ml-3 flex h-7 items-center">
                                            <button type="button" @click="showProductModal = false" class="rounded-full p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                                <i class="ti ti-x text-2xl"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Integrated Search & Filter -->
                                    <div class="mt-6 flex gap-4">
                                        <div class="relative flex-1">
                                            <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                            <input type="text" x-model="productModalSearch" x-ref="productSearch" placeholder="Cari nama, kode..." class="w-full pl-11 pr-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-xl border-none focus:ring-2 focus:ring-emerald-500 transition-shadow">
                                        </div>
                                        <!-- View Toggle -->
                                        <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-xl shrink-0">
                                            <button @click="productModalView = 'grid'" :class="{'bg-white dark:bg-gray-600 text-emerald-600 shadow-sm': productModalView === 'grid', 'text-gray-400': productModalView !== 'grid'}" class="p-2.5 rounded-lg transition-all"><i class="ti ti-layout-grid"></i></button>
                                            <button @click="productModalView = 'list'" :class="{'bg-white dark:bg-gray-600 text-emerald-600 shadow-sm': productModalView === 'list', 'text-gray-400': productModalView !== 'list'}" class="p-2.5 rounded-lg transition-all"><i class="ti ti-list"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Drawer Body: Sidebar Layout -->
                                <div class="flex flex-1 overflow-hidden relative">
                                    <!-- Categories Sidebar (Left) -->
                                    <div class="w-64 bg-gray-50 dark:bg-gray-800/50 border-r border-gray-100 dark:border-gray-700 overflow-y-auto p-4 hidden md:block">
                                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-2">Kategori</h3>
                                        <div class="space-y-1">
                                            <button @click="productModalCategory = ''" 
                                                    :class="productModalCategory === '' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 font-bold' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                                    class="w-full text-left px-4 py-3 rounded-xl text-sm transition-colors flex justify-between items-center">
                                                <span>Semua</span>
                                                <i class="ti ti-chevron-right text-xs opacity-50"></i>
                                            </button>
                                            <template x-for="cat in categories" :key="cat.id">
                                                <button @click="productModalCategory = cat.id" 
                                                        :class="productModalCategory === cat.id ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 font-bold' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                                        class="w-full text-left px-4 py-3 rounded-xl text-sm transition-colors flex justify-between items-center">
                                                    <span x-text="cat.name"></span>
                                                    <span x-show="productModalCategory === cat.id" class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Mobile Category Dropdown (Visible only on small screens) -->
                                    <div class="md:hidden absolute top-0 left-0 right-0 z-10 p-2 bg-white/90 backdrop-blur-sm border-b border-gray-100">
                                        <select x-model="productModalCategory" class="w-full rounded-lg border-gray-300 text-sm">
                                            <option value="">Semua Kategori</option>
                                            <template x-for="cat in categories" :key="cat.id">
                                                <option :value="cat.id" x-text="cat.name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <!-- Product Grid/List Area -->
                                    <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-white dark:bg-gray-900 relative custom-scrollbar">
                                         <!-- Empty State -->
                                         <div x-show="filteredModalProducts.length === 0" class="h-full flex flex-col items-center justify-center text-center opacity-60">
                                            <i class="ti ti-search-off text-4xl text-gray-300 mb-4"></i>
                                            <p class="text-gray-500">Tidak ada produk yang cocok</p>
                                            <button @click="productModalSearch = ''; productModalCategory = ''" class="text-emerald-600 font-bold mt-2 hover:underline">Reset Filter</button>
                                        </div>

                                        <!-- GRID View -->
                                        <div x-show="productModalView === 'grid'" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 pb-20">
                                            <template x-for="product in filteredModalProducts" :key="product.id">
                                                <div @click="toggleModalProduct(product)" 
                                                     class="group relative bg-white dark:bg-gray-800 rounded-2xl p-4 cursor-pointer transition-all border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-lg hover:-translate-y-1 h-full flex flex-col"
                                                     :class="isModalProductSelected(product.id) ? 'ring-2 ring-emerald-500 bg-emerald-50/20' : ''">
                                                    
                                                    <!-- Checkmark Overlay -->
                                                    <div x-show="isModalProductSelected(product.id)" 
                                                         x-transition:enter="scale-in-center"
                                                         class="absolute top-3 right-3 z-10 bg-emerald-500 text-white w-6 h-6 rounded-full flex items-center justify-center shadow-md">
                                                        <i class="ti ti-check text-xs font-bold"></i>
                                                    </div>

                                                    <!-- Icon -->
                                                    <div class="aspect-square rounded-xl bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center mb-3 group-hover:bg-emerald-50 dark:group-hover:bg-emerald-900/10 transition-colors">
                                                        <i class="ti ti-package text-3xl text-gray-300 dark:text-gray-600 group-hover:text-emerald-500 transition-colors"></i>
                                                    </div>

                                                    <!-- Details -->
                                                    <div class="flex-1 flex flex-col">
                                                        <div class="text-[10px] uppercase font-bold text-gray-400 mb-1" x-text="product.category ? product.category.name : 'Umum'"></div>
                                                        <h3 class="font-bold text-gray-900 dark:text-white leading-tight mb-1 line-clamp-2 text-sm" x-text="product.name"></h3>
                                                        <div class="text-xs text-gray-500 font-mono mb-3" x-text="product.code"></div>
                                                        
                                                        <div class="mt-auto flex items-end justify-between border-t border-gray-50 dark:border-gray-700 pt-3">
                                                            <div>
                                                                <div class="text-[10px] text-gray-400">Harga</div>
                                                                <div class="font-bold text-emerald-600 text-sm" x-text="formatCurrency(product.selling_price)"></div>
                                                            </div>
                                                            <div :class="product.stock > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'" class="px-2 py-1 rounded-lg text-[10px] font-bold">
                                                                <span x-text="product.stock > 0 ? product.stock : 'Habis'"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- LIST View -->
                                        <div x-show="productModalView === 'list'" class="pb-20">
                                            <div class="space-y-2">
                                                <template x-for="product in filteredModalProducts" :key="product.id">
                                                    <div @click="toggleModalProduct(product)" 
                                                         class="flex items-center gap-4 p-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-emerald-300 cursor-pointer transition-all group"
                                                         :class="isModalProductSelected(product.id) ? 'bg-emerald-50/50 border-emerald-500' : ''">
                                                        
                                                        <!-- Checkbox visual -->
                                                        <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-colors flex-shrink-0"
                                                             :class="isModalProductSelected(product.id) ? 'bg-emerald-500 border-emerald-500' : 'border-gray-300 bg-white group-hover:border-emerald-400'">
                                                            <i x-show="isModalProductSelected(product.id)" class="ti ti-check text-white text-[10px] font-bold"></i>
                                                        </div>

                                                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-gray-400">
                                                            <i class="ti ti-package"></i>
                                                        </div>

                                                        <div class="flex-1 min-w-0 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-center">
                                                            <div class="col-span-1 md:col-span-2">
                                                                <h4 class="font-bold text-gray-900 dark:text-white truncate" x-text="product.name"></h4>
                                                                <div class="text-xs text-gray-500 font-mono" x-text="product.code"></div>
                                                            </div>
                                                            <div class="text-sm">
                                                                <span class="inline-block px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-xs font-medium text-gray-600" x-text="product.category ? product.category.name : '-'"></span>
                                                            </div>
                                                            <div class="text-right">
                                                                <div class="font-bold text-emerald-600" x-text="formatCurrency(product.selling_price)"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Drawer Footer -->
                                <div class="flex-shrink-0 border-t border-gray-200 dark:border-gray-700 px-4 py-6 sm:px-6 bg-gray-50 dark:bg-gray-800">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg" x-text="selectedModalProducts.length"></div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-gray-900 dark:text-white">Produk Dipilih</span>
                                                <button @click="selectedModalProducts = []" x-show="selectedModalProducts.length > 0" class="text-xs text-red-500 hover:underline text-left">Hapus Semua</button>
                                            </div>
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2" @click="showProductModal = false">Batal</button>
                                            <button type="button" class="inline-flex justify-center rounded-xl border border-transparent bg-emerald-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed" 
                                                    @click="addSelectedProducts()"
                                                    :disabled="selectedModalProducts.length === 0">
                                                Tambahkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NEW CUSTOMER MODAL (Compact Card Style) -->
        <div x-show="showCustomerModal" 
             style="display: none;"
             class="relative z-50" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            
            <div x-show="showCustomerModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"
                 @click="showCustomerModal = false"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div x-show="showCustomerModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm border-t-4 border-emerald-500">
                        
                        <div class="px-4 pb-4 pt-5 sm:p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="ti ti-user-plus text-emerald-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">Pelanggan Baru</h3>
                                    <p class="text-xs text-gray-500">Input nama untuk transaksi cepat.</p>
                                </div>
                            </div>
                            
                            <div class="mt-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Nama Lengkap</label>
                                <input type="text" x-model="tempCustomer.name" x-ref="newCustomerNameInput"
                                       @keydown.enter.prevent="saveNewCustomer()"
                                       class="block w-full rounded-xl border-0 py-3 pl-4 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-emerald-600 sm:text-sm sm:leading-6 bg-gray-50"
                                       placeholder="Nama Pelanggan">
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/30 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                            <button type="button" @click="saveNewCustomer()" class="inline-flex w-full justify-center rounded-xl bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:ml-3 sm:w-auto">Simpan</button>
                            <button type="button" @click="showCustomerModal = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>