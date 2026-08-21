<?php

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Operator;
use App\Models\Role;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['slug' => 'super-admin'], ['name' => 'Super Admin']);
    $customerRole = Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    $this->customer = User::factory()->create(['role_id' => $customerRole->id]);
    $this->otherCustomer = User::factory()->create(['role_id' => $customerRole->id]);
});

/**
 * Helper: buat jadwal lengkap dengan 4 kursi (1A, 1B, 1C, 1D).
 */
function makeSchedule(): Schedule
{
    $operator = Operator::create([
        'nama_operator' => 'PO Test', 'kode_operator' => 'OP-TEST',
        'alamat' => 'Jl. Test 1', 'telepon' => '0812345', 'email' => 'op@test.test', 'status' => true,
    ]);

    $asal = Terminal::create([
        'nama_terminal' => 'Terminal A', 'kode_terminal' => 'TRM-A',
        'alamat' => 'Alamat A', 'kota' => 'Jakarta', 'provinsi' => 'DKI', 'status' => true,
    ]);
    $tujuan = Terminal::create([
        'nama_terminal' => 'Terminal B', 'kode_terminal' => 'TRM-B',
        'alamat' => 'Alamat B', 'kota' => 'Surabaya', 'provinsi' => 'Jatim', 'status' => true,
    ]);

    $route = Route::create([
        'terminal_asal_id' => $asal->id, 'terminal_tujuan_id' => $tujuan->id,
        'jarak' => 700, 'estimasi_durasi' => 600, 'status' => true,
    ]);

    $bus = Bus::create([
        'operator_id' => $operator->id, 'nomor_polisi' => 'B 1234 TT', 'kode_bus' => 'BUS-TEST',
        'nama_bus' => 'Bus Test', 'kelas' => 'Executive', 'kapasitas' => 4,
        'fasilitas' => 'AC', 'status' => true,
    ]);

    foreach (['1A', '1B', '1C', '1D'] as $nomor) {
        Seat::create([
            'bus_id' => $bus->id, 'nomor_kursi' => $nomor,
            'posisi' => in_array($nomor, ['1A', '1B']) ? 'kiri' : 'kanan', 'status' => 'aktif',
        ]);
    }

    return Schedule::create([
        'bus_id' => $bus->id, 'route_id' => $route->id,
        'tanggal_keberangkatan' => now()->addDays(3)->toDateString(),
        'jam_keberangkatan' => '08:00', 'jam_tiba' => '18:00',
        'harga' => 100000, 'status' => 'aktif',
    ])->load('bus.seats');
}

test('guest cannot access seat selection page', function () {
    $schedule = makeSchedule();

    $this->get(route('tickets.seats', $schedule->id))
        ->assertRedirect(route('login'));
});

test('customer can book seats and booking is created as pending', function () {
    $schedule = makeSchedule();
    $seat = $schedule->bus->seats->first();

    $this->actingAs($this->customer)
        ->post(route('tickets.store', $schedule->id), [
            'seats' => [$seat->id],
            'passengers' => [
                ['nama_penumpang' => 'Budi Santoso', 'nik' => '3201010101010001', 'no_hp' => '081234567890', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '1990-01-01'],
            ],
        ])
        ->assertRedirect(route('booking.show', Booking::first()->id));

    $this->assertDatabaseHas('bookings', [
        'user_id' => $this->customer->id,
        'status_booking' => 'pending',
        'status_pembayaran' => 'unpaid',
    ]);

    $this->assertDatabaseHas('booking_seats', [
        'seat_id' => $seat->id,
    ]);
});

test('double booking prevention: second customer cannot book the same seat', function () {
    $schedule = makeSchedule();
    $seat = $schedule->bus->seats->first();

    // Customer pertama berhasil booking kursi 1A
    $this->actingAs($this->customer)
        ->post(route('tickets.store', $schedule->id), [
            'seats' => [$seat->id],
            'passengers' => [['nama_penumpang' => 'Budi']],
        ])
        ->assertRedirect();

    // Customer kedua mencoba kursi yang sama -> harus gagal
    $this->actingAs($this->otherCustomer)
        ->from(route('tickets.seats', $schedule->id))
        ->post(route('tickets.store', $schedule->id), [
            'seats' => [$seat->id],
            'passengers' => [['nama_penumpang' => 'Andi']],
        ])
        ->assertRedirect(route('tickets.seats', $schedule->id))
        ->assertSessionHas('error');

    // Hanya satu booking aktif untuk kursi tersebut
    $activeBookings = Booking::whereHas('seats', fn ($q) => $q->where('seat_id', $seat->id))
        ->where('status_booking', 'pending')
        ->count();

    expect($activeBookings)->toBe(1);
});

test('customer cannot view another customer booking (IDOR protection)', function () {
    $schedule = makeSchedule();
    $seat = $schedule->bus->seats->first();

    $this->actingAs($this->customer)
        ->post(route('tickets.store', $schedule->id), [
            'seats' => [$seat->id],
            'passengers' => [['nama_penumpang' => 'Budi']],
        ]);

    $booking = Booking::first();

    // Customer lain tidak boleh akses detail booking milik customer pertama
    $this->actingAs($this->otherCustomer)
        ->get(route('booking.show', $booking->id))
        ->assertForbidden();

    // Pemiliknya boleh
    $this->actingAs($this->customer)
        ->get(route('booking.show', $booking->id))
        ->assertOk()
        ->assertSee($booking->kode_booking);
});

test('ticket page only available after payment is paid', function () {
    $schedule = makeSchedule();
    $seat = $schedule->bus->seats->first();

    $this->actingAs($this->customer)
        ->post(route('tickets.store', $schedule->id), [
            'seats' => [$seat->id],
            'passengers' => [['nama_penumpang' => 'Budi']],
        ]);

    $booking = Booking::first();

    // Belum bayar -> akses tiket ditolak
    $this->actingAs($this->customer)
        ->get(route('booking.ticket', $booking->id))
        ->assertForbidden();

    // Setelah dibayar -> tiket bisa diakses
    $booking->update(['status_booking' => 'confirmed', 'status_pembayaran' => 'paid', 'paid_at' => now()]);

    $this->actingAs($this->customer)
        ->get(route('booking.ticket', $booking->id))
        ->assertOk()
        ->assertSee($booking->kode_booking);
});

test('paid booking can be downloaded as pdf ticket with qr code', function () {
    $schedule = makeSchedule();
    $seat = $schedule->bus->seats->first();

    $this->actingAs($this->customer)
        ->post(route('tickets.store', $schedule->id), [
            'seats' => [$seat->id],
            'passengers' => [['nama_penumpang' => 'Budi']],
        ]);

    $booking = Booking::first();
    $booking->update(['status_booking' => 'confirmed', 'status_pembayaran' => 'paid', 'paid_at' => now()]);

    $this->actingAs($this->customer)
        ->get(route('booking.ticket.pdf', $booking->id))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');
});
