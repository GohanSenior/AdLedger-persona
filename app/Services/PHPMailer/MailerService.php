<?php

require_once __DIR__ . '/src/PHPMailer.php';
require_once __DIR__ . '/src/Exception.php';
require_once __DIR__ . '/src/SMTP.php';
require_once __DIR__ . '/../../Config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerService
{
    private PHPMailer $mail;
    private string $fromEmail;
    private string $fromName;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->CharSet = 'utf-8';
        ini_set('default_charset', 'UTF-8');
        
        // Définir la langue des messages d'erreur en français
        $this->mail->setLanguage('fr', __DIR__ . '/language/');

        // Charger la configuration SMTP depuis config.ini
        $smtpEnabled = Config::get('smtp', 'enabled') === 'true';
        $this->fromEmail = Config::get('smtp', 'from_email') ?? 'no-reply@adledger.com';
        $this->fromName = Config::get('smtp', 'from_name') ?? 'AdLedger Persona';

        // Configuration SMTP si activée
        if ($smtpEnabled) {
            $this->mail->isSMTP();
            $this->mail->Host = Config::get('smtp', 'host');
            $this->mail->SMTPAuth = Config::get('smtp', 'auth') === 'true';
            $this->mail->Username = Config::get('smtp', 'username');
            $this->mail->Password = Config::get('smtp', 'password');
            
            $secure = Config::get('smtp', 'secure');
            $this->mail->SMTPSecure = $secure === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = (int) Config::get('smtp', 'port');
            
            // Activer le debug en développement (à commenter en production)
            // $this->mail->SMTPDebug = 2;
        }

        $this->mail->setFrom($this->fromEmail, $this->fromName);
        $this->mail->addReplyTo($this->fromEmail, $this->fromName);
    }

    /**
     * Récupère un template HTML depuis un fichier local
     */
    /**
     * Remplace les variables dans un template
     */
    private function replaceVariables(string $template, array $variables): string
    {
        $body = $template;
        foreach ($variables as $key => $value) {
            $body = str_replace('%%' . $key . '%%', $value, $body);
        }
        return $body;
    }

    /**
     * Envoie un email simple
     */
    public function send(string $to, string $subject, string $body, string $toName = ''): bool
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to, $toName);
            $this->mail->Subject = $subject;
            $this->mail->MsgHTML($body);
            $this->mail->AltBody = strip_tags($body);

            return $this->mail->send();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Envoie un email avec un template
     */
    public function sendWithTemplate(
        string $to,
        string $toName,
        string $subject,
        string $templatePath,
        array $variables
    ): bool {
        try {
            // Récupérer le template
            if (!file_exists($templatePath)) {
                return false;
            }
            $template = file_get_contents($templatePath);
            if ($template === false) {
                return false;
            }

            // Remplacer les variables
            $body = $this->replaceVariables($template, $variables);

            // Envoyer l'email
            return $this->send($to, $subject, $body, $toName);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Envoie un email de confirmation de création de persona
     */
    public function sendPersonaCreationEmail(
        string $userEmail,
        string $userName,
        string $personaName,
        int $personaId
    ): bool {
        $subject = 'Nouveau persona créé avec succès';
        
        // URL vers le profil du persona
        $baseUrl = Config::get('app', 'base_url');
        $personaUrl = $baseUrl . '/index.php?action=view-persona&id=' . $personaId;

        // Variables pour le template
        $variables = [
            'title' => 'Nouveau Persona Créé',
            'message' => "Bonjour $userName,<br><br>Un nouveau persona <strong>$personaName</strong> a été créé avec succès !<br><br>Vous pouvez consulter son profil en cliquant sur le bouton ci-dessous.",
            'lienboutonprincipal' => $personaUrl,
            'nom_persona' => $personaName,
            'nom_utilisateur' => $userName
        ];

        // Chemin vers votre template (à adapter selon votre structure)
        $templatePath = __DIR__ . '/../../templates/persona_creation.html';

        // Si vous n'avez pas encore de template, utiliser un email simple
        if (!file_exists($templatePath)) {
            $body = "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <h2>Nouveau Persona Créé</h2>
                    <p>Bonjour $userName,</p>
                    <p>Un nouveau persona <strong>$personaName</strong> a été créé avec succès !</p>
                    <p><a href='$personaUrl' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Voir le profil</a></p>
                </body>
                </html>
            ";
            return $this->send($userEmail, $subject, $body, $userName);
        }

        return $this->sendWithTemplate($userEmail, $userName, $subject, $templatePath, $variables);
    }

    /**
     * Récupère le dernier message d'erreur
     */
    public function getErrorInfo(): string
    {
        return $this->mail->ErrorInfo;
    }
}
