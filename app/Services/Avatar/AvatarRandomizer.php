<?php

class AvatarRandomizer
{
    private string $gender;

    public function __construct(string $gender)
    {
        $this->gender = strtolower($gender);
    }

    /**
     * Sélectionne une valeur aléatoire dans un tableau ou retourne la valeur elle-même si ce n'est pas un tableau
     */
    private function pickRandom(array $values): ?string
    {
        return empty($values) ? null : $values[array_rand($values)];
    }

    /**
     * Génère une configuration d'avatar aléatoire en fonction du genre et des options communes
     */
    public function generate(): array
    {
        // Sélectionner les options spécifiques au genre
        switch ($this->gender) {
            case 'homme':
                $genderSpecific = AvatarConfig::$male;
                break;
            case 'femme':
                $genderSpecific = AvatarConfig::$female;
                break;
            case 'autre':
                $genderSpecific = AvatarConfig::$neutral;
                break;
            default:
                $genderSpecific = AvatarConfig::$neutral;
                break;
        }

        // Fusionner les options communes et spécifiques (les spécifiques écrasent les communes)
        $allOptions = array_merge(AvatarConfig::$common, $genderSpecific);

        // Traiter toutes les options : randomiser les tableaux, garder les scalaires
        $options = [];
        foreach ($allOptions as $key => $values) {
            if (!is_array($values)) {
                // Valeur scalaire : utiliser directement
                $options[$key] = $values;
            } else {
                // Tableau : choisir une valeur aléatoire
                $value = $this->pickRandom($values);
                if ($value !== null) {
                    $options[$key] = $value;
                }
            }
        }

        return $options;
    }
}
