<?php

class Companies extends Model
{
    // Récupère toutes les entreprises
    public function getCompanies()
    {
        $sql = "SELECT * FROM company";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Récupère une entreprise par son ID
    public function getCompanyById($id_company)
    {
        $sql = "SELECT * FROM company WHERE id_company = :id_company";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_company' => $id_company]);
        return $stmt->fetch();
    }

    // Récupère le nom de l'entreprise associée à un utilisateur
    public function getCompanyByUserId($id_user)
    {
        $sql = "SELECT c.company_name FROM company c
                JOIN users u ON c.id_company = u.id_company
                WHERE u.id_user = :id_user";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_user' => $id_user]);
        $company = $stmt->fetch();
        return $company ? $company['company_name'] : null;
    }

    // Crée une nouvelle entreprise
    public function createCompany($name, $address, $zipcode, $city, $logo=null)
    {
        $sql = "INSERT INTO company (company_name, company_address, company_zipcode, company_city, logo_url) 
                VALUES (:name, :address, :zipcode, :city, :logo)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'address' => $address,
            'zipcode' => $zipcode,
            'city' => $city,
            'logo' => $logo
        ]);
    }

    // Met à jour une entreprise existante
    public function updateCompany($id_company, $name, $address, $zipcode, $city, $logo=null)
    {
        $sql = "UPDATE company SET 
                    company_name = :name, 
                    company_address = :address, 
                    company_zipcode = :zipcode, 
                    company_city = :city, 
                    logo_url = :logo 
                WHERE id_company = :id_company";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_company' => $id_company,
            'name' => $name,
            'address' => $address,
            'zipcode' => $zipcode,
            'city' => $city,
            'logo' => $logo
        ]);
    }

    // Supprime une entreprise
    public function deleteCompany($id_company)
    {
        $sql = "DELETE FROM company WHERE id_company = :id_company";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id_company' => $id_company]);
    }
}