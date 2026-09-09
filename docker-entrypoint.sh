#!/bin/sh
set -e

# Génère la clé APP_KEY si absente
if [ -z "${APP_KEY:-}" ] || [ "${APP_KEY:-null}" = "null" ]; then
    php artisan key:generate --force
fi

# Caches de production
php artisan config:cache && php artisan route:cache && php artisan view:cache || true

# Mise à jour du port d'écoute Apache
if [ -n "$PORT" ]; then
    sed -i -e "s/^Listen .*/Listen $PORT/" /etc/apache2/ports.conf
    sed -i -e "s|ServerName .*|ServerName localhost|" /etc/apache2/apache2.conf
fi

# Attente de la base, puis migrations
php -r '
    if (getenv("DB_CONNECTION") === null || getenv("DB_CONNECTION") === "sqlite") { exit(0); }
    $host = getenv("DB_HOST") ?: "127.0.0.1";
    $port = getenv("DB_PORT") ?: "5432";
    $ok = false;
    for ($i = 0; $i < 60; $i++) {
        $c = @fsockopen($host, $port, $e1, $e2, 1);
        if ($c) { $ok = true; fclose($c); break; }
        sleep(1);
    }
    if (!$ok) { fwrite(STDERR, "Base de données injoignable après 60s.\n"); exit(1); }
'
php artisan migrate --force

exec "$@"