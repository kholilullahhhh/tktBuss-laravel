# AGENTS.md

Laravel 12 + PHP 8.2 enterprise starter. Service-Repository pattern, granular RBAC per menu action, audit trail, Sneat Bootstrap 5 admin UI. UI strings and code comments are in Indonesian — keep new UI text consistent with that.

## Commands

- `composer dev` — full dev stack (serve + queue + pail + vite via concurrently). Not `php artisan serve`.
- `composer setup` — fresh install (env, key, migrate, npm, build).
- `composer test` — runs `config:clear` then `php artisan test`. Use `php artisan test tests/Feature/XxxTest.php` for one file.
- `php artisan make:feature FeatureName` or `make:feature Admin/Product` — scaffolds Model-less CRUD boilerplate AND auto-wires: repository binding in `app/Providers/AppServiceProvider.php::register()` and a `Route::resource` in `routes/web.php` guarded by `check.permission:{slug}.index`. It is idempotent (skips existing files/bindings/routes).
- `php artisan db:seed --class=RoleAndMenuSeeder` — required after adding a menu entry; menus render from the `role_menu` pivot, so a new module is invisible until seeded.
- `php artisan l5-swagger:generate` — docs from OpenAPI attributes in controllers; UI at `/api/documentation`.
- `vendor/bin/pint` — code style (Laravel Pint, installed).

## Architecture

- Flow: Controller (thin, calls service) → Service (`app/Services/*`, extends `BaseService`, holds business logic) → Repository (`app/Repositories/*`, extends `BaseRepository`, DB queries only).
- Contracts live in `app/Contracts/Repositories/` and are named WITHOUT an `Interface` suffix (e.g. `UserRepository`), bound to concrete classes manually in `AppServiceProvider::register()`.
- Audit trail: add `App\Traits\LogsActivity` to any model whose CRUD should be logged (before/after snapshots in `activity_logs`). Configure via optional `$logAttributes` / `$logExcept` static props.
- Permissions: `check.permission:{menu-slug}` middleware (`app/Http/Middleware/CheckPermission.php`) maps HTTP verb → CRUD action (`index/show`→read, `create/store`→create, `edit/update`→update, `destroy`→delete, plus a route-name override map). It is **fail-closed**: an unmapped route action aborts 403. The `super-admin` role bypasses everything via `Gate::before` in `AppServiceProvider::boot()`.
- Globally loaded helpers (composer `autoload.files`): `get_setting()`, `Helper` alias (`ViewConfigHelper`), `ResponseHelper::success/error` (JSON envelope). Blade directive `@setting(...)`.
- Exports: `maatwebsite/excel` + `barryvdh/laravel-dompdf` (see `products.export.excel` / `products.export.pdf` routes).

## Adding a new module

1. `php artisan make:feature Name` (optionally `Sub/Name`), which also generates the 4 Blade views under `resources/views/pages/...`.
2. Create the migration + model; use `LogsActivity` and define `$fillable`/`$casts`. Generated view/request stubs assume a single `name` field — replace per feature.
3. Register the menu + role grants in `database/seeders/RoleAndMenuSeeder.php` `$menus` (menu `slug` must equal the route name, e.g. `products.index`) and re-run `db:seed --class=RoleAndMenuSeeder`.
4. Note `FileUploadService` and `window.AlertHandler` (SweetAlert2/Toastr) for uploads and delete confirmations; controllers flash `->with('success', ...)` for toastr.

## Testing

- Pest (not plain PHPUnit). Tests run on in-memory SQLite (`phpunit.xml`); Feature tests use `RefreshDatabase`.
- Bypass permissions in tests by creating the super-admin role: `Role::firstOrCreate(['slug' => 'super-admin'], ...)` + `User::factory()->create(['role_id' => $role->id])` in `beforeEach`.
- Use `firstOrCreate` for master data (roles/menus) to avoid unique-constraint collisions. Existing feature tests (e.g. `tests/Feature/PermissionPestTest.php`, `ProductDeleteTest.php`) are the pattern to copy.

## Frontend

- Sneat Bootstrap 5 template + jQuery. Views extend `layouts/layoutMaster`. Template theme config lives in `config/custom.php`.
- Vite (`vite.config.js`) globs assets from `resources/assets/{js,vendor}` and page JS from `resources/assets/js/*.js`; new asset directories must be added to the input globs there. A custom plugin patches `jkanban.js`/`vfs_fonts` for `window` assignment — don't remove.

## Conventions

- Changelog: Keep a Changelog + SemVer in `CHANGELOG.md`; add notes under `## [Unreleased]` during development.
- Repo-local guides (authoritative for feature workflows): `DEVELOPMENT_GUIDE.md`, `FEATURES_GUIDE.md`, `ACTIVITY_LOG_GUIDE.md`, `ALERT_SYSTEM_GUIDE.md`.