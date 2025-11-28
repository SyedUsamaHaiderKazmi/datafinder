# DataFinder v2.0 - Quick Start Guide

Get up and running with DataFinder in 5 minutes!

---

## 🚀 Quick Start

### Step 1: Install

```bash
composer require suhk/datafinder-laravel
php artisan datafinder:setup
```

### Step 2: Create Your Config

```bash
php artisan datafinder:add users
```

This creates: `app/Helpers/DataFinder/users/main.php`

### Step 3: Edit Your Config

```php
<?php
// app/Helpers/DataFinder/users/main.php

return [
    // Table ID
    'dom_table_id' => 'users-table',
    
    // Your model
    'model_path' => 'App\Models\User',
    'table_name' => 'users',
    
    // Columns to show
    'table_headers' => [
        [
            'title' => 'Name',
            'data' => 'name',
            'searchable' => true,
            'column_name' => 'name',
            'visibility' => true,
            'exportable' => true,
        ],
        [
            'title' => 'Email',
            'data' => 'email',
            'searchable' => true,
            'column_name' => 'email',
            'visibility' => true,
            'exportable' => true,
        ],
        [
            'title' => 'Created',
            'data' => 'created_at',
            'searchable' => false,
            'visibility' => true,
            'exportable' => true,
        ],
    ],
    
    // Optional: Filters
    'filters' => [],
    
    // Optional: Export
    'exportable' => true,
];
```

### Step 4: Add to Your View

```blade
{{-- resources/views/users/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <h1>Users</h1>
    
    @include('datafinder::container', ['config_file_name' => 'users'])
@endsection
```

### Step 5: Make Sure Layout Has Required Scripts

```blade
{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.min.css">
    
    <!-- DataFinder CSS -->
    @yield('df-styles')
</head>
<body>
    @yield('content')
    
    <!-- jQuery (required) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.min.js"></script>
    
    <!-- DataFinder Scripts -->
    @yield('df-scripts')
    @stack('df-scripts')
</body>
</html>
```

### Done! 🎉

Visit your page and see the DataTable with search, sorting, pagination, and export!

---

## 📋 Common Recipes

### Add Filters

```php
'filters' => [
    [
        'id' => 'status-filter',
        'name' => 'status',
        'label' => 'Status',
        'type' => 'select',
        'value_type' => 'CUSTOM',
        'value' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ],
        'visibility' => true,
        'filterable' => true,
        'column_name' => 'status',
    ],
    [
        'id' => 'date-filter',
        'name' => 'created_at',
        'label' => 'Created Date',
        'type' => 'date',
        'value_type' => 'CUSTOM',
        'value' => null,
        'visibility' => true,
        'filterable' => true,
        'column_name' => 'created_at',
        'conditional_operator' => '>=',
    ],
]
```

### Add Row Buttons

```php
'row_has_buttons' => true,
'table_row_buttons' => [
    [
        'label' => 'Edit',
        'class' => 'btn btn-sm btn-primary',
        'icon' => 'fas fa-edit',
        'tooltip' => 'Edit User',
        'route' => [
            'name' => 'users.edit',
            'params' => [
                ['param_key' => 'user', 'data_key' => 'id']
            ],
            'additional_params' => []
        ],
    ],
    [
        'label' => 'Delete',
        'class' => 'btn btn-sm btn-danger',
        'icon' => 'fas fa-trash',
        'tooltip' => 'Delete User',
        'route' => [
            'name' => 'users.destroy',
            'params' => [
                ['param_key' => 'user', 'data_key' => 'id']
            ],
            'additional_params' => []
        ],
    ],
]
```

### Add Joins

```php
'table_has_joins' => true,
'joins' => [
    [
        'type' => 'leftJoin',
        'by' => 'TABLE',
        'using' => [
            'name' => 'departments',
            'options' => [
                'on' => [
                    ['type' => 'on', 'left_side' => 'users.department_id', 'right_side' => 'departments.id']
                ],
                'selective_columns' => true,
                'select' => [
                    ['type' => 'DEFAULT', 'column_name' => 'name', 'alias' => 'department_name']
                ]
            ]
        ]
    ]
]
```

---

## 🎮 JavaScript Control

```javascript
// Reload table
DataFinder.get('df-container-users-table').reload();

// Set filter and reload
DataFinder.get('df-container-users-table')
    .setFilter('status', 'active')
    .reload();

// Export
DataFinder.get('df-container-users-table').export('xlsx');

// Listen to events
DataFinder.get('df-container-users-table').on('df:data:loaded', (e) => {
    console.log('Total records:', e.detail.total);
});
```

---

## 🔧 Backend Usage

```php
use SUHK\DataFinder\App\Facades\DataFinder;

// Get query
$query = DataFinder::query('users/main');
$activeUsers = $query->where('status', 'active')->get();

// Search
$results = DataFinder::search('users/main', 'john')->get();

// Full DataTable response
public function data(Request $request)
{
    return response()->json(
        DataFinder::paginate('users/main', $request)
    );
}
```

---

## ❓ Need More?

See the full [Usage Guide](./Usage.md) for:
- Multiple tables on same page
- Dynamic/AJAX loading
- Custom controllers
- Event handling
- Migration from v1.x

