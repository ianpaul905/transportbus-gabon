# TransportBus Gabon

Application web de **réservation de billets de transport interurbain** avec **paiement Mobile Money** (Airtel Money / Moov Money), e-billet sécurisé par **QR Code** et suggestion automatique de **dates alternatives** en cas de bus complet.

Projet de fin d'études **DUT Génie Logiciel** — I.S.T Libreville (Gabon), conformément au mémoire « Mémoire DUT ».

---

## Fonctionnalités

### Côté client (usager)
- Recherche de trajets par ville de départ, ville d'arrivée et date
- Affichage en temps réel du nombre de places restantes par trajet
- Réservation en ligne (sans déplacement à l'agence)
- Paiement **Mobile Money** simulé (Airtel Money / Moov Money)
- **E-billet** téléchargeable avec **QR Code** vérifiable à l'embarquement
- **Algorithme de dates alternatives** : si le bus est complet, le système propose automatiquement les prochains départs disponibles sur le même axe

### Côté administrateur
- Tableau de bord (statistiques, chiffre d'affaires, prochains départs)
- Gestion des **bus** (immatriculation, places, statut, agence)
- Programmation des **trajets** (villes, date, heure, prix, bus)
- Consultation des **réservations**
- Suivi du **chiffre d'affaires** par opérateur Mobile Money

### Côté contrôleur
- Liste des départs du jour
- **Scanner / vérification** des e-billets (QR Code)
- Validation de l'embarquement (statut → « utilisée »)

---

## Technologie

| Couche    | Technologie |
|-----------|-------------|
| Back-end  | Laravel 12 (PHP 8.2) — architecture MVC |
| Front-end | HTML5, CSS3, JavaScript (vues Blade) |
| BDD       | MySQL (production) — SQLite (local/démo) |
| QR Code   | BaconQrCode (SVG) |
| Déploiement | Git + GitHub (Render compatible) |

---

## Installation rapide (SQLite — zéro configuration)

Prérequis : **PHP ≥ 8.2** (extensions `pdo_sqlite`, `gd`, `mbstring`, `openssl`, `zip`, `sqlite3`).

```bash
cd F:\reservation-bus
cd app
composer install --no-dev        # dépendances (vendor déjà fourni dans ce livrable)
copy database\database.sqlite database\database.sqlite.bak  # (optionnel)
php artisan key:generate          # si APP_KEY vide dans .env
php artisan migrate --force
php artisan db:seed --force       # comptes de démonstration + trajets
php artisan serve
```

Puis ouvrir **http://127.0.0.1:8000**.

### Comptes de démonstration (seeders)

| Rôle       | E-mail                    | Mot de passe |
|------------|---------------------------|--------------|
| Admin      | `admin@transportbus.ga`   | `password123`|
| Contrôleur | `controleur@transportbus.ga` | `password123`|
| Client     | `client@transportbus.ga`  | `password123`|

---

## Installation avec MySQL (conforme au mémoire)

MySQL Community **8.4.4** est déjà installé sur ce poste (version "noinstall" dans `F:\mysql-8.4.4-winx64`, base `transportbus` créée et peuplée).

### Démarrer / arrêter MySQL

```cmd
F:\mysql-8.4.4-winx64\start-mysql.bat    :: démarre mysqld (aucune élévation requise)
F:\mysql-8.4.4-winx64\stop-mysql.bat     :: l'arrête
```

- Connexion : `mysql --protocol=tcp -h127.0.0.1 -P3306 -uroot` (compte `root`, mot de passe vide en local).
- Le `.env` de l'application pointe déjà sur MySQL : `DB_CONNECTION=mysql`, base **transportbus**, user `root`.

### (Re)configurer sur un autre poste

1. Installer MySQL (serveur + client) et créer la base :
   ```sql
   CREATE DATABASE transportbus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Adapter le fichier `.env` :
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=transportbus
   DB_USERNAME=root
   DB_PASSWORD=ton_mot_de_passe
   ```

3. Prérequis PHP : extension `pdo_mysql` (déjà activée dans le php.ini fourni).

4. Migrer et peupler :
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

---

## Tests fonctionnels

Le script autonome `functional-test.php` couvre les cas du **Tableau 5** du mémoire :

```bash
php artisan serve
php functional-test.php
```

Sortie : `Cas 1 … Cas 6` avec `[PASS]`/`[FAIL]` et un bilan final.

---

## Structure du projet

```
app/                     code applicatif
  Http/Controllers/      Home, Auth, Reservation, Payment, Admin, Controleur
  Models/                Agency, Bus, Trip, Reservation, Payment, User
  Services/              QrCodeService, MobileMoneyService, AlternativeDatesService
  Http/Middleware/       RoleMiddleware
database/
  migrations/            5 entités + users/cache/jobs
  seeders/               DatabaseSeeder (comptes, agences, bus, trajets)
resources/views/         vues Blade (client, admin, contrôleur)
routes/web.php           routes publiques + espaces protégés par rôle
functional-test.php      tests fonctionnels autonomes
```

---

## Notes

- Le paiement Mobile Money est une **simulation** (conforme au mémoire : intégration en environnement de simulation, en attendant un partenariat opérateur réel).
- Mot de passe hachés (`bcrypt`) via Laravel.
- Validation systématique des formulaires, `CSRF` actif, requêtes sécurisées (Eloquent).