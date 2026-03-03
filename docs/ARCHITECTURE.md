# Architecture du projet

AdLedger Persona suit le **pattern MVC** (Modèle - Vue - Contrôleur) implémenté **sans framework**. Chaque brique (routeur, autoloader, connexion BDD, configuration) a été écrite manuellement, ce qui rend le projet autonome : aucune dépendance externe à installer hormis PHP et MySQL.

---

## Sommaire

- [Architecture du projet](#architecture-du-projet)
  - [Sommaire](#sommaire)
  - [Vue d'ensemble](#vue-densemble)
  - [Cycle de vie d'une requête](#cycle-de-vie-dune-requête)
    - [1. Réception dans `index.php`](#1-réception-dans-indexphp)
    - [2. Résolution des classes par l'autoloader](#2-résolution-des-classes-par-lautoloader)
    - [3. Dispatch dans le switch](#3-dispatch-dans-le-switch)
    - [4. Exécution dans le contrôleur](#4-exécution-dans-le-contrôleur)
    - [5. Rendu HTML via gabarit.php](#5-rendu-html-via-gabaritphp)
  - [Le routeur — index.php](#le-routeur--indexphp)
  - [L'autoloader](#lautoloader)
  - [La configuration — Config.php](#la-configuration--configphp)
  - [La connexion base de données — Database.php](#la-connexion-base-de-données--databasephp)
  - [Les modèles — Models/](#les-modèles--models)
    - [Classe parente `Model.php`](#classe-parente-modelphp)
    - [Exemple d'héritage](#exemple-dhéritage)
    - [Correspondance modèles / tables](#correspondance-modèles--tables)
  - [Les contrôleurs — Controllers/](#les-contrôleurs--controllers)
    - [Schéma type d'une méthode de contrôleur](#schéma-type-dune-méthode-de-contrôleur)
  - [Les vues — Views/ et gabarit.php](#les-vues--views-et-gabaritphp)
    - [gabarit.php — le layout commun](#gabaritphp--le-layout-commun)
    - [Transmission de données à la vue](#transmission-de-données-à-la-vue)
  - [Les transactions PDO](#les-transactions-pdo)
  - [Conventions de nommage](#conventions-de-nommage)

---

## Vue d'ensemble

```text
Navigateur
    │
    │  GET /index.php?action=create-persona
    ▼
index.php  ──── autoloader ────► charge les classes à la volée
    │
    │  switch($action)
    ▼
PersonasController::createPersona()
    │
    ├──► Personas::insert()  ──► Database::getConnection() ──► MySQL
    │         (Model)
    │
    └──► $content = 'app/Views/persona_form.php'
              │
              ▼
         gabarit.php  ──► require $content ──► HTML envoyé au navigateur
```

---

## Cycle de vie d'une requête

Voici ce qui se passe exactement quand un utilisateur clique sur un lien ou soumet un formulaire :

### 1. Réception dans `index.php`

Toutes les URLs passent par `index.php`. Le paramètre GET `action` détermine quelle page est demandée :

```text
http://localhost/adledger_persona/index.php?action=create-persona
```

Si `action` est absent, la valeur par défaut est `'home'` (redirection vers login ou dashboard selon la session).

### 2. Résolution des classes par l'autoloader

Avant d'exécuter quoi que ce soit, l'autoloader `spl_autoload_register()` est déclaré. Il cherche automatiquement les fichiers de classe dans les dossiers `Models/`, `Controllers/`, `Services/`... dès qu'une classe est instanciée. Il n'y a donc aucun `require_once` à écrire manuellement dans les contrôleurs.

### 3. Dispatch dans le switch

Le `switch($action)` instancie le contrôleur approprié et appelle la méthode correspondante :

```php
case 'create-persona':
    $controller = new PersonasController();
    $controller->createPersona();
    exit();
```

L'`exit()` après chaque `case` empêche le code de continuer vers le `require_once 'app/Views/gabarit.php'` prématurément (le contrôleur s'en charge lui-même).

### 4. Exécution dans le contrôleur

Le contrôleur :

1. Vérifie que l'utilisateur est connecté (`isLoggedIn()`)
2. Récupère les données POST/GET et les valide
3. Appelle les méthodes du modèle pour lire ou écrire en base
4. Définit la variable `$content` pointant vers la vue à afficher
5. Inclut `gabarit.php` qui va charger la vue dans le layout

### 5. Rendu HTML via gabarit.php

`gabarit.php` est le **layout commun** à toutes les pages. Il affiche la navbar, le footer, charge les assets CSS/JS, puis inclut dynamiquement la vue définie par `$content` :

```php
<?php if (isset($content)) {
    require $content;
} ?>
```

La vue elle-même affiche les données transmises par le contrôleur via des variables PHP.

---

## Le routeur — index.php

`index.php` est le **seul point d'entrée** de l'application. Il n'existe pas d'autres fichiers PHP accessibles directement depuis le navigateur (hormis les assets).

Structure générale :

```php
session_start();                         // 1. Démarrage de session
spl_autoload_register(function($class){  // 2. Autoloader
    // cherche le fichier de classe...
});

$action = $_GET['action'] ?? 'home';     // 3. Lecture de l'action demandée

try {                                    // 4. Protection contre les erreurs critiques (ex : BDD)
    switch ($action) {                   // 5. Dispatch
        case 'nom-de-la-route':
            $controller = new XxxController();
            $controller->methode();
            exit();
        // ...
        default:
            // Redirection selon l'état de session
    }

    require_once 'app/Views/gabarit.php';    // 6. Rendu final (si pas d'exit avant)
} catch (RuntimeException $e) {
    http_response_code(503);
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}
```

> **Pourquoi `exit()` dans chaque `case` ?**  
> Certains contrôleurs font une redirection HTTP (`header('Location: ...')`) et appellent eux-mêmes `gabarit.php`. Sans `exit()`, le code continuerait à s'exécuter après le `switch`, provoquant un double appel au gabarit ou des en-têtes déjà envoyés.

---

## L'autoloader

```php
spl_autoload_register(function ($class) {
    $folders = ['Models', 'Controllers', 'Views'];
    foreach ($folders as $folder) {
        $file = __DIR__ . '/app/' . $folder . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    // Cherche aussi dans Services/Avatar et Services/PHPMailer...
});
```

**Règle à respecter :** le nom du fichier doit être **identique** au nom de la classe. Ex : la classe `PersonasController` doit être dans `PersonasController.php`.

---

## La configuration — Config.php

`Config.php` est un **singleton** qui lit le fichier `config/config.ini` une seule fois, à la première demande, et met le résultat en cache dans une propriété statique :

```php
class Config {
    private static $config = null;

    public static function get($section, $key) {
        if (self::$config === null) {
            self::$config = parse_ini_file(__DIR__ . '/../config/config.ini', true);
        }
        return self::$config[$section][$key] ?? null;
    }
}
```

Utilisation dans le code :

```php
$baseUrl = Config::get('app', 'base_url');
$host    = Config::get('database', 'host');
```

---

## La connexion base de données — Database.php

`Database.php` est également un **singleton**. Il crée la connexion PDO une seule fois et la réutilise pour toutes les requêtes de la même page, évitant d'ouvrir plusieurs connexions inutiles :

```php
class Database {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            // Lecture des paramètres depuis Config
            // Création du PDO avec ERRMODE_EXCEPTION et FETCH_ASSOC par défaut
        }
        return self::$pdo;
    }
}
```

**Options PDO configurées :**

- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION` — les erreurs SQL lèvent une exception (attrapable avec `try/catch`)
- `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC` — les résultats sont toujours des tableaux associatifs (`$row['nom']` plutôt que `$row[0]`)

**Gestion des erreurs de connexion :**

En cas d'échec de connexion, `Database.php` logue l'erreur réelle via `error_log()` (visible uniquement dans les logs serveur) et lève une `RuntimeException` avec un message générique. Celle-ci est interceptée par le `try/catch` global de `index.php`, qui retourne un HTTP 503 sans exposer de détails techniques à l'utilisateur.

---

## Les modèles — Models/

### Classe parente `Model.php`

Tous les modèles héritent de la classe abstraite `Model` :

```php
abstract class Model {
    protected $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }
}
```

Grâce à l'héritage, chaque modèle dispose automatiquement de `$this->pdo` sans répéter la logique de connexion.

### Exemple d'héritage

```php
class Personas extends Model {
    public function getPersonaById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM persona WHERE id_persona = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}
```

### Correspondance modèles / tables

| Classe | Table(s) principale(s) |
| --- | --- |
| `Users` | `users` |
| `Companies` | `company` |
| `Personas` | `personas` |
| `Operations` | `operations` |
| `Criteria` | `criteria` |
| `Criteria_types` | `criteria_types` |

La table de liaison `associer` (persona ↔ critère) est gérée via des méthodes du modèle `Personas`.

---

## Les contrôleurs — Controllers/

Chaque contrôleur regroupe les méthodes liées à une entité. En début de chaque méthode nécessitant une authentification, on trouve :

```php
if (!UsersController::isLoggedIn()) {
    header('Location: index.php?action=login');
    exit();
}
```

Pour les actions réservées à l'admin :

```php
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php?action=dashboard');
    exit();
}
```

### Schéma type d'une méthode de contrôleur

```php
public function createPersona() {
    // 1. Vérification authentification
    if (!UsersController::isLoggedIn()) { ... }

    // 2. Traitement du formulaire (si POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validation des données
        // Appel du modèle
        // Redirection après succès
    }

    // 3. Préparation des données pour la vue
    $data = $this->personaModel->getAll();

    // 4. Définition de la vue et inclusion du layout
    $content = __DIR__ . '/../Views/persona_form.php';
    require_once __DIR__ . '/../Views/gabarit.php';
}
```

---

## Les vues — Views/ et gabarit.php

### gabarit.php — le layout commun

`gabarit.php` est inclus en **fin de chaque méthode de contrôleur** (jamais appelé directement depuis le navigateur). Il constitue l'enveloppe HTML commune à toutes les pages :

```text
gabarit.php
├── <head>       — CSS, favicon, meta
├── <nav>        — Navbar (adaptée selon la session)
├── <section>    — Zone principale
│     └── require $content  ← ici s'insère la vue spécifique
├── <footer>     — Liens légaux, copyright
└── <script>     — JS (jQuery, Bootstrap, DataTables, script.js)
```

La variable `$content` est définie dans le contrôleur et contient le chemin absolu vers la vue à afficher. `gabarit.php` la lit avec `require`.

### Transmission de données à la vue

Les données sont passées via des **variables PHP simples**, définies dans le contrôleur avant d'inclure `gabarit.php` :

```php
// Dans le contrôleur
$persona = $this->personaModel->getPersonaById($id);
$content = __DIR__ . '/../Views/profile_persona.php';
require_once __DIR__ . '/../Views/gabarit.php';

// Dans profile_persona.php
echo $persona['persona_firstname'];
```

---

## Les transactions PDO

Pour les opérations qui impliquent plusieurs écritures en base (ex : création d'un persona + association des critères), le code utilise les **transactions PDO** pour garantir l'intégrité des données :

```php
$pdo = Database::getConnection();
$pdo->beginTransaction();

try {
    // Écriture 1 : créer le persona
    // Écriture 2 : associer les critères
    $pdo->commit(); // Tout valider si tout s'est bien passé
} catch (Exception $e) {
    $pdo->rollBack(); // Tout annuler en cas d'erreur
    $errors[] = "Une erreur est survenue : " . $e->getMessage();
}
```

> Sans transaction, une erreur entre l'écriture 1 et l'écriture 2 laisserait la base dans un état incohérent (persona créé mais sans critères).

---

## Conventions de nommage

| Élément | Convention | Exemple |
| --- | --- | --- |
| Classes | `PascalCase` | `PersonasController`, `Users` |
| Méthodes | `camelCase` | `createPersona()`, `getUserById()` |
| Fichiers de classe | Identique à la classe | `PersonasController.php` |
| Variables PHP | `camelCase` | `$personaId`, `$creatorName` |
| Routes (`?action=`) | `kebab-case` | `create-persona`, `list-users` |
| Tables SQL | `snake_case` pluriel (sauf `company`) | `personas`, `users`, `operations`, `criteria_types` |
| Colonnes SQL | `snake_case` préfixé | `persona_firstname`, `id_persona` |
