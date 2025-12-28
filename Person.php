<?php
// Person.php

require_once "Database.php";
require_once "Validator.php";

abstract class Person
{
    protected $conn;
    protected $id;
    protected $first_name;
    protected $last_name;
    protected $phone;
    protected $email;

    public function __construct($first_name = null, $last_name = null, $phone = null, $email = null, $id = null)
    {
        $this->conn = Database::getConnection();
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->phone = $phone;
        $this->email = $email;
        $this->id = $id;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    // Setters
    public function setFirstName($first_name)
    {
        if (Validator::isNotEmpty($first_name)) {
            $this->first_name = Validator::sanitize($first_name);
        }
    }

    public function setLastName($last_name)
    {
        if (Validator::isNotEmpty($last_name)) {
            $this->last_name = Validator::sanitize($last_name);
        }
    }

    public function setPhone($phone)
    {
        if (Validator::isValidPhone($phone)) {
            $this->phone = $phone;
        }
    }

    public function setEmail($email)
    {
        if (Validator::isValidEmail($email)) {
            $this->email = $email;
        }
    }

    abstract public function save();
    abstract public function delete();
    abstract public static function getAll();
    abstract public static function getById($id);
}