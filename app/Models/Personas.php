<?php

class Personas extends Model
{
    // Récupère tous les personas
    public function getPersonas(): array
    {
        $sql = "SELECT * FROM personas";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Récupère les personas d'un utilisateur spécifique
    public function getPersonasByIdUser(int $id_user): array
    {
        $sql = "SELECT * FROM personas WHERE id_user = :id_user AND is_type = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user' => $id_user]);
        return $stmt->fetchAll();
    }

    // Récupère les personas types
    public function getPersonasTypes(): array
    {
        $sql = "SELECT * FROM personas WHERE is_type = 1";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Récupère tous les personas normaux (non-types)
    public function getNormalPersonas(): array
    {
        $sql = "SELECT * FROM personas WHERE is_type = 0";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Récupère les personas d'une opération spécifique
    public function getPersonasByOperationId(int $id_operation): array
    {
        $sql = "SELECT * FROM personas WHERE id_operation = :id_operation";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_operation' => $id_operation]);
        return $stmt->fetchAll();
    }

    // Récupère un persona par son ID
    public function getPersonaById(int $id_persona): array|false
    {
        $sql = "SELECT * FROM personas WHERE id_persona = :id_persona";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_persona' => $id_persona]);
        return $stmt->fetch();
    }

    // Crée un nouveau persona
    public function createPersona(string $firstname, string $lastname, int $age, string $sexe, string $city, string $job, int $id_user, ?int $id_operation = null, ?string $avatar_options = null): bool
    {
        $sql = "INSERT INTO personas (persona_firstname, persona_lastname, persona_age, persona_sexe, persona_city, persona_job, persona_created_at, id_user, id_operation, avatar_options) 
                VALUES (:firstname, :lastname, :age, :sexe, :city, :job, NOW(), :id_user, :id_operation, :avatar_options)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'age' => $age,
            'sexe' => $sexe,
            'city' => $city,
            'job' => $job,
            'id_user' => $id_user,
            'id_operation' => $id_operation,
            'avatar_options' => $avatar_options
        ]);
    }

    // Met à jour un persona existant
    public function updatePersona(int $id_persona, string $firstname, string $lastname, int $age, string $sexe, string $city, string $job, ?int $id_operation = null, ?string $avatar_options = null): bool
    {
        $sql = "UPDATE personas SET 
                    persona_firstname = :firstname, 
                    persona_lastname = :lastname, 
                    persona_age = :age, 
                    persona_sexe = :sexe, 
                    persona_city = :city, 
                    persona_job = :job,
                    id_operation = :id_operation,
                    avatar_options = :avatar_options
                WHERE id_persona = :id_persona";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_persona' => $id_persona,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'age' => $age,
            'sexe' => $sexe,
            'city' => $city,
            'job' => $job,
            'id_operation' => $id_operation,
            'avatar_options' => $avatar_options
        ]);
    }

    // Supprime un persona
    public function deletePersona(int $id_persona): bool
    {
        $sql = "DELETE FROM personas WHERE id_persona = :id_persona";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id_persona' => $id_persona]);
    }

    // Dissocie une opération de tous les personas qui lui sont associés
    public function removeOperationFromPersonas(int $id_operation): bool
    {
        $sql = "UPDATE personas SET id_operation = NULL WHERE id_operation = :id_operation";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id_operation' => $id_operation]);
    }

    // Associe un critère à un persona
    public function associateCriteria(int $id_persona, int $id_criterion): bool
    {
        $sql = "INSERT INTO associer (id_persona, id_criterion) VALUES (:id_persona, :id_criterion)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_persona' => $id_persona,
            'id_criterion' => $id_criterion
        ]);
    }

    // Récupère les critères associés à un persona
    public function getCriteriaByPersona(int $id_persona): array
    {
        $sql = "SELECT c.*, ct.criteria_type_name 
                FROM criteria c 
                INNER JOIN associer a ON c.id_criterion = a.id_criterion 
                INNER JOIN criteria_types ct ON c.id_criteria_type = ct.id_criteria_type
                WHERE a.id_persona = :id_persona";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_persona' => $id_persona]);
        return $stmt->fetchAll();
    }

    // Supprime les critères associés à un persona
    public function removeCriteriaByPersona(int $id_persona): bool
    {
        $sql = "DELETE FROM associer WHERE id_persona = :id_persona";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id_persona' => $id_persona]);
    }

    // Met à jour le statut typed d'un persona
    public function updateTypedStatus(int $id_persona, int $typedStatus): bool
    {
        $sql = "UPDATE personas SET typed = :typedStatus WHERE id_persona = :id_persona";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_persona' => $id_persona,
            'typedStatus' => $typedStatus
        ]);
    }

    // créer un persona type à partir d'un persona existant
    public function createPersonaTypeFromExisting(int $id_persona, int $id_user_admin): int|false
    {
        // Récupérer les données du persona existant
        $persona = $this->getPersonaById($id_persona);
        if (!$persona) {
            return false; // Persona non trouvé
        }

        // Récupérer les critères associés au persona original
        $personaCriteria = $this->getCriteriaByPersona($id_persona);

        // Créer un nouveau persona type avec les mêmes données
        $sql = "INSERT INTO personas (persona_firstname, persona_lastname, persona_age, persona_sexe, persona_city, persona_job, persona_created_at, id_user, avatar_options, is_type, id_persona_original) 
                VALUES (:firstname, :lastname, :age, :sexe, :city, :job, NOW(), :id_user, :avatar_options, 1, :id_persona_original)";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            'firstname' => $persona['persona_firstname'],
            'lastname' => $persona['persona_lastname'],
            'age' => $persona['persona_age'],
            'sexe' => $persona['persona_sexe'],
            'city' => $persona['persona_city'],
            'job' => $persona['persona_job'],
            'id_user' => $id_user_admin,
            'avatar_options' => $persona['avatar_options'],
            'id_persona_original' => $id_persona
        ]);

        if ($result) {
            $newPersonaTypeId = $this->pdo->lastInsertId();
            
            // Associer les critères au nouveau persona type
            if (!empty($personaCriteria)) {
                foreach ($personaCriteria as $criterion) {
                    $this->associateCriteria($newPersonaTypeId, $criterion['id_criterion']);
                }
            }
            
            return $newPersonaTypeId; // Retourne l'ID du nouveau persona type créé
        } else {
            return false; // Échec de la création
        }
    }

    // Créer un persona normal à partir d'un persona type
    public function createNormalPersonaFromType(int $id_persona_type, int $id_user): int|false
    {
        // Récupérer les données du persona type
        $personaType = $this->getPersonaById($id_persona_type);
        if (!$personaType || $personaType['is_type'] != 1) {
            return false; // Persona type non trouvé ou n'est pas un type
        }

        // Récupérer les critères associés au persona type
        $personaCriteria = $this->getCriteriaByPersona($id_persona_type);

        // Créer un nouveau persona normal avec les mêmes données
        $sql = "INSERT INTO personas (persona_firstname, persona_lastname, persona_age, persona_sexe, persona_city, persona_job, persona_created_at, id_user, id_operation, avatar_options, is_type, typed) 
                VALUES (:firstname, :lastname, :age, :sexe, :city, :job, NOW(), :id_user, :id_operation, :avatar_options, 0, 1)";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            'firstname' => $personaType['persona_firstname'],
            'lastname' => $personaType['persona_lastname'],
            'age' => $personaType['persona_age'],
            'sexe' => $personaType['persona_sexe'],
            'city' => $personaType['persona_city'],
            'job' => $personaType['persona_job'],
            'id_user' => $id_user,
            'id_operation' => $personaType['id_operation'],
            'avatar_options' => $personaType['avatar_options']
        ]);

        if ($result) {
            $newPersonaId = $this->pdo->lastInsertId();
            
            // Associer les critères au nouveau persona
            if (!empty($personaCriteria)) {
                foreach ($personaCriteria as $criterion) {
                    $this->associateCriteria($newPersonaId, $criterion['id_criterion']);
                }
            }
            
            return $newPersonaId; // Retourne l'ID du nouveau persona créé
        } else {
            return false; // Échec de la création
        }
    }
}
