<?php
// Patient.php

require_once "Person.php";

class Patient extends Person
{
    private $date_naissance;
    private $sexe;
    private $medecin_id;

    public function __construct($first_name = null, $last_name = null, $phone = null, $email = null, $date_naissance = null, $sexe = null, $medecin_id = null, $id = null)
    {
        parent::__construct($first_name, $last_name, $phone, $email, $id);
        $this->date_naissance = $date_naissance;
        $this->sexe = $sexe;
        $this->medecin_id = $medecin_id;
    }

    public function getDateNaissance()
    {
        return $this->date_naissance;
    }

    public function getSexe()
    {
        return $this->sexe;
    }

    public function getMedecinId()
    {
        return $this->medecin_id;
    }

    public function setDateNaissance($date)
    {
        if (Validator::isValidDate($date)) {
            $this->date_naissance = $date;
        }
    }

    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    }

    public function setMedecinId($id)
    {
        $this->medecin_id = $id;
    }

    public function save()
    {
        $full_name = $this->getFullName();

        if ($this->id == null) {
            $qry = "INSERT INTO patients (patient_nom, email, date_naissance, sexe, medecin_id) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($qry);
            $stmt->execute([$full_name, $this->email, $this->date_naissance, $this->sexe, $this->medecin_id]);
            $this->id = $this->conn->lastInsertId();
            return true;
        } else {
            $qry = "UPDATE patients SET patient_nom=?, email=?, date_naissance=?, sexe=?, medecin_id=? 
                    WHERE patient_id=?";
            $stmt = $this->conn->prepare($qry);
            return $stmt->execute([$full_name, $this->email, $this->date_naissance, $this->sexe, $this->medecin_id, $this->id]);
        }
    }

    public function delete()
    {
        $qry = "DELETE FROM patients WHERE patient_id = ?";
        $stmt = $this->conn->prepare($qry);
        return $stmt->execute([$this->id]);
    }

    public static function getAll()
    {
        $conn = Database::getConnection();
        $qry = "SELECT * FROM patients";
        $stmt = $conn->query($qry);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $patients = [];
        foreach ($results as $row) {
            $names = explode(" ", $row['patient_nom'], 2);
            $patients[] = new Patient(
                $names[0],
                $names[1] ?? '',
                '',
                $row['email'],
                $row['date_naissance'],
                $row['sexe'],
                $row['medecin_id'],
                $row['patient_id']
            );
        }
        return $patients;
    }

    public static function getById($id)
    {
        $conn = Database::getConnection();
        $qry = "SELECT * FROM patients WHERE patient_id = ?";
        $stmt = $conn->prepare($qry);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $names = explode(" ", $row['patient_nom'], 2);
            return new Patient(
                $names[0],
                $names[1] ?? '',
                '',
                $row['email'],
                $row['date_naissance'],
                $row['sexe'],
                $row['medecin_id'],
                $row['patient_id']
            );
        }
        return null;
    }

    public static function search($name)
    {
        $conn = Database::getConnection();
        $qry = "SELECT * FROM patients WHERE patient_nom LIKE ?";
        $stmt = $conn->prepare($qry);
        $stmt->execute(["%$name%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $patients = [];
        foreach ($results as $row) {
            $names = explode(" ", $row['patient_nom'], 2);
            $patients[] = new Patient(
                $names[0],
                $names[1] ?? '',
                '',
                $row['email'],
                $row['date_naissance'],
                $row['sexe'],
                $row['medecin_id'],
                $row['patient_id']
            );
        }
        return $patients;
    }

    public static function calculateAverageAge()
    {
        $conn = Database::getConnection();
        $qry = "SELECT AVG(YEAR(CURDATE()) - YEAR(date_naissance)) as avg_age FROM patients";
        $stmt = $conn->query($qry);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return round($result['avg_age'], 2);
    }

    public static function countByDepartment()
    {
        $conn = Database::getConnection();
        $qry = "SELECT d.departement_nom, COUNT(p.patient_id) as count 
                FROM patients p 
                JOIN medecins m ON p.medecin_id = m.medecin_id 
                JOIN departements d ON m.departement_id = d.departement_id 
                GROUP BY d.departement_id";
        $stmt = $conn->query($qry);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function __toString()
    {
        return "ID: {$this->id} | {$this->getFullName()} | {$this->email} | {$this->date_naissance} | {$this->sexe}";
    }
}