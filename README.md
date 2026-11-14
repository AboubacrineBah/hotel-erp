# 🏨 Hôtels & Resorts — Vitrine Client & ERP Hôtelier (PWA / Laravel)

Application web **Mobile-First / Progressive Web App (PWA)** développée sous **Laravel** réunissant une **vitrine publique de réservation hôtelière** (inspirée de l'expérience *Warm Luxury* du Groupe Hôtels) et un **ERP hôtelier complet** protégé par contrôle d'accès basé sur les rôles (**RBAC**).

---

## ✨ Fonctionnalités Principales

### 1. Espace Visiteur Public & Expérience Client
- **Interface Publique Épurée :** Navigation pensée pour le visiteur anonyme (Destinations phares : *Abidjan, Bamako, Dakar, Cotonou, Ouagadougou*, Expériences & Gastronomie, Club Fidélité *Hotel Nomad*).
- **Moteur de Réservation Temps Réel :** Calcul dynamique des nuitées, sélection d'options VIP (Transfert Aéroport, Demi-Pension) et tarification en **XOF**.
- **Facturation PDF Instantanée :** Génération automatique d'une facture normalisée (HT, TVA hôtelière 18%, Net à Payer TTC) téléchargeable et imprimable au format A4 via **DomPDF**.
- **Programme Fidélité Azalaï Nomad :** Cumul automatique de points à chaque séjour pour les membres connectés.

### 2. Back-Office ERP Hôtelier & Privilèges (RBAC)
Pilotage centralisé avec cloisonnement strict des droits via **Spatie Laravel Permission** :
- **Réservations & CRM :** Suivi des dossiers clients, validation des check-ins et synchronisation temps réel avec les réservations publiques.
- **Facturation & Comptabilité :** Registre des factures encaissées, suivi du chiffre d'affaires (visible uniquement par les profils autorisés) et export PDF.
- **Stock & Économat :** Gestion des stocks Minibar, Restaurant & Cave avec alertes de seuil critique et réapprovisionnement rapide.
- **Ressources Humaines (RH) :** Gestion des brigades par hôtel, plannings, bascule présence/congé et visibilité sécurisée des rémunérations.
- **Administration & Matrice RBAC (Super Admin) :** Création de comptes, attribution dynamique des rôles et activation/suspension d'accès.

---

## 🔐 Comptes de Démonstration (Seeder RBAC)

Après exécution de `php artisan db:seed`, les 5 profils suivants sont prêts à l'emploi (Mot de passe commun : **`Azalai2026`**) :

| Rôle Système | Utilisateur | Email de Connexion | Privilèges & Périmètre |
| :--- | :--- | :--- | :--- |
| **`super_admin`** | Moussa Keïta | `dg@azalai.com` | Accès complet (Tous modules, Finances, Salaires, Admin RBAC) |
| **`reception`** | Fatou Ndiaye | `reception@azalai.com` | Réservations, Check-in CRM & Facturation clients |
| **`stock`** | Ousmane Bamba | `stock@azalai.com` | Stock Minibar, Restaurant, Alertes & Réapprovisionnement |
| **`hr`** | Kadiatou Traoré | `rh@azalai.com` | Effectifs RH, Plannings, Présences & Salaires |
| **`vip_client`** | Amadou Diallo | `amadou.diallo@vip.com` | Vitrine Publique, Mes Séjours, Factures & Points Nomad |

---

## 🛠️ Stack Technique

- **Serveur Local :** Windows + XAMPP (Apache, MySQL, PHP 8.2 / 8.3)
- **Backend :** Laravel 11/12, Spatie Laravel Permission, Barryvdh DomPDF
- **Frontend & Mobile :** Bootstrap 5, Sass, FontAwesome 6, Vite, `vite-plugin-pwa`
- **Qualité & CI/CD :** PHPUnit, Laravel Pint (PSR-12), GitHub Actions (`.github/workflows/site-check.yml`)

---

## 🚀 Installation & Lancement Local (XAMPP)

### 1. Prérequis
- **XAMPP** installé avec **Apache** et **MySQL** démarrés.
- **PHP >= 8.2**, **Composer** et **Node.js >= 20** configurés dans le `PATH` Windows.

### 2. Cloner le dépôt & installer les dépendances
Ouvrez un terminal dans `C:\xampp\htdocs` :

```bash
git clone https://github.com/TON-UTILISATEUR/azalai-erp-laravel.git azalai-erp
cd azalai-erp

# Dépendances PHP
composer install

# Dépendances Front-End & PWA
npm install
```

### 3. Configuration de l'environnement (`.env`) & Base de données
1. Créez une base de données MySQL nommée **`hotel-erp`** (`utf8mb4_unicode_ci`) via **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Copiez le fichier d'environnement et générez la clé de sécurité :

```bash
cp .env.example .env
php artisan key:generate
```

3. Vérifiez les paramètres de connexion MySQL dans `.env` :

```env
APP_NAME="Hôtels & Resorts ERP"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel-erp
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrations & Peuplement des données initiales
Exécutez les migrations et les seeders pour créer les tables, les rôles RBAC, les 5 hôtels Azalaï et les comptes de démonstration :

```bash
php artisan migrate --seed
```

### 5. Démarrage de l'application
Lancez le serveur de compilation Vite et le serveur Laravel dans deux terminaux distincts :

```bash
# Terminal 1 : Assets Bootstrap 5, Sass & PWA
npm run dev

# Terminal 2 : Serveur Web Laravel
php artisan serve
```

Ouvrez votre navigateur sur **`http://127.0.0.1:8000`**.

---

## 🧪 Tests, Qualité & Sécurité

```bash
# Exécuter les tests fonctionnels & RBAC (PHPUnit)
php artisan test

# Vérifier le formatage PSR-12 (Laravel Pint)
./vendor/bin/pint --test

# Audits de sécurité
composer audit
npm audit --audit-level=moderate
```

---

## 📄 Licence
Projet développé par **NexaWeb Mali** — Tous droits réservés.
