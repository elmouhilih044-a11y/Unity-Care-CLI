<?php
// Doctor.php

require_once "Person.php";

class Doctor extends Person
{
    private $specialite;
    private $departement_id;

    public function __construct($first_name = null, $last_name = null, $phone = null, $email = null, $specialite = null, $departement_id = null, $id = null)
    {
        parent::__construct($first_name, $last_name, $phone, $email, $id);
        $this->specialite = $specialite;
        $this->departement_id = $departement_id;
    }

    public function getSpecialite()
    {
        return $this->specialite;
    }

    public function getDepartementId()
    {
        return $this->departement_id;
    }

    public function setSpecialite($specialite)
    {
        if (Validator::isNotEmpty($specialite)) {
            $this->specialite = Validator::sanitize($specialite);
        }
    }

    public function setDepartementId($id)
    {
        $this->departement_id = $id;
    }

    public function save()
    {
        $full_name = $this->getFullName();

        if ($this->id == null) {
            $qry = "INSERT INTO medecins (medecin_nom, specialite, departement_id) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($qry);
            $stmt->execute([$full_name, $this->specialite, $this->departement_id]);
            $this->id = $this->conn->lastInsertId();
            return true;
        } else {
            $qry = "UPDATE medecins SET medecin_nom=?, specialite=?, departement_id=? WHERE medecin_id=?";
            $stmt = $this->conn->prepare($qry);
            return $stmt->execute([$full_name, $this->specialite, $this->departement_id, $this->id]);
        }
    }

    public function delete()
    {
        $qry = "DELETE FROM medecins WHERE medecin_id = ?";
        $stmt = $this->conn->prepare($qry);
        return $stmt->execute([$this->id]);
    }

    public static function getAll()
    {
        $conn = Database::getConnection();
        $qry = "SELECT * FROM medecins";
        $stmt = $conn->query($qry);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $doctors = [];
        foreach ($results as $row) {
            $names = explode(" ", $row['medecin_nom'], 2);
            $doctors[] = new Doctor(
                $names[0],
                $names[1] ?? '',
                '',
                '',
                $row['specialite'],
                $row['departement_id'],
                $row['medecin_id']
            );
        }
        return $doctors;
    }

    public static function getById($id)
    {
        $conn = Database::getConnection();
        $qry = "SELECT * FROM medecins WHERE medecin_id = ?";
        $stmt = $conn->prepare($qry);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $names = explode(" ", $row['medecin_nom'], 2);
            return new Doctor(
                $names[0],
                $names[1] ?? '',
                '',
                '',
                $row['specialite'],
                $row['departement_id'],
                $row['medecin_id']
            );
        }
        return null;
    }

    public static function search($name)
    {
        $conn = Database::getConnection();
        $qry = "SELECT * FROM medecins WHERE medecin_nom LIKE ?";
        $stmt = $conn->prepare($qry);
        $stmt->execute(["%$name%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $doctors = [];
        foreach ($results as $row) {
            $names = explode(" ", $row['medecin_nom'], 2);
            $doctors[] = new Doctor(
                $names[0],
                $names[1] ?? '',
                '',
                '',
                $row['specialite'],
                $row['departement_id'],
                $row['medecin_id']
            );
        }
        return $doctors;
    }

    public function __toString()
    {
        return "ID: {$this->id} | Dr. {$this->getFullName()} | {$this->specialite} | Dept: {$this->departement_id}";
    }
}