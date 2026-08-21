# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

-   **BusGo Bus Ticket Booking**: Aplikasi booking tiket bus lengkap (fitur baru):
    -   Modul Master Data: Operator, Bus, Kursi (termasuk generate kursi otomatis), Terminal, Rute, Jadwal — lengkap CRUD + RBAC per menu.
    -   Alur pembelian publik: pencarian tiket per rute/tanggal, pemilihan kursi interaktif, isi data penumpang, dan pembuatan booking.
    -   Pembayaran: integrasi Midtrans Snap (server key kosong → fallback konfirmasi manual admin) + webhook notifikasi `/payment/notification`.
    -   E-tiket digital: halaman tiket + download PDF (dompdf) dengan QR code per kode booking.
    -   Area pelanggan: dashboard, daftar booking & tiket, pembatalan booking (maks 1 jam sebelum berangkat).
    -   Area admin: dashboard statistik (ApexCharts), kelola booking, pembayaran, pelanggan, dan laporan (booking/revenue/travel) dengan ekspor Excel + cetak PDF.
    -   Anti double-booking: kunci baris + transaksi DB, kursi valid hanya untuk jadwal bersangkutan, booking pending kedaluwarsa otomatis setelah 2 jam (`bookings:expire`).
    -   Proteksi IDOR pada booking/tiket (hanya pemilik atau admin).
    -   Seeder data demo: operator, terminal, bus+kursi, rute, jadwal, booking, pembayaran; pengguna demo (`superadmin@busticket.test`, `admin@busticket.test`, `customer@busticket.test`, password `password`).
    -   Branding "BusGo" di halaman auth, layout publik baru (`layouts/publicLayout`), dan landing page.
    -   Test Pest baru: `BookingFlowTest` (alur booking, double-booking, IDOR, tiket/PDF) dan `PublicPagesSmokeTest`.

### Changed

-   **Redirect pasca-login/registrasi** berbasis peran (admin → `/admin/dashboard`, lainnya → `/customer/dashboard`) di `AuthController` dan `bootstrap/app.php`.
-   **RBAC**: menu disesuaikan dengan modul BusGo; `check.permission` memetakan rute admin (bookings, payments, customers, reports, schedules.generate-seats, seats.generate, settings.clear-cache).
-   **Produk lama dihapus**: tabel `products`, controller/service/repository/contract/view produk, dan rute `products.*` dihapus; `CheckPermission` tidak lagi memetakan aksi produk.
-   Route infrastruktur (user, role, menu, permission, settings, activity-log, impersonate, system) dipindah ke URL `/admin/*` dengan nama route tidak ber-prefix (tetap `user.*`, `role.*`, dst).
-   `verticalMenu` meng-guard deklarasi fungsi helper (`isMenuItemActive`, `hasActiveChild`) agar tidak bentrok saat view dirender berulang dalam satu request.

### Fixed

-   Dashboard admin kini portabel lintas DB (tanpa `CONCAT`), sehingga tetap berfungsi pada SQLite (tes) maupun MySQL.
-   Test `BugfixRegressionTest` ekspor laporan disesuaikan dengan skema RBAC admin baru (user berperan `admin` dengan izin read).

## [1.4.0] - 2026-02-23

### Added

-   **Enhanced Code Generator**: `make:feature` now automatically generates full Blade views (Index, Create, Edit, Show) with Sneat-compatible templates.
-   **Namespace/Directory Support**: `make:feature` now supports subdirectories (e.g., `php artisan make:feature Admin/Post`), automatically handling folders and namespaces.

### Changed

-   **Profile Validation**: Refactored profile update logic to use a dedicated `ProfileRequest`.
-   **Development Guide**: Updated with instructions for the new generator capabilities.

### Fixed

-   **Profile Password**: Fixed issue where password was incorrectly required during profile info updates.

## [1.3.0] - 2026-02-05

### Added

-   **User Avatar**: Implemented support for uploading and displaying user avatars across profile and management views.
-   **System Health**: Added system health and maintenance status endpoints (`SystemController`).
-   **Enhanced RBAC**: Added new 'Visitor' role for restricted access.
-   **Audit Documentation**: Completed comprehensive technical audit for PHP 7.4 compatibility.

### Changed

-   **Framework Upgrade**: Upgraded to Laravel 12.x and PHP 8.2+ compatibility.
-   **Activity Log**: Refined log display and statistics.

## [1.2.0] - 2026-01-20

### Added

-   **Global Settings**: Comprehensive management system for website configuration (Logo, App Name, etc.) with dedicated UI.
-   **User Profile**: Dedicated page for users to manage their profile and password.
-   **Alerts**: Added new "warning" type to the Alert system.

### Changed

-   **Activity Logging**: Refactored logging logic for better maintainability and reliability.
-   **Authorization**: improved redirect logic for admin vs non-admin users and set default application timezone.
-   **UI/UX**: Redesigned Roles & Permissions interface.

### Fixed

-   **Sidebar**: Fixed active menu highlighting issues when navigating sub-menus.

## [1.1.0] - 2026-01-12

### Documentation

-   **Translation**: Translated all documentation (README, Guides) to English.
-   **Sponsorship**: Added sponsorship and license information.

### Removed

-   **Sponsor**: Removed Ko-fi link integration.

## [1.0.0] - 2026-01-04

### Added

-   **Base Template**: Finalized base Laravel template with RBAC (Role-Based Access Control).
-   **Product Management**: Module for managing products with CRUD operations.
-   **API Docs**: Integrated Swagger/OpenAPI for API documentation with Sanctum authentication.
-   **Seeders**: Multi-role seeders and default menu configurations.

### Fixed

-   **Auth**: Refined exception handler to correctly handle unauthenticated redirects for web routes.
-   **Validation**: Updated menu request validation logic.

### Documentation

-   Created step-by-step Development Guide.
-   Updated Creator information and project documentation.
