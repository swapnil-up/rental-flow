<?php

namespace App\Admin\Bookings\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Domain\Bookings\Models\Booking;
use Symfony\Component\HttpFoundation\Response;

class GenerateLeasePdfAction
{
    public function execute(Booking $booking): Response
    {
        $booking->load('property', 'tenant');

        $data = [
            'booking' => $booking,
            'property' => $booking->property,
            'tenant' => $booking->tenant,
        ];

        $pdf = Pdf::loadView('pdf.lease', $data);

        return $pdf->download("lease-{$booking->id}.pdf");
    }
}
