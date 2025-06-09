Schema::create('categories', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->text('description')->nullable();
$table->timestamps();
});

Schema::create('products', function (Blueprint $table) {
$table->id();
$table->foreignId('category_id')->constrained();
$table->string('name');
$table->string('code')->unique();
$table->text('description')->nullable();
$table->string('image_path')->nullable();
$table->integer('purchase_price');
$table->integer('selling_price');
$table->integer('stock');
$table->integer('min_stock');
$table->timestamps();
$table->softDeletes();
});

Schema::create('suppliers', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->string('phone');
$table->text('address');
$table->text('description')->nullable();
$table->timestamps();
$table->softDeletes();
});
Schema::create('purchases', function (Blueprint $table) {
$table->id();
$table->string('invoice_number')->unique();
$table->foreignId('supplier_id')->constrained();
$table->foreignId('user_id')->constrained();
$table->dateTime('date');
$table->integer('total_amount');
$table->enum('status', ['pending', 'partially_received', 'received'])->default('pending');
$table->text('notes')->nullable();
$table->timestamps();
$table->softDeletes();
});
Schema::create('purchase_details', function (Blueprint $table) {
$table->id();
$table->foreignId('purchase_id')->constrained()->onDelete('cascade');
$table->foreignId('product_id')->constrained();
$table->integer('quantity');
$table->integer('received_quantity')->default(0);
$table->integer('purchase_price')->default(0);
$table->integer('subtotal')->default(0);
$table->timestamps();
});
Schema::create('stock_movements', function (Blueprint $table) {
$table->id();
$table->foreignId('product_id')->constrained();
$table->string('type'); // in/out
$table->integer('quantity');
$table->string('movement_type')->default('in')->comment('in or out');
$table->integer('before_stock');
$table->integer('after_stock');
$table->string('reference_type'); // purchase/sale
$table->unsignedBigInteger('reference_id');
$table->text('notes')->nullable();
$table->timestamps();
});
Schema::create('sales', function (Blueprint $table) {
$table->id();
$table->string('invoice_number')->unique();
$table->foreignId('user_id')->constrained();
$table->foreignId('customer_id')->nullable();
$table->dateTime('date');
$table->integer('total_amount')->default(0);
$table->integer('discount')->default(0);
$table->integer('paid_amount')->default(0);
$table->integer('down_payment')->default(0);
$table->integer('change_amount')->default(0);
$table->enum('payment_method', ['cash', 'transfer', 'credit']);
$table->string('vehicle_type')->nullable();
$table->string('vehicle_number')->nullable();
$table->string('payment_status')->default('paid');
$table->enum('status', ['draft', 'completed', 'cancelled'])->default('completed');
$table->integer('remaining_amount')->default(0);
$table->timestamp('due_date')->nullable();
$table->text('notes')->nullable();
$table->timestamps();
$table->softDeletes();
});
Schema::create('customers', function (Blueprint $table) {
$table->id();
$table->string('nik')->unique();
$table->string('nama');
$table->text('alamat')->nullable();
$table->string('desa_id');
$table->string('kecamatan_id');
$table->string('kabupaten_id');
$table->string('provinsi_id');
$table->string('desa_nama');
$table->string('kecamatan_nama');
$table->string('kabupaten_nama');
$table->string('provinsi_nama');
$table->timestamps();
$table->softDeletes();
});
Schema::create('roles', function (Blueprint $table) {
$table->id();
$table->string('name')->unique();
$table->string('slug')->unique();
$table->text('description')->nullable();
$table->timestamps();
});
Schema::create('permissions', function (Blueprint $table) {
$table->id();
$table->string('name')->unique();
$table->string('slug')->unique();
$table->text('description')->nullable();
$table->timestamps();
});
Schema::create('permission_role', function (Blueprint $table) {
$table->foreignId('permission_id')->constrained()->onDelete('cascade');
$table->foreignId('role_id')->constrained()->onDelete('cascade');
$table->primary(['permission_id', 'role_id']);
});
Schema::create('role_user', function (Blueprint $table) {
$table->foreignId('role_id')->constrained()->onDelete('cascade');
$table->foreignId('user_id')->constrained()->onDelete('cascade');
$table->primary(['role_id', 'user_id']);
});
Schema::create('purchase_receipts', function (Blueprint $table) {
$table->id();
$table->foreignId('purchase_id')->constrained()->onDelete('cascade');
$table->foreignId('user_id')->constrained();
$table->date('receipt_date');
$table->string('receipt_number');
$table->string('receipt_file')->nullable();
$table->text('notes')->nullable();
$table->timestamps();
$table->softDeletes();
});
Schema::create('purchase_receipt_details', function (Blueprint $table) {
$table->id();
$table->foreignId('purchase_receipt_id')->constrained()->onDelete('cascade');
$table->foreignId('purchase_detail_id')->constrained();
$table->foreignId('product_id')->constrained();
$table->integer('received_quantity')->default(0);
$table->integer('base_quantity')->default(0);
$table->timestamps();
});
Schema::table('products', function (Blueprint $table) {
$table->foreignId('supplier_id')
->nullable()
->constrained()
->onDelete('set null');
});
Schema::create('product_supplier', function (Blueprint $table) {
$table->id();
$table->foreignId('product_id')->constrained()->onDelete('cascade');
$table->foreignId('supplier_id')->constrained()->onDelete('cascade');
// $table->decimal('purchase_price', 15, 2)->default(0);
$table->integer('purchase_price')->default(0);
$table->timestamps();

// Mencegah duplikasi produk-supplier
$table->unique(['product_id', 'supplier_id']);
});
Schema::create('unit_of_measures', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->string('abbreviation')->nullable();
$table->boolean('is_base_unit')->default(false);
$table->timestamps();
});
Schema::create('product_units', function (Blueprint $table) {
$table->id();
$table->foreignId('product_id')->constrained()->onDelete('cascade');
$table->foreignId('unit_id')->constrained('unit_of_measures')->onDelete('cascade');
$table->integer('conversion_factor')->default(1);
$table->integer('purchase_price')->default(0);
$table->integer('selling_price')->default(0);
$table->date('expire_date')->nullable();
$table->boolean('is_default')->default(false);
$table->timestamps();
$table->unique(['product_id', 'unit_id']);
});
{
Schema::create('sale_details', function (Blueprint $table) {
$table->id();
$table->foreignId('sale_id')->constrained()->onDelete('cascade');
$table->foreignId('product_id')->constrained();
$table->unsignedBigInteger('product_unit_id')->nullable();
$table->unsignedBigInteger('unit_id')->nullable();
$table->integer('quantity');
$table->integer('base_quantity')->comment('Quantity in base unit for stock calculation');
$table->integer('price');
$table->integer('subtotal');
$table->timestamps();

$table->foreign('product_unit_id')->references('id')->on('product_units')->onDelete('set null');
$table->foreign('unit_id')->references('id')->on('unit_of_measures')->onDelete('set null');
});
Schema::table('purchase_details', function (Blueprint $table) {
$table->unsignedBigInteger('unit_id')->after('product_id')->nullable()->comment('Unit of measure for the product');

$table->integer('conversion_factor')->default(0);
$table->integer('base_quantity')->default(0)->comment('Quantity converted to base unit');
$table->foreign('unit_id')->references('id')->on('unit_of_measures');
});
if (!Schema::hasColumn('products', 'supplier_id')) {
Schema::table('products', function (Blueprint $table) {
$table->foreignId('supplier_id')->nullable()->after('category_id')->constrained('suppliers');
$table->date('expire_date')->nullable()->after('min_stock');
});
}
