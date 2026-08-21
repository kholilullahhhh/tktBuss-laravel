<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketService
{
    public function qrSvg(string $data): string
    {
        return QrCode::format('svg')->size(160)->margin(1)->generate($data);
    }

    /**
     * Data tiket digital yang tampil di halaman tiket & PDF.
     */
    public function ticketData(Booking $booking): array
    {
        $schedule = $booking->schedule;
        $bus = $schedule?->bus;
        $route = $schedule?->route;

        return [
            'kode_booking' => $booking->kode_booking,
            'operator' => $bus?->operator?->nama_operator ?? '-',
            'bus' => $bus?->nama_bus ?? '-',
            'nomor_polisi' => $bus?->nomor_polisi ?? '-',
            'kelas' => $bus?->kelas ?? '-',
            'asal' => $route?->terminalAsal?->nama_terminal ?? '-',
            'tujuan' => $route?->terminalTujuan?->nama_terminal ?? '-',
            'tanggal' => optional($schedule)->tanggal_keberangkatan ? $schedule->tanggal_keberangkatan->format('d-m-Y') : '-',
            'jam_berangkat' => $schedule?->jam_keberangkatan ?? '-',
            'jam_tiba' => $schedule?->jam_tiba ?? '-',
            'kursi' => $booking->seats->map(fn ($s) => $s->seat?->nomor_kursi ?? '-')->implode(', '),
            'total_harga' => $booking->total_harga,
            'status' => strtoupper($booking->status_pembayaran),
        ];
    }

    public function downloadPdf(Booking $booking)
    {
        $ticket = $this->ticketData($booking);
        $qr = $this->qrSvg($booking->kode_booking);
        $pdf = Pdf::loadView('pages.booking.ticket-pdf', compact('booking', 'ticket', 'qr'));

        return $pdf->download('tiket-'.$booking->kode_booking.'.pdf');
    }
}
