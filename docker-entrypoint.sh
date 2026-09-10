#!/bin/sh
set -e

# Génère la clé APP_KEY si absente
if [ -z "${APP_KEY:-}" ] || [ "${APP_KEY:-null}" = "null" ]; then
    php artisan key:generate --force
fi

# Caches de production
php artisan config:cache && php artisan route:cache && php artisan view:cache || true

# Mise à jour du port d'écoute Apache ($PORT de Render)
if [ -n "$PORT" ]; then
    sed -i -e "s/^Listen .*/Listen $PORT/" /etc/apache2/ports.conf
    sed -i -e "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:$PORT>/" /etc/apache2/sites-available/000-default.conf
    sed -i -e "s/ServerName .*/ServerName localhost/" /etc/apache2/apache2.conf
fi

# Attente de la base (hôte/port depuis DATABASE_URL ou DB_HOST), puis migrations
php -r '
    if (getenv("DB_CONNECTION") === null || getenv("DB_CONNECTION") === "sqlite") { exit(0); }
    $url = getenv("DATABASE_URL") ?: "tcp://" . (getenv("DB_HOST") ?: "127.0.0.1") . ":" . (getenv("DB_PORT") ?: "5432");
    if (strpos($url, "://") === false) { $url = "tcp://" . $url; }
    $host = parse_url($url, PHP_URL_HOST) ?: "127.0.0.1";
    $port = (int) (parse_url($url, PHP_URL_PORT) ?: 5432);
    $ok = false;
    for ($i = 0; $i < 60; $i++) {
        $c = @fsockopen($host, $port, $e1, $e2, 1);
        if ($c) { $ok = true; fclose($c); break; }
        sleep(1);
    }
    if (!$ok) { fwrite(STDERR, "Base de données injoignable après 60s ($host:$port).\n"); exit(1); }
'
php artisan migrate --force

# Données de démonstration (idempotente : ne crée pas de doublons)
php artisan db:seed --force

exec "$@"