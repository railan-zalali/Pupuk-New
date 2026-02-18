# Technical Handoff Specification

## Overview
This document outlines the technical implementation of the new POS (Point of Sale) interface for the Sales module. The redesign focuses on performance, accessibility, and user experience using a client-side first approach for responsiveness.

## Architecture

### Frontend Stack
- **Framework**: Laravel Blade (View Layer)
- **Interactivity**: Alpine.js v3 (via `resources/js/sales-pos.js`)
- **Styling**: Tailwind CSS (Utility-first)
- **Icons**: Tabler Icons

### Data Flow
1.  **Initial Load**: The `SaleController::create` method loads all products with their relationships (`category`, `productUnits.unit`) and customers. This data is passed to the view and injected into the Alpine.js component.
2.  **Client-Side Processing**:
    - **Search**: Performed in-memory on the client side for instant feedback.
    - **Cart Management**: All calculations (subtotal, total, change) happen reactively in Alpine.js.
    - **Validation**: Basic validation (required fields) runs before form submission.
3.  **Submission**: The form is submitted via standard POST request to `sales.store`.

## Key Components

### 1. POS Logic (`resources/js/sales-pos.js`)
This standalone Alpine.js data object (`posSystem`) handles the entire application state.
- **State**: Manages `cart`, `customer`, `payment`, and `search` states.
- **Reactivity**: Uses Alpine's getters for `subtotal`, `total`, `change`, etc.
- **Methods**:
    - `addToCart(product)`: Handles adding items, checking for duplicates (same product + same unit), and default unit selection.
    - `processTransaction()`: Validates and submits the form.

### 2. Blade View (`resources/views/sales/create.blade.php`)
- **Layout**: Uses a 12-column grid system (8 cols for Cart, 4 cols for Info/Payment).
- **Responsive**: Stacks to single column on mobile.
- **Accessibility**:
    - Semantic HTML structure.
    - Keyboard shortcuts (`Alt+A` for Search, `Alt+S` for Save).
    - Focus management for search results.

### 3. Backend Optimizations (`SaleController.php`)
- **Eager Loading**: Modified `create` method to eager load `productUnits.unit`.
  ```php
  $products = Product::with(['category', 'productUnits.unit'])->orderBy('name')->get();
  ```
  *Reason*: Prevents N+1 queries and ensures unit conversion data is available immediately for the JS logic.

## Integration Points

### Product Data Structure
The JS expects product objects to match the Eloquent serialization:
```json
{
  "id": 1,
  "name": "Product Name",
  "code": "SKU123",
  "stock": 100,
  "selling_price": 50000,
  "product_units": [
    {
      "id": 10,
      "unit_id": 1,
      "conversion_factor": 1,
      "is_default": true,
      "selling_price": 50000,
      "unit": { "name": "Pcs" }
    }
  ]
}
```

### Form Submission
The form submits the following arrays:
- `product_id[]`
- `unit_id[]`
- `quantity[]`
- `selling_price[]`

And standard fields:
- `customer_id` / `new_customer_name`
- `date`
- `payment_method`
- `paid_amount`
- `vehicle_type`, `vehicle_number`, `notes`

## Future Improvements / Scalability Notes
- **Server-Side Search**: If the product catalog exceeds ~2,000 items, the initial JSON payload might become too heavy. Consider refactoring the search to use an AJAX endpoint (`/api/products/search`) while keeping the cart logic client-side.
- **Offline Mode**: The current architecture is close to supporting PWA capabilities. Caching the product list in IndexedDB would allow offline transaction recording (synced later).

## Setup Instructions
1.  Ensure `npm run build` or `npm run dev` is running to compile the new JS file.
2.  Verify `product_units` table has data for products to ensure unit selection works.
