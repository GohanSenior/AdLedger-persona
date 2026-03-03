# Documentation - DataTables

## Table des matières

- [Documentation - DataTables](#documentation---datatables)
  - [Table des matières](#table-des-matières)
  - [Introduction](#introduction)
  - [Fichiers impliqués](#fichiers-impliqués)
  - [Intégration dans le gabarit](#intégration-dans-le-gabarit)
    - [CSS — dans le `<head>`](#css--dans-le-head)
    - [JavaScript — avant la fermeture du `</body>`](#javascript--avant-la-fermeture-du-body)
  - [Les tables DataTables](#les-tables-datatables)
  - [Configuration commune](#configuration-commune)
    - [Options détaillées](#options-détaillées)
    - [L'option `dom`](#loption-dom)
    - [Traductions françaises](#traductions-françaises)
  - [Détail de chaque table](#détail-de-chaque-table)
    - [1. `#personasTable`](#1-personastable)
    - [2. `#operationsTable`](#2-operationstable)
    - [3. `#usersTable`](#3-userstable)
  - [Le filtre personnalisé de la liste complète](#le-filtre-personnalisé-de-la-liste-complète)
    - [Fonctionnement](#fonctionnement)
    - [Valeurs possibles du filtre](#valeurs-possibles-du-filtre)
    - [Code du filtre](#code-du-filtre)
  - [Ajouter une nouvelle table DataTables](#ajouter-une-nouvelle-table-datatables)
    - [1. Créer la table HTML dans la vue PHP](#1-créer-la-table-html-dans-la-vue-php)
    - [2. Ajouter l'initialisation dans `script.js`](#2-ajouter-linitialisation-dans-scriptjs)

---

## Introduction

**DataTables** est une librairie JavaScript (basée sur jQuery) qui enrichit les tableaux HTML statiques avec des fonctionnalités avancées :

- **Recherche en temps réel** dans toutes les colonnes
- **Tri** au clic sur les en-têtes de colonnes
- **Pagination** configurable
- **Sélecteur du nombre d'entrées** affichées par page

Dans AdLedger Persona, DataTables est utilisé sur **toutes les vues de liste** afin d'offrir une navigation fluide dans les données (personas, opérations, utilisateurs).

---

## Fichiers impliqués

```text
assets/
├── css/
│   ├── datatables.min.css     # Styles minifiés (production)
│   └── datatables.css         # Styles non minifiés (référence)
├── js/
│   ├── datatables.min.js      # Librairie minifiée (production)
│   └── datatables.js          # Librairie non minifiée (référence)
└── libs/
    └── phpmailer/             # (sans lien avec DataTables)

app/Views/
├── gabarit.php                # Chargement des assets CSS et JS
├── list_personas.php          # Table #personasTable
├── list_operations.php        # Table #operationsTable
├── list_users.php             # Table #usersTable
└── profile_operation.php      # Table #personasTable (personas d'une opération)

assets/js/
└── script.js                  # Initialisation et configuration de chaque table
```

> Les fichiers `.css` et `.js` non minifiés sont conservés pour référence mais **seuls les fichiers `.min`** sont chargés en page.

---

## Intégration dans le gabarit

DataTables dépend de **jQuery**. Les scripts sont chargés dans `app/Views/gabarit.php` dans l'ordre suivant :

### CSS — dans le `<head>`

```html
<link rel="stylesheet" href="assets/css/datatables.min.css">
```

### JavaScript — avant la fermeture du `</body>`

```html
<script src="assets/js/datatables.min.js"></script>  <!-- 1. DataTables (inclut jQuery) -->
<script src="assets/js/bootstrap.bundle.min.js"></script>  <!-- 2. Bootstrap -->
<script src="assets/js/script.js"></script>            <!-- 3. Code applicatif -->
```

> L'ordre est important : `datatables.min.js` doit être chargé **avant** `script.js` car ce dernier appelle `$(document).ready()` et `$.fn.dataTable`.

---

## Les tables DataTables

Le projet utilise actuellement **3 identifiants** de tables DataTables :

| Identifiant HTML | Vue(s) concernée(s) | Données affichées |
| --- | --- | --- |
| `#personasTable` | `list_personas.php`, `profile_operation.php` | Personas (nom, âge, profession, actions) |
| `#operationsTable` | `list_operations.php` | Opérations (nom, actions) |
| `#usersTable` | `list_users.php` | Utilisateurs (nom, email, entreprise, rôle, statut, actions) |

Chaque table est déclarée dans la vue PHP sous la forme :

```html
<table id="personasTable" class="table table-striped table-hover">
    <thead>...</thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>...</tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

---

## Configuration commune

Toutes les tables partagent une base de configuration identique, définie dans `assets/js/script.js` à l'intérieur d'un bloc `$(document).ready()` :

```javascript
$(document).ready(function () {
    if ($("#maTable").length) {
        $("#maTable").DataTable({
            language: { ... },   // Traductions françaises
            order: [[0, "asc"]], // Tri par défaut sur la 1ère colonne
            columnDefs: [ ... ], // Comportements par colonne
            pagingType: "full_numbers",
            pageLength: 10,
            lengthChange: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        });
    }
});
```

### Options détaillées

| Option | Valeur | Description |
| --- | --- | --- |
| `language` | Objet de traduction | Tous les textes de l'interface sont traduits en français |
| `order` | `[[0, "asc"]]` | Tri ascendant sur la colonne 0 au chargement |
| `pagingType` | `"full_numbers"` | Pagination avec numéros de page complets (`<< 1 2 3 >>`) |
| `pageLength` | `10` | 10 lignes affichées par page par défaut |
| `lengthChange` | `true` | L'utilisateur peut modifier le nombre d'entrées affichées |
| `dom` | Voir ci-dessous | Disposition des contrôles autour du tableau |

### L'option `dom`

```h
'<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
```

Cette chaîne contrôle la **disposition des éléments** de l'interface DataTables :

| Caractère | Rôle |
| --- | --- |
| `l` | Sélecteur du nombre d'entrées (length) |
| `f` | Champ de recherche (filter) |
| `r` | Indicateur de traitement (processing) |
| `t` | Le tableau (`table`) |
| `i` | Informations de pagination (info) |
| `p` | Contrôles de pagination (pages) |

La structure `"row"` / `"col-sm-12 col-md-6"` utilise les classes Bootstrap pour placer le sélecteur d'entrées et la barre de recherche **côte à côte** sur les écrans larges, et **empilés** sur mobile.

### Traductions françaises

```javascript
language: {
    search:       "Rechercher :",
    lengthMenu:   "_MENU_ entrées",
    info:         "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
    infoEmpty:    "Affichage de 0 à 0 sur 0 entrées",
    infoFiltered: "(filtré de _MAX_ entrées totales)",
    paginate: {
        first:    "<<",
        last:     ">>",
        next:     ">",
        previous: "<",
    },
    emptyTable:   "Aucune donnée disponible",
    zeroRecords:  "Aucun enregistrement correspondant trouvé",
},
```

---

## Détail de chaque table

### 1. `#personasTable`

**Vues :** `list_personas.php` (mes personas, personas types, tous les personas, personas d'un utilisateur), `profile_operation.php`

**Colonnes (index 0 à 3) :**

| Index | En-tête | Tri activé |
| --- | --- | --- |
| 0 | Nom | Oui |
| 1 | Âge | Oui |
| 2 | Profession | Oui |
| 3 | Actions | **Non** |
| 4 | Type *(colonne cachée)* | — |

**Particularités :**

- La colonne Actions (index `3`) a son tri désactivé via `columnDefs` :

  ```javascript
  { orderable: false, targets: 3 }
  ```

- Une **5e colonne cachée** (index `4`) est présente uniquement sur la vue `list-all-personas`. Elle contient le type de persona (`users`, `mine`, `types`) et est masquée visuellement grâce à la classe PHP `d-none` ainsi qu'à l'option DataTables :
  
  ```javascript
  { visible: false, targets: 4 }
  ```

  Cette colonne est détectée dynamiquement :

  ```javascript
  var hasTypeColumn = $("#personasTable thead th").length > 4;
  if (hasTypeColumn) {
      columnDefs.push({ visible: false, targets: 4 });
  }
  ```

---

### 2. `#operationsTable`

**Vue :** `list_operations.php`

**Colonnes (index 0 à 1) :**

| Index | En-tête | Tri activé |
| --- | --- | --- |
| 0 | Nom | Oui |
| 1 | Actions | **Non** |

**Particularités :**

- La colonne Actions (index `1`) a son tri désactivé :

  ```javascript
  { orderable: false, targets: 1 }
  ```

---

### 3. `#usersTable`

**Vue :** `list_users.php`

**Colonnes (index 0 à 5) :**

| Index | En-tête | Tri activé |
| --- | --- | --- |
| 0 | Nom | Oui |
| 1 | Email | Oui |
| 2 | Entreprise | Oui |
| 3 | Rôle | Oui |
| 4 | Statut | Oui |
| 5 | Actions | **Non** |

**Particularités :**

- La colonne Actions (index `5`) a son tri désactivé :

  ```javascript
  { orderable: false, targets: 5 }
  ```

---

## Le filtre personnalisé de la liste complète

La vue `list-all-personas` (accessible aux administrateurs) affiche **tous les personas** confondus. Un menu déroulant `#personaFilter` permet de filtrer par type de persona.

### Fonctionnement

```text
┌──────────────────────────────────────┐
│  Filtrer par : [ Personas types ▼ ]  │
└──────────────────────────────────────┘
         │
         │  #personaFilter change
         ▼
  $.fn.dataTable.ext.search.push(...)   ← filtre personnalisé enregistré
         │
         │  DataTable().draw()
         ▼
  Lecture de data[4]                    ← valeur de la colonne cachée (Type)
         │
         │  Comparaison avec filterValue
         ▼
  Ligne affichée ou masquée
```

### Valeurs possibles du filtre

| Valeur `filterValue` | Comportement |
| --- | --- |
| `"all"` | Tous les personas affichés |
| `"users"` | Personas appartenant aux autres utilisateurs |
| `"mine"` | Personas appartenant à l'utilisateur connecté |
| `"types"` | Personas de type "personas types" *(sélectionné par défaut)* |

### Code du filtre

```javascript
$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== "personasTable") {
        return true; // Ne filtre pas les autres tables
    }

    var filterValue = $("#personaFilter").val();
    var personaType = data[4]; // Colonne cachée

    if (filterValue === "all") {
        return true;
    }

    return personaType === filterValue;
});

$("#personaFilter").on("change", function () {
    $("#personasTable").DataTable().draw();
});

// Filtre appliqué dès le chargement de la page
$("#personasTable").DataTable().draw();
```

> Le garde `settings.nTable.id !== "personasTable"` est indispensable : les filtres `ext.search` sont **globaux** et s'appliquent à toutes les tables DataTables de la page. Sans ce contrôle, le filtre interférerait si plusieurs tables étaient présentes simultanément.

---

## Ajouter une nouvelle table DataTables

### 1. Créer la table HTML dans la vue PHP

```html
<div class="table-responsive">
    <table id="maNouvellTable" class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">Colonne 1</th>
                <th scope="col">Colonne 2</th>
                <th id="th-actions" scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['champ1'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($item['champ2'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><!-- boutons d'action --></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
```

### 2. Ajouter l'initialisation dans `script.js`

Dans le bloc `$(document).ready(function () { ... })` existant, ajouter :

```javascript
if ($("#maNouvelleTable").length) {
    $("#maNouvelleTable").DataTable({
        language: {
            search:       "Rechercher :",
            lengthMenu:   "_MENU_ entrées",
            info:         "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
            infoEmpty:    "Affichage de 0 à 0 sur 0 entrées",
            infoFiltered: "(filtré de _MAX_ entrées totales)",
            paginate: { first: "<<", last: ">>", next: ">", previous: "<" },
            emptyTable:   "Aucune donnée disponible",
            zeroRecords:  "Aucun enregistrement correspondant trouvé",
        },
        order: [[0, "asc"]],
        columnDefs: [
            { orderable: false, targets: 2 }, // Index de la colonne Actions
        ],
        pagingType: "full_numbers",
        pageLength: 10,
        lengthChange: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
    });
}
```

> Penser à ajuster `targets` dans `columnDefs` selon l'index réel de la colonne Actions dans la nouvelle table (0-indexé).
