# Documentation - Avatar Randomizer (DiceBear)

## Table des matières

1. [Introduction](#introduction)
2. [Architecture du système](#architecture-du-système)
3. [Les classes](#les-classes)
4. [Configuration des avatars](#configuration-des-avatars)
5. [Options disponibles](#options-disponibles)
6. [Personnalisation](#personnalisation)
7. [Exemples d'utilisation](#exemples-dutilisation)

---

## Introduction

Ce système génère automatiquement des avatars aléatoires personnalisés en fonction du genre (homme, femme, autre) en utilisant l'API **DiceBear** avec le style **avataaars**.

**Lien de référence DiceBear :** <https://www.dicebear.com/styles/avataaars/>

### Fonctionnement général

1. Un **seed** (identifiant unique) est utilisé pour générer l'avatar
2. Des **options aléatoires** sont sélectionnées selon le genre
3. Une **URL** est construite pour récupérer l'avatar SVG depuis l'API DiceBear

---

## Architecture du système

Le système est composé de **3 classes principales** :

```text
app/Services/Avatar/
├── AvatarConfig.php      # Configuration des options d'avatar
├── AvatarRandomizer.php  # Génération aléatoire d'options
└── AvatarBuilder.php     # Construction de l'URL finale
```

---

## Les classes

### 1. `AvatarConfig.php`

**Rôle :** Définit toutes les options disponibles pour la génération d'avatars.

**Propriétés statiques :**

- `$common` : Options communes à tous les genres
- `$male` : Options spécifiques aux hommes
- `$female` : Options spécifiques aux femmes
- `$neutral` : Options spécifiques au genre "autre"

### 2. `AvatarRandomizer.php`

**Rôle :** Sélectionne aléatoirement des options en fonction du genre.

**Méthodes principales :**

- `__construct(string $gender)` : Initialise avec un genre (homme, femme, autre)
- `generate(): array` : Génère et retourne un tableau d'options aléatoires

**Logique :**

1. Fusionne les options communes avec les options spécifiques au genre
2. Pour chaque option qui est un tableau → sélectionne une valeur aléatoire
3. Pour chaque option scalaire → garde la valeur telle quelle

### 3. `AvatarBuilder.php`

**Rôle :** Construit l'URL finale de l'avatar pour l'API DiceBear.

**Méthodes principales :**

- `__construct(string $seed, array $options, string $style = 'avataaars')`
- `buildUrl(): string` : Retourne l'URL complète de l'avatar

**Format de l'URL générée :**

```text
https://api.dicebear.com/9.x/avataaars/svg?seed=john.doe&option1=value1&option2=value2...
```

---

## Configuration des avatars

### Options communes (tous genres)

Définies dans `AvatarConfig::$common` :

| Option | Type | Valeurs | Description |
| -------- | ------ | --------- | ------------- |
| `size` | Scalaire | `128` | Taille de l'avatar en pixels |
| `backgroundColor` | Scalaire | `f9edff` | Couleur de fond (hex sans #) |
| `radius` | Scalaire | `50` | Arrondi des coins (0-50) |
| `accessoriesProbability` | Scalaire | `30` | Probabilité d'avoir des accessoires (0-100) |
| `mouth` | Tableau | Aléatoire parmi 4 options | Type de bouche |
| `eyes` | Tableau | Aléatoire parmi 8 options | Type d'yeux |
| `skinColor` | Tableau | Aléatoire parmi 7 couleurs | Couleur de peau |

#### Valeurs possibles pour `mouth`

- `default` : Bouche par défaut
- `smile` : Sourire
- `serious` : Sérieux
- `twinkle` : Pétillant

#### Valeurs possibles pour `eyes`

- `closed` : Yeux fermés
- `default` : Yeux par défaut
- `eyeRoll` : Yeux levés au ciel
- `happy` : Yeux heureux
- `side` : Regard de côté
- `squint` : Yeux plissés
- `wink` : Clin d'œil
- `winkWacky` : Clin d'œil excentrique

#### Valeurs possibles pour `skinColor` (codes hex)

- `614335` : Peau très foncée
- `ae5d29` : Peau foncée
- `d08b5b` : Peau brune
- `edb98a` : Peau claire
- `f8d25c` : Peau moyenne
- `fd9841` : Peau orangée
- `ffdbb4` : Peau très claire

---

### Options spécifiques aux hommes

Définies dans `AvatarConfig::$male` :

| Option | Type | Valeurs | Description |
| -------- | ------ | --------- | ------------- |
| `top` | Tableau | 14 styles de coiffure | Cheveux/chapeau |
| `facialHair` | Tableau | 5 styles de barbe/moustache | Pilosité faciale |
| `facialHairProbability` | Scalaire | `50` | Probabilité d'avoir de la barbe (0-100) |
| `accessories` | Tableau | 5 types d'accessoires | Lunettes |

#### Valeurs possibles pour `top` (hommes)

- `dreads01`, `dreads02` : Dreadlocks
- `frizzle` : Cheveux frisés
- `fro` : Afro
- `hat` : Chapeau
- `shaggy` : Cheveux ébouriffés
- `shaggyMullet` : Mulet ébouriffé
- `shortCurly` : Court bouclé
- `shortFlat` : Court plat
- `shortRound` : Court arrondi
- `shortWaved` : Court ondulé
- `sides` : Rasé sur les côtés
- `theCaesar` : Coupe César
- `theCaesarAndSidePart` : César avec raie

#### Valeurs possibles pour `facialHair`

- `beardLight` : Barbe légère
- `beardMajestic` : Barbe majestueuse
- `beardMedium` : Barbe moyenne
- `moustacheFancy` : Moustache fantaisie
- `moustacheMagnum` : Moustache Magnum

#### Valeurs possibles pour `accessories` (hommes)

- `prescription01`, `prescription02` : Lunettes de vue
- `round` : Lunettes rondes
- `sunglasses` : Lunettes de soleil
- `wayfarers` : Lunettes Wayfarer

---

### Options spécifiques aux femmes

Définies dans `AvatarConfig::$female` :

| Option | Type | Valeurs | Description |
| -------- | ------ | --------- | ------------- |
| `top` | Tableau | 14 styles de coiffure | Cheveux |
| `facialHairProbability` | Scalaire | `0` | Pas de barbe pour les femmes |
| `accessories` | Tableau | 6 types d'accessoires | Lunettes |

#### Valeurs possibles pour `top` (femmes)

- `bigHair` : Grosses boucles
- `bob` : Coupe au carré
- `bun` : Chignon
- `curly` : Bouclé
- `curvy` : Ondulé
- `dreads` : Dreadlocks
- `frida` : Style Frida (avec fleurs)
- `froBand` : Afro avec bandeau
- `longButNotTooLong` : Long mais pas trop
- `miaWallace` : Style Mia Wallace
- `shavedSides` : Rasé sur les côtés
- `straight01`, `straight02` : Cheveux raides
- `straightAndStrand` : Raides avec mèche

#### Valeurs possibles pour `accessories` (femmes)

- `kurt` : Lunettes Kurt
- `prescription01`, `prescription02` : Lunettes de vue
- `round` : Lunettes rondes
- `sunglasses` : Lunettes de soleil
- `wayfarers` : Lunettes Wayfarer

---

### Options pour genre "autre"

Définies dans `AvatarConfig::$neutral` :

| Option | Type | Valeurs | Description |
| -------- | ------ | --------- | ------------- |
| `facialHairProbability` | Scalaire | `50` | 50% de chance d'avoir de la barbe |
| `eyes` | Tableau | 8 types d'yeux | Types d'yeux (même que common) |
| `accessories` | Tableau | 6 types | Tous les accessoires disponibles |

---

## Options disponibles

### Toutes les options DiceBear (non utilisées actuellement)

Voici d'autres options disponibles dans l'API DiceBear que vous pouvez ajouter selon vos besoins :

#### Options de vêtements (`clothingType`)

- `blazerAndShirt` : Blazer et chemise
- `blazerAndSweater` : Blazer et pull
- `collarAndSweater` : Col et pull
- `graphicShirt` : T-shirt graphique
- `hoodie` : Sweat à capuche
- `overall` : Salopette
- `shirtCrewNeck` : T-shirt col rond
- `shirtScoopNeck` : T-shirt décolleté
- `shirtVNeck` : T-shirt col V

#### Couleur des vêtements (`clothingColor`)

- `black`, `blue01`, `blue02`, `blue03`
- `gray01`, `gray02`, `heather`
- `pastelBlue`, `pastelGreen`, `pastelOrange`, `pastelRed`, `pastelYellow`
- `pink`, `red`, `white`

#### Couleur des cheveux (`hairColor`)

- `auburn` : Auburn
- `black` : Noir
- `blonde` : Blond
- `blondeGolden` : Blond doré
- `brown` : Brun
- `brownDark` : Brun foncé
- `pastelPink` : Rose pastel
- `platinum` : Platine
- `red` : Roux
- `silverGray` : Gris argenté

#### Sourcils (`eyebrows`)

- `angry` : En colère
- `angryNatural` : En colère naturel
- `default` : Par défaut
- `defaultNatural` : Naturel par défaut
- `flatNatural` : Plat naturel
- `frownNatural` : Froncé naturel
- `raisedExcited` : Levés excité
- `raisedExcitedNatural` : Levés excité naturel
- `sadConcerned` : Triste inquiet
- `sadConcernedNatural` : Triste inquiet naturel
- `unibrowNatual` : Monosourcils naturel
- `upDown` : Haut-bas
- `upDownNatural` : Haut-bas naturel

---

## Personnalisation

### Ajouter une nouvelle option

**Exemple :** Ajouter la couleur des cheveux

1. **Modifier `AvatarConfig.php`** :

    ```php
    public static array $common = [
        // ... options existantes ...
        'hairColor' => [
            'auburn',
            'black',
            'blonde',
            'brown',
            'red'
        ]
    ];
    ```

2. Le système sélectionnera automatiquement une couleur aléatoire parmi ces options !

### Modifier une option existante

**Exemple :** Ajouter des nouvelles bouches

```php
public static array $common = [
    'mouth' => [
        'default',
        'smile',
        'serious',
        'twinkle',
        'eating',      // NOUVEAU
        'grimace',     // NOUVEAU
        'sad',         // NOUVEAU
        'scream',      // NOUVEAU
        'tongue',      // NOUVEAU
        'vomit'        // NOUVEAU
    ]
];
```

### Modifier la probabilité d'un attribut

```php
public static array $common = [
    'accessoriesProbability' => 80, // 80% de chance d'avoir des lunettes (au lieu de 30%)
];
```

### Ajouter une option scalaire (non aléatoire)

```php
public static array $common = [
    'flip' => true,  // Miroir horizontal de l'avatar
    'rotate' => 15,  // Rotation en degrés
];
```

---

## Exemples d'utilisation

### Exemple 1 : Générer un avatar pour un homme

```php
<?php
require_once 'app/Services/Avatar/AvatarRandomizer.php';
require_once 'app/Services/Avatar/AvatarBuilder.php';

// 1. Créer le randomizer avec le genre
$randomizer = new AvatarRandomizer('homme');

// 2. Générer les options aléatoires
$options = $randomizer->generate();

// 3. Créer le builder avec un seed unique et les options
$builder = new AvatarBuilder('john.doe.123', $options);

// 4. Obtenir l'URL de l'avatar
$avatarUrl = $builder->buildUrl();

// 5. Afficher l'avatar
echo "<img src='{$avatarUrl}' alt='Avatar'>";
```

**URL générée (exemple) :**

```text
https://api.dicebear.com/9.x/avataaars/svg?seed=john.doe.123&size=128&backgroundColor=f9edff&radius=50&accessoriesProbability=30&mouth=smile&eyes=happy&skinColor=edb98a&top=shortFlat&facialHair=beardMedium&facialHairProbability=50&accessories=sunglasses
```

---

### Exemple 2 : Générer un avatar pour une femme

```php
<?php
$randomizer = new AvatarRandomizer('femme');
$options = $randomizer->generate();
$builder = new AvatarBuilder('jane.smith.456', $options);
$avatarUrl = $builder->buildUrl();

echo "<img src='{$avatarUrl}' alt='Avatar femme'>";
```

---

### Exemple 3 : Générer plusieurs avatars avec le même seed

```php
<?php
// Avec le MÊME seed, on obtient toujours le MÊME avatar
$seed = 'user.unique.id.789';

$randomizer1 = new AvatarRandomizer('homme');
$options1 = $randomizer1->generate();
$builder1 = new AvatarBuilder($seed, $options1);
$avatar1 = $builder1->buildUrl();

// Généré plus tard avec le même seed
$randomizer2 = new AvatarRandomizer('homme');
$options2 = $randomizer2->generate();
$builder2 = new AvatarBuilder($seed, $options2);
$avatar2 = $builder2->buildUrl();

// $avatar1 et $avatar2 seront DIFFÉRENTS car les options sont randomisées
// Pour garantir le MÊME avatar, il faut sauvegarder les options en BD
```

---

### Exemple 4 : Sauvegarder les options en base de données

Pour garantir que l'avatar reste identique, il faut sauvegarder les options :

```php
<?php
// CRÉATION de l'avatar
$randomizer = new AvatarRandomizer('femme');
$options = $randomizer->generate();

// Sauvegarder en BD (format JSON)
$optionsJson = json_encode($options);
// INSERT INTO personas (avatar_options, ...) VALUES ($optionsJson, ...)

// AFFICHAGE de l'avatar plus tard
$savedOptions = json_decode($optionsJson, true);
$builder = new AvatarBuilder('persona.seed.123', $savedOptions);
$avatarUrl = $builder->buildUrl();
```

---

### Exemple 5 : Personnaliser manuellement certaines options

```php
<?php
$randomizer = new AvatarRandomizer('homme');
$options = $randomizer->generate();

// Override: Forcer une bouche souriante
$options['mouth'] = 'smile';

// Override: Forcer des lunettes rondes
$options['accessories'] = 'round';

// Override: Forcer une couleur de peau spécifique
$options['skinColor'] = 'ffdbb4';

$builder = new AvatarBuilder('custom.avatar', $options);
$avatarUrl = $builder->buildUrl();
```

---

### Exemple 6 : Utiliser un style différent

DiceBear propose plusieurs styles. Pour en utiliser un autre :

```php
<?php
$randomizer = new AvatarRandomizer('femme');
$options = $randomizer->generate();

// Utiliser le style "lorelei" au lieu de "avataaars"
$builder = new AvatarBuilder('user.seed', $options, 'lorelei');
$avatarUrl = $builder->buildUrl();
```

**Styles disponibles sur DiceBear :**

- `avataaars` (utilisé actuellement)
- `lorelei`
- `personas`
- `bottts`
- `adventurer`
- `micah`
- etc.

Documentation complète : <https://www.dicebear.com/styles>

---

## Résumé du workflow

```text
1. Utilisateur crée une persona avec un genre
        ↓
2. AvatarRandomizer génère des options aléatoires selon le genre
        ↓
3. Options sont sauvegardées en BD (JSON)
        ↓
4. AvatarBuilder construit l'URL avec le seed + options
        ↓
5. L'avatar SVG est récupéré depuis l'API DiceBear
        ↓
6. L'avatar est affiché dans l'interface
```

---

## Ressources

- **Documentation DiceBear Avataaars :** <https://www.dicebear.com/styles/avataaars/>
- **API DiceBear :** <https://api.dicebear.com/>
- **Tous les styles DiceBear :** <https://www.dicebear.com/styles>
- **Playground DiceBear :** <https://www.dicebear.com/playground> (pour tester visuellement)

---

## Notes importantes

1. **Le seed est important** : Il garantit que l'avatar reste cohérent. Utilisez un identifiant unique (ex: `id_persona`, `email`, etc.)

2. **Les options doivent être sauvegardées** : Sinon, chaque fois que vous régénérez, l'avatar changera (même avec le même seed) car les options sont randomisées.

3. **Format des couleurs** : Les couleurs sont en hexadécimal SANS le `#` (ex: `f9edff` au lieu de `#f9edff`)

4. **Probabilités** : Utilisez des valeurs entre 0 et 100 pour les options `*Probability`

5. **Performance** : Les avatars SVG sont légers et rapides à charger. Pour de meilleures performances, vous pouvez les mettre en cache ou les télécharger localement.

---

**Dernière mise à jour :** Février 2026
