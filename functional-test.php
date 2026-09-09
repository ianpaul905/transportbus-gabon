<?php
/**
 * Tests fonctionnels autonomes — TransportBus Gabon
 *
 * Correspond au « Tableau 5 : Récapitulatif des cas de tests fonctionnels »
 * du mémoire. Lancement :
 *
 *   1. php artisan serve --port=8000   (dans un terminal)
 *   2. php functional-test.php          (dans un second terminal)
 *
 * Sortie : une ligne « PASS » / « FAIL » par cas de test, puis un bilan.
 */

$base = getenv('APP_URL_TEST') ?: 'http://127.0.0.1:8000';

$PASS = 0;
$FAIL = 0;

function check(string $cas, bool $ok, string $detail = ''): void
{
    global $PASS, $FAIL;
    if ($ok) { $PASS++; echo "[PASS] $cas\n"; }
    else     { $FAIL++; echo "[FAIL] $cas — $detail\n"; }
}

function getToken(string $html): ?string
{
    return preg_match('/name="_token" value="([^"]+)"/', $html, $m) ? $m[1] : null;
}

function client(string $cookie): CurlHandle
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookie,
        CURLOPT_COOKIEFILE => $cookie,
    ]);

    return $ch;
}

function req(CurlHandle $ch, string $url, ?array $post = null): array
{
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $post !== null);
    if ($post !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    $html = curl_exec($ch);

    return [$html, curl_getinfo($ch, CURLINFO_HTTP_CODE)];
}

function login(CurlHandle $ch, string $base, string $email, string $password): int
{
    [$page, ] = req($ch, $base . '/login');
    [, $code] = req($ch, $base . '/login', [
        'email' => $email, 'password' => $password, '_token' => getToken($page),
    ]);

    return $code;
}

function freshToken(CurlHandle $ch, string $base, string $page = '/'): ?string
{
    [$html, ] = req($ch, $base . $page);

    return getToken($html);
}

echo "=== Tests fonctionnels TransportBus Gabon ===\n";
echo "Cible : $base\n\n";

$dir = sys_get_temp_dir() . '/tb_tests_' . uniqid();
@mkdir($dir);

// ---- Cas 1 : recherche d'un trajet avec disponibilité en temps réel ----
$ck = "$dir/c1.txt";
$c  = client($ck);
login($c, $base, 'client@transportbus.ga', 'password123');
$t = freshToken($c, $base);
[$html, $code] = req($c, $base . '/recherche', [
    'ville_depart' => 'Libreville', 'ville_arrivee' => 'Mouila',
    'date_depart' => date('Y-m-d', strtotime('+2 days')), '_token' => $t,
]);
$nb = preg_match_all('/Libreville → Mouila/', $html) ?? 0;
$affPlace = strpos($html, 'place(s) restante(s)') !== false;
check('Cas 1 — Recherche de trajet : liste affichée avec places restantes', $code === 200 && $nb > 0 && $affPlace, "http=$code trajets=$nb");

// ---- Cas 2 : réservation d'une place ----
preg_match('/reserver\?trip_id=(\d+)/', $html, $m);
$tripId = $m[1] ?? null;
$resId = null;
if ($tripId) {
    $t = freshToken($c, $base, "/reserver?trip_id=$tripId");
    [$html, $code] = req($c, $base . '/reservations', [
        'trip_id' => $tripId, 'client_nom' => 'Client Test Fonctionnel',
        'client_telephone' => '+24107000000', 'nombre_places' => '1', '_token' => $t,
    ]);
    preg_match('#/paiement/(\d+)#', $html, $m2);
    $resId = $m2[1] ?? null;
    check('Cas 2 — Réservation créée et redirigée vers le paiement', $code === 200 && $resId !== null, "http=$code");
}

// ---- Cas 3 : paiement Mobile Money (Airtel Money) ----
if ($resId) {
    $t = freshToken($c, $base, "/paiement/$resId");
    [$html, $code] = req($c, $base . "/paiement/$resId/initier", [
        'telephone' => '+24107000000', 'mode_paiement' => 'airtel_money', '_token' => $t,
    ]);
    $ref = strpos($html, 'MM_') !== false || strpos($html, 'Airtel') !== false;
    check('Cas 3 — Initiation du paiement Airtel Money', $code === 200 && $ref, "http=$code");

    $t = freshToken($c, $base, "/paiement/$resId");
    [$html, $code] = req($c, $base . "/paiement/$resId/confirmer", ['_token' => $t]);
    $okConf = strpos($html, 'confirmé') !== false || strpos($html, 'confirmée') !== false;
    check('Cas 4 — Confirmation de la transaction et réservation confirmée', $code === 200 && $okConf, "http=$code");
}

// ---- Cas 5 : génération de l'e-billet avec QR Code ----
if ($resId) {
    [$html, $code] = req($c, $base . "/ebillet/$resId");
    $qr = strpos($html, '<svg') !== false;
    check('Cas 5 — E-billet généré avec QR Code vérifiable', $code === 200 && $qr, "http=$code qr=" . ($qr ? 'oui' : 'non'));
}

// ---- Cas 6 : contrôle du billet (embarquement) ----
$ck6 = "$dir/c6.txt";
$c6 = client($ck6);
login($c6, $base, 'controleur@transportbus.ga', 'password123');
$okScan = false;
if ($resId) {
    $t = freshToken($c6, $base, '/controleur/scanner');
    [$html, $code] = req($c6, $base . '/controleur/verifier', ['reference' => "$resId", '_token' => $t]);
    $okScan = $code === 200 && strpos($html, 'Billet') !== false;
    check('Cas 6 — Contrôleur vérifie l\'e-billet (scanner)', $okScan, "http=$code");
}

foreach (glob("$dir/*.txt") as $f) { @unlink($f); }
@rmdir($dir);

echo "\n==========================\n";
echo "Résultat : $PASS PASS / $FAIL FAIL\n";
exit($FAIL === 0 ? 0 : 1);