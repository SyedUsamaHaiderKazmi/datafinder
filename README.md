
<p align="center">
<img src="https://suhk.me/assets/images/datafinder-full-logo-lg.png" width="400">
</p>
<p align="center">
An easy, configurable & modular laravel package for <a href="https://datatables.net/">Datatables</a>
<br><br>

<img src="https://img.shields.io/scrutinizer/quality/g/SyedUsamaHaiderKazmi/datafinder">

<img src="https://img.shields.io/scrutinizer/build/g/SyedUsamaHaiderKazmi/datafinder">

<img src="https://img.shields.io/github/license/SyedUsamaHaiderKazmi/datafinder.svg">
<a href="https://sheetjs.com/">
    <img src="https://img.shields.io/badge/Powered_by-SheetJS-blue?logo=javascript" alt="Powered by SheetJS">
</a>
</p>

## _Introduction_

**DataFinder** is an innovative and highly efficient Laravel package designed to implement advanced search, filtering, and data retrieval functionalities in your applications. It bridges the gap between complex backend queries and dynamic front-end tables, providing developers with a streamlined solution for handling large datasets seamlessly.

The package integrates effortlessly with **[DataTables](https://datatables.net/)** on the frontend while leveraging Laravel's robust **Eloquent Query Builder** on the backend. Its scalable architecture ensures high performance, making it suitable for systems with millions of records.

#### **_Key Features_**

🔍 **Dynamic Multi-Table Search**

- Search seamlessly across multiple database tables using **dynamic JOINs**, retrieving relevant data efficiently.  

🔎 **Advanced Multi-Filter Search**

- Apply multiple filters at once, each supporting multiple values, enabling highly refined search results.  
- Combine **filter-based search** with **text-based search**, working across single or multiple tables dynamically.  

⚡ **Flexible Table Configurations**

- Fetch data from any table and define relationships with **efficient JOIN operations**.  
- Supports dynamic configurations for **searchable and filterable columns** in a single file per module.  

🚀 **Easy Module Setup**

- Configure columns, filters, table configuration, database table and their joins, data to display and custom row actions with a single configuration file per module, making setup intuitive and reusable.  

🎯 **Custom Row Actions**

- Add interactive buttons for specific actions within the table, ensuring seamless user interaction.  

📈 **Optimized for Performance & Scalability**

- Designed to handle **millions of records** while maintaining fast and efficient search performance.


#### **_Why Use DataFinder?_**
DataFinder simplifies the integration of **[DataTables](https://datatables.net/)** with Laravel by reducing the complexity of repetitive configurations. Its modular approach empowers developers to:

- Dynamically fetch, filter, and display data from the backend.
- Maintain scalability and performance while handling large datasets.
- Enhance user experience with responsive and interactive tables.
- Minimize development overhead with reusable and centralized configuration files.


<hr>
<center>
    <h1>Documentation</h1>
    <a href="#installation-guide">Installation Guide</a>
    |
    <a href="documentation/Usage.md">Usage</a>
    |
    <a href="documentation/ConfigurationFileBreakdown.md">Configuration File Breakdown</a>
</center>

## _Installation Guide_

Follow these steps to integrate the DataFinder package smoothly into your Laravel project.


#### _Step 1: Install the Package_

Run the following command to install the package via Composer:

```bash
composer require suhk/datafinder-laravel
```

###### ✅ **Tested Compatibility:**

> - **PHP:** 7.3 – 8.4
> - **Laravel:** 5.8 – 11
 

#### _Step 2: Add Service Provider_

The package's service provider is auto-loaded upon installation. However, if it is not loaded, manually add the following entry to the `providers` array in your `config/app.php` file:

```php
SUHK\DataFinder\App\Providers\MainServiceProvider::class,
```

#### _Step 3: Setup package:_

```bash
php artisan datafinder:setup
```

- Loads route & views to autoload
- Publishes required assets for package to public directory
- Publishes the [_Sample Configuration_](src/config/filter_configurations.php) file to following directory structure:
    - **`app/Helpers/DataFinder/sample_configuration.php`** 
        - This is the [_Sample Configuration_](src/config/filter_configurations.php) file used to configure the `datafinder` package per module. It contains default settings and structure that can be customized as needed for your application.
        - [_See file breakdown_ ](documentation/ConfigurationFileBreakdown.md)


#### _Step 4: Add Required CDNs_


For **DataFinder** to work properly, your project must include the following CDNs in your views:

>- **[Bootstrap](https://getbootstrap.com/)**
>- **[jQuery](https://jquery.com/)**
>- **[DataTables](https://cdn.datatables.net/)**
>   - Datatable & Button Extension CDNs [Mendatory]
>- **[Select2](https://select2.org/)**

These dependencies are **not included** in the package to:  

>1. **Avoid copyright claims** – We ensure compliance by not bundling third-party assets. Instead, we reference their official websites.
>2. **Maintain flexibility** – Including these libraries would require constant updates with each new release. By relying on your project’s existing dependencies, **DataFinder** remains compatible across multiple Laravel and PHP versions.  

Make sure to include the required CDNs in your project to ensure **DataFinder** works seamlessly. 

#### _Step 5: Yield DataFinder Scripts_
To ensure DataFinder functions correctly across different pages in your Laravel application, you need to reserve a section in your main layout for injecting page-specific DataFinder scripts using Laravel Blade's `@yield` directive with a section name `datafinder-scripts`.

Place the following line just before the closing `</body>` tag in your main layout which will look like this:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Laravel App</title>
    <!-- Global CSS and scripts -->
</head>
<body>

    <!-- Page content will be injected here -->
    @yield('content')

    <!-- Page-specific DataFinder scripts will be injected here -->
    @yield('datafinder-scripts')

</body>
</html>
```
###### Why This Matters:
- Allows child views to inject their own DataFinder scripts only when needed.
- Keeps your layout clean and avoids loading unnecessary scripts globally.
- Supports better separation of concerns and modular code structure.


#### _Step 6: Read Usage & Configuration File Breakdown_


For comprehensive guidance on how to use this package, please refer to the [_Usage Instructions_](documentation/Usage.md). 

If you need a breakdown of the configuration file to understnad what options and features this package offer and how, see the [_Configuration File Breakdown_ ](documentation/ConfigurationFileBreakdown.md).

---

## _Credits:_

This project depends on the following open-source libraries, which are **not bundled** in the package (except **SheetJS CE**, which is included via CDN). All other libraries are expected to be added via CDN by the end user:

- **[Bootstrap](https://getbootstrap.com/)**
- **[jQuery](https://jquery.com/)**
- **[DataTables](https://datatables.net/)**
- **[Select2](https://select2.org/)**
- **[SheetJS CE](https://docs.sheetjs.com/)**
    - CDN used: `https://cdn.sheetjs.com/xlsx-latest/package/xlsx.mjs`
    - Licensed under the [Apache License 2.0](http://www.apache.org/licenses/LICENSE-2.0)
    - © 2012–present SheetJS LLC
