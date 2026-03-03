# Réinitialisation de mot de passe

## Vue d'ensemble

Ce document décrit le fonctionnement de la réinitialisation de mot de passe dans AdLedger Persona et les paramètres configurables.

---

## Configuration requise

### Configuration SMTP (production)

Pour que les emails de réinitialisation soient effectivement envoyés, le SMTP doit être activé dans `config/config.ini` :

```ini
[smtp]
enabled    = true
host       = smtp.votre-serveur.com
port       = 587
auth       = true
username   = votre-email@domaine.com
password   = votre-mot-de-passe
secure     = tls
from_email = no-reply@votredomaine.com
from_name  = AdLedger Persona
```

> En développement local sans SMTP configuré, aucun email ne sera envoyé mais le système ne retournera pas d'erreur.

**Consultez le guide complet :** [CONFIGURATION_SMTP_PRODUCTION.md](CONFIGURATION_SMTP_PRODUCTION.md)

---

## Fonctionnement

### Processus de réinitialisation

1. **Demande de réinitialisation** (`forgot-password`)
   - L'utilisateur entre son nom d'utilisateur ou email
   - Le système vérifie si l'utilisateur existe
   - Génère un token sécurisé (64 caractères)
   - Enregistre le token avec une expiration de 1 heure
   - Envoie un email avec le lien de réinitialisation

2. **Réinitialisation du mot de passe** (`reset-password`)
   - L'utilisateur clique sur le lien reçu par email
   - Le système vérifie la validité et l'expiration du token
   - Affiche le formulaire de nouveau mot de passe
   - Met à jour le mot de passe et supprime le token

### Routes

| URL | Description |
| --- | --- |
| `index.php?action=forgot-password` | Formulaire de demande de réinitialisation |
| `index.php?action=reset-password&token=XXX` | Formulaire de saisie du nouveau mot de passe |

---

## Sécurité

1. **Token sécurisé** : Génération avec `random_bytes(32)` (64 caractères en hexadécimal)
2. **Expiration** : Les tokens expirent après 1 heure
3. **Usage unique** : Le token est supprimé après utilisation
4. **Validation côté serveur** : Vérification de l'expiration à chaque utilisation
5. **Hachage du mot de passe** : Utilisation de `password_hash()` avec `PASSWORD_DEFAULT`
6. **Protection contre l'énumération** : Le message de succès est affiché même si l'utilisateur n'existe pas

---

## Personnalisation

### Modifier la durée d'expiration du token

L'expiration est calculée **en SQL** dans `app/Models/Users.php`, méthode `setResetToken()` :

```sql
reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR)
```

Modifier `1 HOUR` par la durée souhaitée, par exemple `30 MINUTE` ou `2 HOUR`.

### Modifier les validations du mot de passe

Dans `app/Controllers/UsersController.php`, méthode `resetPassword()`. Actuellement, la seule règle appliquée est une **longueur minimale de 8 caractères**. Des règles de complexité supplémentaires (majuscule, chiffre, caractère spécial...) peuvent y être ajoutées.

### Modifier le template d'email

Le template HTML de l'email de réinitialisation se trouve dans `app/templates/reset_password_email.html`.
