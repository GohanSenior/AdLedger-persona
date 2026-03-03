# Configuration SMTP pour la Production

Ce document explique comment configurer l'envoi d'emails via SMTP pour l'application AdLedger Persona en environnement de production.

## 📋 Prérequis

1. Un compte SMTP actif (ex: Gmail, SendGrid, Mailgun, Amazon SES, etc.)
2. Les identifiants SMTP fournis par votre hébergeur ou service email
3. Un accès au fichier de configuration `config/config.ini`
4. Un accès au fichier `app/Services/PHPMailer/MailerService.php`

## ⚙️ Étape 1 : Ajouter la configuration SMTP dans config.ini

Ajoutez la section suivante dans le fichier `config/config.ini` :

```ini
[smtp]
; Activer ou désactiver SMTP (true/false)
enabled = true

; Serveur SMTP
host = smtp.example.com

; Port SMTP (465 pour SSL, 587 pour TLS)
port = 465

; Authentification SMTP
auth = true
username = votre-email@example.com
password = votre-mot-de-passe-smtp

; Sécurité (ssl ou tls)
secure = ssl

; Adresse email de l'expéditeur
from_email = no-reply@votredomaine.com
from_name = AdLedger Persona
```

### Exemples de configuration selon les fournisseurs

#### Gmail

```ini
[smtp]
enabled = true
host = smtp.gmail.com
port = 587
auth = true
username = votre-email@gmail.com
password = votre-mot-de-passe-application
secure = tls
from_email = votre-email@gmail.com
from_name = AdLedger Persona
```

**Note :** Pour Gmail, vous devez créer un [mot de passe d'application](https://support.google.com/accounts/answer/185833?hl=fr).

#### SendGrid

```ini
[smtp]
enabled = true
host = smtp.sendgrid.net
port = 587
auth = true
username = apikey
password = votre-clé-api-sendgrid
secure = tls
from_email = no-reply@votredomaine.com
from_name = AdLedger Persona
```

#### Mailgun

```ini
[smtp]
enabled = true
host = smtp.mailgun.org
port = 587
auth = true
username = postmaster@votredomaine.mailgun.org
password = votre-mot-de-passe-mailgun
secure = tls
from_email = no-reply@votredomaine.com
from_name = AdLedger Persona
```

#### Amazon SES

```ini
[smtp]
enabled = true
host = email-smtp.eu-west-1.amazonaws.com
port = 587
auth = true
username = votre-access-key-id
password = votre-secret-access-key
secure = tls
from_email = no-reply@votredomaine.com
from_name = AdLedger Persona
```

## ✅ Étape 2 : Vérification de MailerService.php

Le fichier `app/Services/PHPMailer/MailerService.php` est déjà configuré pour lire la configuration SMTP depuis `config.ini`.

Le constructeur charge automatiquement les paramètres SMTP :

- Vérifie si SMTP est activé via `Config::get('smtp', 'enabled')`
- Charge le host, port, username, password, etc.
- Configure PHPMailer en conséquence

**Aucune modification n'est nécessaire dans ce fichier.**

## 🧪 Étape 3 : Tester l'envoi d'emails

### En développement (sans SMTP)

```ini
[smtp]
enabled = false
```

Les emails ne seront pas réellement envoyés mais le code fonctionnera sans erreur.

### En production (avec SMTP)

```ini
[smtp]
enabled = true
; ... autres paramètres
```

Testez l'envoi d'un email de réinitialisation de mot de passe ou de création de persona.

## 🔍 Debug et résolution de problèmes

### Activer le mode debug

Dans `MailerService.php`, décommentez cette ligne dans le constructeur :

```php
$this->mail->SMTPDebug = 2;
```

Niveaux de debug :

- `0` : Aucun debug
- `1` : Messages du client
- `2` : Messages client et serveur
- `3` : Plus détaillé
- `4` : Très détaillé avec les commandes bas niveau

### Erreurs courantes

#### "SMTP Error: Could not authenticate"

- Vérifiez votre username et password
- Pour Gmail, utilisez un mot de passe d'application
- Vérifiez que l'authentification à deux facteurs n'est pas active (ou utilisez un mot de passe d'application)

#### "SMTP Error: Could not connect to SMTP host"

- Vérifiez le host et le port
- Vérifiez que votre hébergeur autorise les connexions SMTP sortantes
- Vérifiez le firewall du serveur

#### "SMTP Error: The following From address failed"

- L'adresse email d'expéditeur doit être autorisée par votre fournisseur SMTP
- Pour certains fournisseurs, elle doit correspondre à votre domaine

## 🔒 Sécurité

### Protéger les identifiants SMTP

**Important :** Ne commitez JAMAIS le fichier `config.ini` avec vos vrais identifiants SMTP !

1. Ajoutez `config/config.ini` dans `.gitignore` :

```text
config/config.ini
```

1. Créez un fichier template `config/config.ini.example` :

```ini
[database]
host = localhost
port = 3306
dbname = adledger_persona
user = root
password = ""
charset = utf8mb4

[app]
base_url = http://localhost/adledger_persona

[smtp]
enabled = false
host = smtp.example.com
port = 587
auth = true
username = votre-email@example.com
password = votre-mot-de-passe
secure = tls
from_email = no-reply@votredomaine.com
from_name = AdLedger Persona
```

1. En production, copiez le template et remplissez avec les vraies valeurs :

```bash
cp config/config.ini.example config/config.ini
# Puis éditez config/config.ini avec vos vraies valeurs
```

## ✅ Checklist de mise en production

- [ ] Configuration SMTP ajoutée dans `config/config.ini`
- [ ] Test d'envoi d'email réussi
- [ ] Mode debug désactivé (`SMTPDebug` ligne commentée)
- [ ] Vérification que les emails arrivent bien (pas dans les spams)

**Note :** Les fichiers `.gitignore` et `config.ini.example` sont déjà créés, et `MailerService.php` est déjà configuré.

## 📚 Ressources supplémentaires

- [PHPMailer Documentation](https://github.com/PHPMailer/PHPMailer)
- [Configuration Gmail SMTP](https://support.google.com/mail/answer/7126229)
- [SendGrid Documentation](https://docs.sendgrid.com/)
- [Mailgun Documentation](https://documentation.mailgun.com/en/latest/quickstart-sending.html)
- [Amazon SES Documentation](https://docs.aws.amazon.com/ses/latest/dg/send-email-smtp.html)
