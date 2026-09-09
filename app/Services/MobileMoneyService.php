<?php

namespace App\Services;

class MobileMoneyService
{
    public const MODES = [
        'airtel_money' => 'Airtel Money',
        'moov_money' => 'Moov Money',
    ];

    /**
     * Simule l'envoi d'une requête de paiement vers l'API Mobile Money.
     *
     * @return array{success: bool, reference: ?string, message: string}
     */
    public function initierPaiement(string $telephone, string $montant, string $mode): array
    {
        $reference = 'MM_' . strtoupper(bin2hex(random_bytes(8)));

        return [
            'success' => true,
            'reference' => $reference,
            'message' => "Demande de paiement de {$montant} FCFA envoyée à {$telephone} via " . self::MODES[$mode] . '.',
        ];
    }

    /**
     * Simule la confirmation d'une transaction par l'opérateur.
     *
     * @return array{success: bool, provider_reference: ?string, message: string}
     */
    public function confirmerPaiement(string $reference, string $telephone): array
    {
        return [
            'success' => true,
            'provider_reference' => 'PRV_' . strtoupper(bin2hex(random_bytes(6))),
            'message' => 'Paiement confirmé par l\'opérateur Mobile Money.',
        ];
    }
}