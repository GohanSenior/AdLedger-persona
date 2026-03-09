<?php

class Config
{
    private static $config = null;
    public static function get($section, $key)
    {
        if (self::$config === null) {
            self::$config = parse_ini_file(__DIR__ . '/../config/config.ini', true);
        }
        return self::$config[$section][$key] ?? null;
    }
}

/**
 * Valide une URL de redirection pour éviter les open redirects.
 * N'accepte que les chemins internes commençant par "index.php".
 *
 * @param string|null $url       URL candidate (issue de $_GET['redirect'])
 * @param string      $fallback  URL de secours si la validation échoue
 * @return string
 */
function sanitizeRedirect(?string $url, string $fallback): string
{
    if ($url !== null && preg_match('/^index\.php(\?.*)?$/', $url)) {
        return $url;
    }
    return $fallback;
}
