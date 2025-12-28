<?php
// Department.php

require_once "Database.php";
require_once "Validator.php";

class Department
{
    private $conn;
    private $id;
    private $nom;
    private $location;

    public function __construct($nom = null, $location = null, $id = null)
    {
        $this->conn = Database::getConnection();
        $this->nom = $nom;
        $this->location = $location;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setNom($nom)
    {
        if (Validator::isNotEmpty($nom)) {
            $this->nom = Validator::sanitize($nom);
        }
    }

    public function setLocation($location)
    {
        if (Validator::isNotEmpty($location)) {
            $this->location = Validator::sanitize($location);
        }
    }

    public function save()
    {
        if ($this->id == null) {
            $qry = "INSERT INTO departements (departement_nom, location) VALUES (?, ?)";
            $stmt = $this->conn->prepare($qry);
            $stmt->execute([$this->nom, $this->location]);
            $this->id = $this->conn->lastInsertId();
            return true;
        } else {
            $qry = "UPDATE departements SET departement_nom=?, location=? WHERE departement_id=?";
            $stmt = $this->conn->prepare($qry);
            return $stmt->execute([$this->nom, $this->location, $this->id]);
        }
    }

    public function delete()
    {
        $qry = "DELETE FROM departements WHERE departement_id = ?";
        $stmt = $this->conn->prepare($qry);
        return $stmt->execute([$this->id]);
    }

    public static function getAll()
    {
        $conn = Database::getConnection();
        $qry = "SELECT * FROM departements";
        $stmt = $conn->query($qry);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $departments = [];
        foreach ($results as $row) {
            $departments[] = new Department($row['departement_nom'], $row['location'], $row['departement_id']);
        }
        return $departments;
    }

    public static function getById($id)
    {
        $conn = Database::getConnection();
        $qry = "SELECT * FROM departements WHERE departement_id = ?";
        $stmt = $conn->prepare($qry);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Department($row['departement_nom'], $row['location'], $row['departement_id']);
        }
        return null;
    }

    public static function getMostPopulated()
    {
        $conn = Database::getConnection();
        $qry = "SELECT d.*, COUNT(m.medecin_id) as count 
                FROM departements d 
                LEFT JOIN medecins m ON d.departement_id = m.departement_id 
                GROUP BY d.departement_id 
                ORDER BY count DESC 
                LIMIT 1";
        $stmt = $conn->query($qry);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Department($row['departement_nom'], $row['location'], $row['departement_id']);
        }
        return null;
    }

    public function __toString()
    {
        return "ID: {$this->id} | {$this->nom} | {$this->location}";
    }
}