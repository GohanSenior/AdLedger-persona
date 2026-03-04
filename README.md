# AdLedger Persona

Application web de gestion de **personas marketing** développée en PHP natif avec une architecture MVC maison.

---

## Sommaire

- [AdLedger Persona](#adledger-persona)
  - [Sommaire](#sommaire)
  - [Prérequis](#prérequis)
  - [Installation](#installation)
    - [1. Cloner ou copier le projet](#1-cloner-ou-copier-le-projet)
    - [2. Importer la base de données](#2-importer-la-base-de-données)
    - [3. Configurer l'application](#3-configurer-lapplication)
    - [4. Lancer l'application](#4-lancer-lapplication)
  - [Configuration](#configuration)
    - [`[database]`](#database)
    - [`[app]`](#app)
    - [`[smtp]`](#smtp)
  - [Structure du projet](#structure-du-projet)
  - [Fonctionnalités](#fonctionnalités)
    - [Gestion des utilisateurs](#gestion-des-utilisateurs)
    - [Gestion des personas](#gestion-des-personas)
    - [Gestion des opérations](#gestion-des-opérations)
    - [Gestion des entreprises](#gestion-des-entreprises)
  - [Rôles et accès](#rôles-et-accès)
  - [Routeur](#routeur)
  - [Librairies utilisées](#librairies-utilisées)
  - [Documentation complémentaire](#documentation-complémentaire)

---

## Prérequis

| Outil | Version recommandée |
| --- | --- |
| PHP | 8.3+ |
| MySQL | 8.4+ |
| Serveur local | [Laragon](https://laragon.org/) (recommandé) ou XAMPP/WAMP |
| Navigateur | Chrome, Firefox, Edge (versions récentes) |

---

## Installation

### 1. Cloner ou copier le projet

Placer le dossier dans le répertoire web de votre serveur local :

```text
C:\laragon\www\adledger_persona\
```

### 2. Importer la base de données

1. Ouvrir **phpMyAdmin** (`http://localhost/phpmyadmin`)
2. Créer une base de données nommée `adledger_persona`
3. Importer le fichier `BDD/adledger_persona.sql`

### 3. Configurer l'application

Copier le fichier exemple et le renseigner :

```bash
cp config/config.ini.example config/config.ini
```

Puis éditer `config/config.ini` (voir section [Configuration](#configuration)).

### 4. Lancer l'application

Démarrer Laragon et ouvrir :

```text
http://localhost/adledger_persona
```

---

## Configuration

Le fichier `config/config.ini` contient trois sections :

### `[database]`

```ini
host     = localhost
port     = 3306
dbname   = adledger_persona
user     = root
password = ""
charset  = utf8mb4
```

### `[app]`

```ini
base_url    = http://localhost/adledger_persona
admin_email = admin@example.com
admin_name  = Administrateur
```

> En production, remplacer `base_url` par l'URL publique et renseigner l'email réel de l'administrateur qui recevra les notifications de création de personas.

### `[smtp]`

Par défaut, l'envoi d'emails utilise la fonction `mail()` de PHP. Pour activer un serveur SMTP :

```ini
enabled  = true
host     = smtp.example.com
port     = 587
secure   = tls
username = votre-email@example.com
password = votre-mot-de-passe
```

> Voir `docs/CONFIGURATION_SMTP_PRODUCTION.md` pour le détail complet.

---

## Structure du projet

```text
adledger_persona/
├── index.php                  # Point d'entrée unique — routeur principal
├── config/
│   ├── config.ini             # Configuration locale (ne pas versionner)
│   └── config.ini.example     # Modèle de configuration
├── app/
│   ├── Config.php             # Lecture du config.ini
│   ├── Database.php           # Connexion PDO (singleton)
│   ├── Controllers/           # Logique métier
│   │   ├── UsersController.php
│   │   ├── PersonasController.php
│   │   ├── OperationsController.php
│   │   └── CompaniesController.php
│   ├── Models/                # Accès à la base de données
│   │   ├── Model.php          # Classe parente (connexion PDO)
│   │   ├── Users.php
│   │   ├── Personas.php
│   │   ├── Operations.php
│   │   ├── Companies.php
│   │   ├── Criteria.php
│   │   └── Criteria_types.php
│   ├── Views/                 # Templates HTML/PHP
│   │   ├── gabarit.php        # Layout commun (navbar, footer, assets)
│   │   └── *.php              # Pages individuelles
│   ├── Services/
│   │   ├── Avatar/            # Génération d'avatars SVG
│   │   └── PHPMailer/         # Envoi d'emails (wrapper + librairie)
│   └── templates/             # Templates HTML pour les emails
├── assets/
│   ├── css/                   # Feuilles de style
│   ├── js/                    # Scripts JS (jQuery, DataTables, script.js)
│   ├── img/                   # Images et icônes
│   ├── fonts/                 # Polices locales (Roboto, Jura)
│   └── logo/                  # Logos uploadés par les utilisateurs (non versionnés sauf default)
└── BDD/                       # Scripts SQL
    └── adledger_persona.sql       # Script de production

```

---

## Fonctionnalités

### Gestion des utilisateurs

- Inscription avec création automatique de l'entreprise associée
- Connexion / déconnexion
- Déconnexion automatique après 30 minutes d'inactivité (avertissement à 25 min)
- Réinitialisation du mot de passe par email
- Activation / désactivation d'un compte (admin)
- Profil utilisateur

### Gestion des personas

- Création via un formulaire multi-étapes
- Avatar généré aléatoirement (service `AvatarRandomizer`)
- Association à des critères typés (ex. : comportements, besoins...)
- Bascule persona normal ↔ persona type
- Filtrage et tri via DataTables
- Notification email à l'administrateur lors de chaque création

### Gestion des opérations

- Création et édition des opérations marketing
- Association de personas à une opération
- Vue détaillée d'une opération avec ses personas liés

### Gestion des entreprises

- Édition du profil de l'entreprise
- Upload du logo

---

## Rôles et accès

| Rôle | Description |
| --- | --- |
| `admin` | Accès complet : gestion des utilisateurs, de toutes les entreprises, activation/désactivation des comptes |
| `user` | Accès limité à ses propres données : ses personas, ses opérations, son profil |

L'administrateur est identifié par le rôle `admin` en base de données. L'email de notification lui étant destiné est configurable via `admin_email` dans `config/config.ini`.

L'accès aux pages protégées est contrôlé par `UsersController::isLoggedIn()`, appelé en début de chaque méthode de contrôleur nécessitant une authentification.

---

## Routeur

L'application utilise un **point d'entrée unique** : `index.php`. Toutes les URLs passent par le paramètre GET `action` :

```text
http://localhost/adledger_persona/index.php?action=<nom-de-la-route>
```

| Action | Contrôleur | Description |
| --- | --- | --- |
| `login` | `UsersController` | Page de connexion |
| `register` | `UsersController` | Inscription |
| `dashboard` | `UsersController` | Tableau de bord |
| `forgot-password` | `UsersController` | Mot de passe oublié |
| `reset-password` | `UsersController` | Réinitialisation mdp |
| `profile-user` | `UsersController` | Profil utilisateur |
| `edit-user` | `UsersController` | Édition du profil |
| `list-users` | `UsersController` | Liste des utilisateurs (admin) |
| `view-user` | `UsersController` | Fiche d'un utilisateur |
| `toggle-user` | `UsersController` | Activer / désactiver un compte |
| `logout` | `UsersController` | Déconnexion |
| `edit-company` | `CompaniesController` | Édition de l'entreprise |
| `create-persona` | `PersonasController` | Création d'un persona |
| `list-personas` | `PersonasController` | Mes personas |
| `list-personas-types` | `PersonasController` | Personas types |
| `list-all-personas` | `PersonasController` | Tous les personas (admin) |
| `view-persona` | `PersonasController` | Fiche d'un persona |
| `edit-persona` | `PersonasController` | Édition d'un persona |
| `delete-persona` | `PersonasController` | Suppression |
| `toggle-persona` | `PersonasController` | Passer en persona type |
| `toggle-persona-type-to-normal` | `PersonasController` | Copier un persona type vers ses personas |
| `list-operations` | `OperationsController` | Liste des opérations |
| `view-operation` | `OperationsController` | Fiche d'une opération |
| `edit-operation` | `OperationsController` | Édition d'une opération |
| `delete-operation` | `OperationsController` | Suppression |
| `search-criteria` | `PersonasController` | Recherche de critères par type (AJAX — autocomplétion) |

**Pour ajouter une nouvelle page :**

1. Créer la méthode dans le contrôleur concerné
2. Créer la vue dans `app/Views/`
3. Ajouter le `case` correspondant dans le `switch` de `index.php`

---

## Librairies utilisées

| Librairie | Version | Usage |
| --- | --- | --- |
| [Bootstrap](https://getbootstrap.com/) | 5.x | Framework CSS / composants UI |
| [Bootstrap Icons](https://icons.getbootstrap.com/) | — | Icônes |
| [jQuery](https://jquery.com/) | 3.x | Manipulation DOM, AJAX |
| [DataTables](https://datatables.net/) | 2.x | Tableaux avec tri, recherche, pagination |
| [PHPMailer](https://github.com/PHPMailer/PHPMailer) | 7.x | Envoi d'emails SMTP |
| [DiceBear](https://www.dicebear.com/) *(bundlé)* | — | Génération d'avatars SVG |

---

## Documentation complémentaire

| Document | Contenu |
| --- | --- |
| `docs/ARCHITECTURE.md` | Cycle de vie d'une requête, gabarit.php, modèles, transactions, conventions |
| `docs/CONFIGURATION_SMTP_PRODUCTION.md` | Configurer l'envoi d'emails en production |
| `docs/DOCUMENTATION_AVATAR_RANDOMIZER.md` | Fonctionnement du service de génération d'avatars |
| `docs/DOCUMENTATION_RESET_PASSWORD.md` | Fonctionnement, sécurité et personnalisation de la réinitialisation de mot de passe |
| `docs/DOCUMENTATION_DATATABLES.md` | Intégration, configuration et fonctionnement des tableaux DataTables (tri, recherche, pagination, filtre personnalisé) |
