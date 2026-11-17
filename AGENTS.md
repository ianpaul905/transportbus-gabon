# Contexte projet — TransportBus Gabon

Mémoire persistante du projet : à relire au début de chaque session.

## Nature du projet

Application web de réservation de billets de bus interurbains (Gabon),
projet de fin d'études **DUT Génie Logiciel — I.S.T Libreville**.
Paiement Mobile Money simulé (Airtel Money / Moov Money), e-billet QR Code,
suggestion de dates alternatives si le bus est complet.

## Emplacement et dépôt

- Code : `F:\reservation-bus\app` (racine du dépôt Git, branche `master`)
- Remote : `https://github.com/ianpaul905/transportbus-gabon.git`
- Documentation fonctionnelle : `README.md` (à jour, en français)

## Stack

- Laravel 12 / PHP 8.2, architecture MVC, vues Blade, Tailwind + Vite
- Local : MySQL 8.4 (`F:\mysql-8.4.4-winx64`, `start-mysql.bat` / `stop-mysql.bat`),
  base `transportbus`, utilisateur `root` sans mot de passe
- Repli sans config : SQLite (`database/database.sqlite`)
- Production : Render (Dockerfile + `render.yaml`) + PostgreSQL Neon via `DATABASE_URL`
- PHP CLI hors PATH : `D:\wamp64\bin\php\php8.2.29\php.exe`
- QR Code : BaconQrCode (SVG)

## Outils front-end (Tailwind, Vite, Axios)

- **Tailwind CSS 4** : `@import 'tailwindcss'` en tête de `resources/css/app.css`.
  Thème dans `@theme` (`--color-brand-*`, `--color-menu-400`) et classes métier
  dans `@layer components` (`.card`, `.btn`, `.badge`, `.trip-card`, `.stat`…).
  Les vues Blade utilisent directement ces classes (`class="card flex-between"`).
- **Vite 7** : `vite.config.js` déclare les entrées `resources/css/app.css` et
  `resources/js/app.js`, avec `laravel-vite-plugin` et `@tailwindcss/vite`.
  Dev : `npm run dev` (HMR). Prod : `npm run build` → `public/build/manifest.json`
  et assets minifiés (aussi compilés par le Dockerfile Render).
  Chargement via `@vite([...])` dans `resources/views/layouts/app.blade.php`.
- **Axios** : importé dans `resources/js/bootstrap.js`, exposé en `window.axios`
  avec l'en-tête `X-Requested-With: XMLHttpRequest`. Installé et prêt, mais
  **aucune vue ne l'utilise encore** (recherche/paiement via formulaires classiques).

## Fonctionnalités

- Client : recherche de trajets, places restantes, réservation, paiement Mobile Money
  simulé, e-billet QR Code, dates alternatives
- Admin : tableau de bord, gestion bus / trajets / réservations, CA par opérateur
- Contrôleur : départs du jour, scan/vérification des billets, validation d'embarquement

## Comptes de démonstration

| Rôle       | E-mail                       | Mot de passe |
|------------|------------------------------|--------------|
| Admin      | admin@transportbus.ga        | password123  |
| Contrôleur | controleur@transportbus.ga   | password123  |
| Client     | client@transportbus.ga       | password123  |

## Commandes utiles

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan serve          # http://127.0.0.1:8000
php functional-test.php    # tests fonctionnels Cas 1..6 (tableau 5 du mémoire)
```

Seeders idempotents ; l'entrypoint Docker génère `APP_KEY`, migre et seed au déploiement.

## Structure clé

- `app/Http/Controllers/` : Home, Auth, Reservation, Payment, Admin, Controleur
- `app/Models/` : Agency, Bus, Trip, Reservation, Payment, User
- `app/Services/` : QrCodeService, MobileMoneyService, AlternativeDatesService
- `app/Http/Middleware/RoleMiddleware.php` : accès par rôle
- `database/migrations/` : 5 entités métier + users/cache/jobs
- `routes/web.php` : routes publiques + espaces protégés par rôle

## Conventions de travail

- Répondre en français.
- Ne pas ajouter de commentaires dans le code.
- Ne jamais commit/push sans demande explicite.
- Vérifier avec `php functional-test.php` après un changement fonctionnel.

## État connu (dernier commit : 04967ac, 2026-09-10)

Déploiement Render opérationnel (HTTPS forcé pour les assets derrière le proxy).
Connexion Neon directe sans pooler, `sslmode=require`, assets Vite/Tailwind
compilés pendant le build Docker.

## Session 2026-11-17 — travail repris et finalisé (non commité)

Durcissement sécurité + conformité : throttle sur les routes sensibles, contrôle
d'accès au paiement/e-billet (propriétaire, session ou staff), en-têtes de sécurité
(HSTS/CSP...) via `SecurityHeaders`, cookies de session sécurisés sur Render,
comptes démo supprimés en production au profit de `ADMIN_*` / `CONTROLEUR_*`,
pages Mentions légales / CGV / Confidentialité, JS inline déplacé dans `app.js`.

Vérifications OK : `php -l` sur les fichiers modifiés, `npm run build`,
`php functional-test.php` → 6 PASS / 0 FAIL (après régénération des trajets de démo,
le seed initial avait des dates passées).

Avant déploiement : renseigner `ADMIN_EMAIL`, `ADMIN_PASSWORD`, `CONTROLEUR_EMAIL`
et `CONTROLEUR_PASSWORD` dans le dashboard Render (variables `sync: false`).
