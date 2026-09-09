<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use App\Services\MobileMoneyService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function form(Reservation $reservation, MobileMoneyService $service)
    {
        if (! in_array($reservation->statut, ['en_attente', 'confirmee'])) {
            return redirect()->route('home')->with('error', 'Cette réservation n\'est plus modifiable.');
        }

        return view('payment.form', [
            'reservation' => $reservation,
            'modes' => $service::MODES,
        ]);
    }

    public function initier(Request $request, Reservation $reservation, MobileMoneyService $service)
    {
        $validated = $request->validate([
            'telephone' => 'required|string|max:20',
            'mode_paiement' => 'required|in:airtel_money,moov_money',
        ]);

        $result = $service->initierPaiement(
            $validated['telephone'],
            $reservation->montantTotal(),
            $validated['mode_paiement']
        );

        if (! $result['success']) {
            return back()->with('error', 'Le paiement a échoué. Réessayez.');
        }

        $payment = Payment::updateOrCreate(
            ['reservation_id' => $reservation->id],
            [
                'reference_transaction' => $result['reference'],
                'mode_paiement' => $validated['mode_paiement'],
                'montant' => $reservation->montantTotal(),
                'statut' => 'en_attente',
                'telephone' => $validated['telephone'],
            ]
        );

        return view('payment.initier', [
            'payment' => $payment,
            'reservation' => $reservation,
            'message' => $result['message'],
        ]);
    }

    public function confirmer(Reservation $reservation, MobileMoneyService $service, QrCodeService $qr)
    {
        $payment = $reservation->payment;

        if ($payment && $payment->statut === 'en_attente') {
            $confirmation = $service->confirmerPaiement(
                $payment->reference_transaction,
                $payment->telephone
            );

            $payment->update([
                'statut' => 'valide',
                'provider_reference' => $confirmation['provider_reference'],
            ]);

            $code = json_encode([
                'reference' => $reservation->reference,
                'trip_id' => $reservation->trip_id,
                'nom' => $reservation->client_nom,
                'date' => $reservation->trip->date_depart->format('Y-m-d'),
            ], JSON_UNESCAPED_UNICODE);

            $reservation->update([
                'statut' => 'confirmee',
                'qr_code' => $code,
            ]);
        }

        return view('payment.confirmation', compact('reservation', 'qr'));
    }

    public function eBillet(Reservation $reservation, QrCodeService $qr)
    {
        abort_unless($reservation->statut === 'confirmee', 404, 'E-billet non disponible.');

        return view('payment.ebillet', compact('reservation', 'qr'));
    }
}