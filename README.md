
<p align="center">
<img src="https://suhk.me/assets/images/datafinder-full-logo-lg.png" width="400">
</p>
<p align="center">
An easy, configurable & modular laravel package for <a href="https://datatables.net/">Datatables</a>
<br><br>

<img alt="Static Badge" src="https://img.shields.io/badge/Docs-DataFinder-red?style=for-the-badge&link=https%3A%2F%2Fdatafinder.suhk.me%2F&logo=readthedocs&logoColor=white">

<img alt="Static Badge" src="https://img.shields.io/github/v/release/SyedUsamaHaiderKazmi/datafinder?style=for-the-badge&link=https%3A%2F%2Fdatafinder.suhk.me%2F&logo=semanticrelease&logoColor=white">

<img src="https://img.shields.io/scrutinizer/quality/g/SyedUsamaHaiderKazmi/datafinder?style=for-the-badge&logo=scrutinizerci&logoColor=white">

<img src="https://img.shields.io/scrutinizer/build/g/SyedUsamaHaiderKazmi/datafinder?style=for-the-badge&logo=scrutinizerci&logoColor=white">

<a href="https://github.com/SyedUsamaHaiderKazmi/datafinder/tree/master?tab=MIT-1-ov-file">
    <img src="https://img.shields.io/github/license/SyedUsamaHaiderKazmi/datafinder?style=for-the-badge&logo=readthedocs&logoColor=white">
</a>
<a href="https://sheetjs.com/">
    <img src="https://img.shields.io/badge/Powered_by-SheetJS-blue?logo=javascript&style=for-the-badge" alt="Powered by SheetJS">
</a>
</p>

## _Introduction_

**[DataFinder](https://datafinder.suhk.me/)** is an innovative and highly efficient Laravel package that simplifies complex data operations by bringing advanced search, filtering, and retrieval capabilities into a single, developer-friendly solution. It eliminates the need for writing repetitive query logic by bridging backend queries with dynamic, customizable front-end tables.  

Built on top of Laravel’s robust **Eloquent Query Builder** and seamlessly integrated with **[DataTables](https://datatables.net/)**, DataFinder ensures performance and scalability even when working with millions of records. Its modular configuration approach makes it easy to define relationships, filters, conditional queries, exports, and custom row actions & more, all from a configuration structure per module. It allows developers to focus on delivering insights and features rather than wrestling with data pipelines, making it a go-to solution for modern, data-driven applications.


### **_Key Features_**

#### **_Core Features (Developer Essentials)_**
*Everything developers need to build fast, flexible, and reliable data-driven modules.*

🔍 **Dynamic Multi-Table Search**  
Seamlessly query across multiple database tables with **automatic JOINs**, delivering fast and relevant results without extra boilerplate.

🔎 **Advanced Multi-Filter Search**  
Stack multiple filters with multi-value support, combining **filter-based** and **text-based search** across single or multiple tables, giving users ultimate flexibility in refining data.

📊 **Conditional & Aggregate Queries**  
Full support for `where`, `groupBy`, `having`, and aggregate functions makes it easy to build anything from **simple filters** to **complex analytical reports**.

⚡ **Flexible Table Configurations**  
Define models, relationships, searchable columns, and filters inside a **single modular config file per module**, cutting down setup time and ensuring consistency.

🎯 **Custom Row Actions**  
Create interactive row-level actions (like edit, approve, export, etc.) directly in your tables, enabling **seamless workflows** inside your application.

---

#### 🏢 Enterprise Value (Performance & Scale)
*Designed for scalability, maintainability, and enterprise-grade performance.*

📈 **Optimized for Performance & Scale**  
Built to handle **millions of records** efficiently, ensuring fast search and exports even for enterprise-scale datasets.

⚡ **Advanced Data Exporting (CSV, XLSX, XLS)**  
Export small or large datasets — from simple queries to **complex joins, filters, and conditionals**, all from the same modular configuration.

🚀 **Intuitive Module Setup**  
One config = everything. Columns, joins, filters, exports, and row actions can all be defined per module, making integration **faster, cleaner, and scalable**.

📦 **One-Command Setup**  
Install, configure, and refresh with **single Artisan commands**. Perfect for fast onboarding and easy upgrades.

<hr>

### Why DataFinder?

Building **searchable, filterable, and exportable data modules** in modern applications is often repetitive, time-consuming, and inconsistent across projects.  
DataFinder solves this by providing a **plug-and-play, configuration-driven solution** that makes data exploration as simple as writing one config file.

 **For Developers**: No need to reinvent advanced search, joins, and exports. Focus on business logic, not boilerplate.  
 **For Teams**: Standardized, reusable configs mean faster onboarding, less code debt, and predictable results.  
 **For Enterprises**: Built to scale with **millions of records**, efficient exports, and modular architecture for long-term maintainability.  
 **For Investors**: DataFinder reduces engineering overhead, accelerates delivery timelines, and enables applications to monetize faster with enterprise-grade data handling.

<hr>

### **_Documentation_**

Full documentation is available at: **[DataFinder Documentation](https://datafinder.suhk.me)**  

The documentation cover's:

- Introduction
- Installation
- Quick Start Guide
- Configuration Structure  
- Filters, Headers, Buttons, Conditionals & more

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
