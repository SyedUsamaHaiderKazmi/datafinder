# DataFinder - Laravel Package

## Description 

DataFinder is a powerful Laravel package designed to implement advanced search and filtering functionality in any system. It integrates seamlessly with DataTables on the frontend and leverages Laravel's Eloquent Query Builder on the backend. Key features include:

- Search across multiple tables and their rows within a single module or AJAX request.
- Perform searches with or without using JOINs.
- Fetch data from any table with flexible and efficient JOIN operations.
- Define searchable and filterable columns in a single configuration file for each system module.
- Enable fast and scalable search capabilities.

## Installation Guide

Follow these steps to integrate the DataFinder package smoothly into your Laravel project.

### Step 1: Install the Package

Run the following command to install the package via Composer:

```bash
composer require suhk/datafinder-laravel
```

### Step 2: Add Required CDNs

Add the following CDNs for Bootstrap, jQuery, and DataTables to the `<head>` section of your application:

```html
<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CDN -->
<link href="https://cdn.datatables.net/1.13.0/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.0/js/jquery.dataTables.min.js"></script>
```

### Step 3: Add Service Provider

The package's service provider is auto-loaded upon installation. However, if it is not loaded, manually add the following entry to the `providers` array in your `config/app.php` file:

```php
SUHK\DataFinder\App\Providers\MainServiceProvider::class,
```

## Documentation

Detailed documentation is under development and will be uploaded soon. Thank you for your patience!

---

Feel free to contribute or report issues to enhance the package further.
