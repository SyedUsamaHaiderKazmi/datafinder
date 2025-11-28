# DataFinder v2.0 - Usage Guide

This guide covers all the ways to use DataFinder in your Laravel application.

---

## Table of Contents

1. [Installation](#installation)
2. [Basic Usage (Blade)](#basic-usage-blade)
3. [Multiple DataTables on Same Page](#multiple-datatables-on-same-page)
4. [Dynamic Content / AJAX Loading](#dynamic-content--ajax-loading)
5. [JavaScript API](#javascript-api)
6. [Laravel Facade (Backend)](#laravel-facade-backend)
7. [Event Handling](#event-handling)
8. [Custom Controllers](#custom-controllers)
9. [Migration from v1.x](#migration-from-v1x)

---

## Installation

### Step 1: Install via Composer

```bash
composer require suhk/datafinder-laravel
```

### Step 2: Publish Assets

```bash
php artisan datafinder:setup
```

Or manually:

```bash
php artisan vendor:publish --tag=datafinder-assets
```

### Step 3: Include Required Dependencies

Add these to your layout file (before `</body>`):

```html
<!-- Required: jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Required: DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.min.js"></script>

<!-- Required: DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.min.js"></script>

<!-- Optional: Select2 (for multi-select filters) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Optional: Bootstrap (for styling) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataFinder Styles -->
@yield('df-styles')
```

And at the end of your body:

```html
<!-- DataFinder Scripts -->
@yield('df-scripts')
@stack('df-scripts')
```

---

## Basic Usage (Blade)

### The Simplest Way

Just include the container component with your config file name:

```blade
{{-- In your Blade view --}}
@include('datafinder::container', ['config_file_name' => 'users'])
```

This will:
- ✅ Render filters automatically
- ✅ Render the DataTable
- ✅ Initialize everything
- ✅ Handle all AJAX requests

### With Dynamic Filter Values

Pass PHP variables to populate filter dropdowns:

```blade
@include('datafinder::container', [
    'config_file_name' => 'users',
    'phpVariables' => [
        'roles' => $roles,           // From controller: Role::pluck('name', 'id')
        'departments' => $departments,
        'statuses' => ['active' => 'Active', 'inactive' => 'Inactive']
    ]
])
```

In your config file, reference these:

```php
// app/Helpers/DataFinder/users/main.php
'filters' => [
    [
        'name' => 'role_id',
        'label' => 'Role',
        'type' => 'select',
        'value_type' => 'PHP_VARIABLE',  // Use passed variable
        'value' => 'roles',               // Key from phpVariables
        // ...
    ]
]
```

---

## Multiple DataTables on Same Page

Now you can have **unlimited DataTables** on the same page, each with its own independent filters!

```blade
{{-- Users Table --}}
<div class="card mb-4">
    <div class="card-header">Users</div>
    <div class="card-body">
        @include('datafinder::container', ['config_file_name' => 'users'])
    </div>
</div>

{{-- Orders Table (completely independent!) --}}
<div class="card mb-4">
    <div class="card-header">Orders</div>
    <div class="card-body">
        @include('datafinder::container', ['config_file_name' => 'orders'])
    </div>
</div>

{{-- Products Table --}}
<div class="card">
    <div class="card-header">Products</div>
    <div class="card-body">
        @include('datafinder::container', ['config_file_name' => 'products'])
    </div>
</div>
```

Each table has:
- Its own isolated filters
- Its own error handling
- Its own export functionality
- No interference with other tables

---

## Dynamic Content / AJAX Loading

### Loading DataTable in Modal

```html
<!-- Modal -->
<div class="modal" id="reportModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Report</h5>
            </div>
            <div class="modal-body" id="modal-content">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<button onclick="loadReport()">View Report</button>
```

```javascript
function loadReport() {
    // Load content via AJAX
    fetch('/reports/partial')
        .then(response => response.text())
        .then(html => {
            document.getElementById('modal-content').innerHTML = html;
            
            // Initialize DataFinder AFTER content is in DOM
            DataFinder.init('report-container', {
                configFile: 'reports/main',
                columns: [
                    { title: 'Date', data: 'date' },
                    { title: 'Amount', data: 'amount' },
                    { title: 'Status', data: 'status' }
                ],
                exportable: true
            });
            
            // Show modal
            $('#reportModal').modal('show');
        });
}

// Cleanup when modal closes
$('#reportModal').on('hidden.bs.modal', function() {
    DataFinder.destroy('report-container');
});
```

### Loading via jQuery.html()

```javascript
// Load content
$('#container').html(ajaxResponse);

// Initialize after HTML is inserted
DataFinder.init('my-table-container', {
    configFile: 'module/main',
    columns: columns
});
```

### Tab Content

```javascript
$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
    const target = $(e.target).attr('href');
    
    if (target === '#users-tab' && !DataFinder.has('users-table')) {
        DataFinder.init('users-table', { /* config */ });
    }
});
```

---

## JavaScript API

### Initialize

```javascript
// Basic initialization
DataFinder.init('container-id', {
    configFile: 'users/main',
    columns: [
        { title: 'Name', data: 'name' },
        { title: 'Email', data: 'email' },
        { title: 'Status', data: 'status' }
    ]
});

// Full options
DataFinder.init('container-id', {
    // Required
    configFile: 'users/main',
    columns: [...],
    
    // Pagination
    perPage: 25,
    perPageOptions: [10, 25, 50, 100, 250],
    allowPerPageChange: true,
    
    // Features
    searching: true,
    ordering: true,
    responsive: true,
    
    // Export
    exportable: true,
    exportableByChunk: true,
    chunkSize: 1000,
    
    // Custom routes (optional)
    dataRoute: '/api/custom/data',
    exportRoute: '/api/custom/export',
    
    // Callbacks
    onDataLoaded: (json) => console.log('Data loaded:', json),
    onError: (xhr) => console.error('Error:', xhr),
    onFilterChange: () => console.log('Filters changed')
});
```

### Instance Methods

```javascript
// Get instance
const instance = DataFinder.get('container-id');

// Reload data
instance.reload();
instance.reload(() => console.log('Reloaded!'));

// Export
instance.export('xlsx');
instance.export('csv');
instance.export('xls');

// Filters
instance.setFilter('status', 'active');
instance.setFilter('role', '1').reload();  // Chain methods
instance.clearFilters();
const filters = instance.getFilters();

// Errors
instance.clearErrors();
instance.addError('Custom error message');
const errors = instance.getErrors();

// Data access
const data = instance.getData();           // Get current rows
const params = instance.getParams();       // Get current AJAX params
const dt = instance.getDataTable();        // Get underlying DataTable

// State
instance.isInitialized();  // true/false
instance.isExporting();    // true/false

// Destroy
instance.destroy();
```

### Factory Methods

```javascript
// Check if exists
if (DataFinder.has('my-table')) {
    DataFinder.get('my-table').reload();
}

// Reload specific instance
DataFinder.reload('my-table');

// Reload all instances
DataFinder.reloadAll();

// Destroy specific instance
DataFinder.destroy('my-table');

// Destroy all instances (useful for SPA navigation)
DataFinder.destroyAll();

// Get all instances
const instances = DataFinder.getAll();
Object.keys(instances).forEach(id => {
    console.log(id, instances[id]);
});

// Global configuration
DataFinder.configure({
    debug: true,
    perPage: 25
});

// Get version
console.log(DataFinder.version);  // '2.0.0'
```

---

## Laravel Facade (Backend)

Use DataFinder in your custom controllers!

### Basic Query Building

```php
use SUHK\DataFinder\App\Facades\DataFinder;

class UserController extends Controller
{
    public function index()
    {
        // Get query builder from config
        $query = DataFinder::query('users/main');
        
        // Add custom conditions
        $users = $query
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('users.index', compact('users'));
    }
}
```

### Custom DataTable Endpoint

```php
class CustomDataController extends Controller
{
    public function data(Request $request)
    {
        // Returns DataTable-compatible JSON
        return response()->json(
            DataFinder::paginate('users/main', $request)
        );
    }
}
```

### Search

```php
public function search(Request $request)
{
    $results = DataFinder::search('users/main', $request->q)
        ->limit(10)
        ->get();
    
    return response()->json($results);
}
```

### With Filters

```php
public function filtered(Request $request)
{
    $query = DataFinder::queryWithFilters('users/main', $request->filters);
    
    // Add more conditions
    if ($request->has('department')) {
        $query->where('department_id', $request->department);
    }
    
    return $query->get();
}
```

### Access Configuration

```php
// Get config details
$tableName = DataFinder::getTableName('users/main');
$columns = DataFinder::getColumns('users/main');
$searchable = DataFinder::getSearchableColumns('users/main');
$filters = DataFinder::getFilters('users/main');

// Check features
$hasJoins = DataFinder::hasJoins('users/main');
$hasButtons = DataFinder::hasRowButtons('users/main');
$canExport = DataFinder::isExportable('users/main');

// Validate config exists
if (DataFinder::configExists('users/main')) {
    // ...
}
```

---

## Event Handling

Listen to DataFinder events for custom behavior:

```javascript
const instance = DataFinder.get('my-table');

// Data loaded
instance.on('df:data:loaded', (e) => {
    console.log('Records:', e.detail.total);
    console.log('Filtered:', e.detail.filtered);
});

// Reload
instance.on('df:reload', () => {
    console.log('Table reloaded');
});

// Filter changed
instance.on('df:filter:change', (e) => {
    console.log('Filter changed:', e.detail.filter, e.detail.value);
});

// Filters cleared
instance.on('df:filter:clear', () => {
    console.log('Filters cleared');
});

// Export events
instance.on('df:export:start', (e) => {
    console.log('Export started:', e.detail.format);
});

instance.on('df:export:progress', (e) => {
    console.log('Progress:', e.detail.percentage);
});

instance.on('df:export:complete', (e) => {
    console.log('Export complete:', e.detail.format);
});

instance.on('df:export:error', (e) => {
    console.error('Export failed:', e.detail.error);
});

// Errors
instance.on('df:error', (e) => {
    console.error('Error:', e.detail.error);
    // Send to error tracking service
    Sentry.captureMessage(e.detail.error);
});

// Lifecycle
instance.on('df:init', () => console.log('Initialized'));
instance.on('df:destroy', () => console.log('Destroyed'));
```

---

## Custom Controllers

### Using with Custom Routes

In your config file:

```php
'allow_custom_route' => true,
'custom_data_route' => '/api/users/datatable',
'custom_export_route' => '/api/users/export',
```

In your controller:

```php
use SUHK\DataFinder\App\Facades\DataFinder;

class UserApiController extends Controller
{
    public function datatable(Request $request)
    {
        // Use facade for standard behavior
        $response = DataFinder::paginate('users/main', $request);
        
        // Add custom data
        $response['extra'] = [
            'total_active' => User::where('active', true)->count()
        ];
        
        return response()->json($response);
    }
    
    public function export(Request $request)
    {
        $query = DataFinder::queryWithFilters('users/main', $request->filters);
        
        // Custom export logic
        return Excel::download(
            new UsersExport($query), 
            'users.xlsx'
        );
    }
}
```

### Completely Custom Implementation

```javascript
// In your Blade view
DataFinder.init('custom-table', {
    configFile: 'custom/main',
    columns: [
        { title: 'ID', data: 'id' },
        { title: 'Name', data: 'name' }
    ],
    dataRoute: '/api/my-custom-endpoint',
    exportable: false  // Handle export yourself
});
```

```php
// Your controller
public function myCustomEndpoint(Request $request)
{
    // Your completely custom logic
    $data = MyModel::query()
        ->when($request->filters, function($q) use ($request) {
            // Apply filters
        })
        ->paginate($request->length);
    
    return response()->json([
        'draw' => $request->draw,
        'recordsTotal' => $data->total(),
        'recordsFiltered' => $data->total(),
        'data' => $data->items()
    ]);
}
```

---

## Migration from v1.x

### Breaking Changes

| v1.x | v2.x |
|------|------|
| Global `datatable` variable | `DataFinder.get('id').getDataTable()` |
| Global `filters` object | `instance.getFilters()` |
| `.data-finder-filters` class | `.df-filter` class |
| Auto-init on page load | `DataFinder.init()` on demand |
| Single table per page | Multiple tables supported |

### Update Your Views

**Before (v1.x):**
```blade
@include('datafinder::filters', ['config_file_name' => 'users'])
@include('datafinder::datatable', ['config_file_name' => 'users'])
```

**After (v2.x):**
```blade
@include('datafinder::container', ['config_file_name' => 'users'])
```

### Update Your JavaScript

**Before (v1.x):**
```javascript
// Global variables
datatable.ajax.reload();
setupFilterObject();
```

**After (v2.x):**
```javascript
// Instance-based
DataFinder.get('users-container').reload();
DataFinder.get('users-container').getFilters();
```

### Backward Compatibility

If you need to support old code temporarily, include the legacy wrapper:

```html
<script src="/vendor/datafinder/assets/js/legacy/datafinder-legacy.js"></script>
```

This provides the old global variables but logs deprecation warnings.

---

## Examples

### Dashboard with Multiple Tables

```blade
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Recent Users</div>
            <div class="card-body">
                @include('datafinder::container', ['config_file_name' => 'dashboard/users'])
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Recent Orders</div>
            <div class="card-body">
                @include('datafinder::container', ['config_file_name' => 'dashboard/orders'])
            </div>
        </div>
    </div>
</div>
@endsection
```

### Master-Detail Pattern

```javascript
// When clicking a row in the master table
$('#master-table').on('click', 'tr', function() {
    const userId = $(this).data('id');
    
    // Destroy old detail table if exists
    if (DataFinder.has('detail-table')) {
        DataFinder.destroy('detail-table');
    }
    
    // Load detail content
    $('#detail-container').load(`/users/${userId}/orders`, function() {
        // Initialize detail table
        DataFinder.init('detail-table', {
            configFile: 'user-orders/main',
            columns: orderColumns
        });
    });
});
```

---

## Need Help?

- 📚 [Full Documentation](https://datafinder.suhk.me)
- 🐛 [Report Issues](https://github.com/SyedUsamaHaiderKazmi/datafinder/issues)
- 💬 [Discussions](https://github.com/SyedUsamaHaiderKazmi/datafinder/discussions)

