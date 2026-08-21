<?php

use App\Models\Role;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $adminRole = Role::firstOrCreate(['slug' => 'super-admin'], ['name' => 'Super Admin']);
    $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
});

test('landing page renders', function () {
    $this->get('/')->assertOk()->assertSee('BusGo');
});

test('tickets index page renders', function () {
    $this->get(route('tickets.index'))->assertOk();
});

test('tickets search page renders', function () {
    $asal = Terminal::create([
        'nama_terminal' => 'Terminal A', 'kode_terminal' => 'TRM-A',
        'alamat' => 'Alamat A', 'kota' => 'Jakarta', 'provinsi' => 'DKI', 'status' => true,
    ]);
    $tujuan = Terminal::create([
        'nama_terminal' => 'Terminal B', 'kode_terminal' => 'TRM-B',
        'alamat' => 'Alamat B', 'kota' => 'Surabaya', 'provinsi' => 'Jatim', 'status' => true,
    ]);

    $this->get(route('tickets.search', [
        'terminal_asal_id' => $asal->id,
        'terminal_tujuan_id' => $tujuan->id,
        'tanggal' => now()->addDays(2)->toDateString(),
        'penumpang' => 1,
    ]))->assertOk();
});

test('admin dashboard renders for admin user', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Dashboard');
});

test('admin operator index renders for admin user', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.operators.index'))
        ->assertOk();
});

test('admin booking index renders for admin user', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.bookings.index'))
        ->assertOk();
});

test('admin report booking renders for admin user', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.reports.booking'))
        ->assertOk();
});

test('customer dashboard renders for customer user', function () {
    $customerRole = Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    $customer = User::factory()->create(['role_id' => $customerRole->id]);

    $this->actingAs($customer)
        ->get(route('customer.dashboard'))
        ->assertOk();
});
