<?php
// Validator.php

class Validator
{
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isValidPhone($phone)
    {
        return preg_match('/^0[5-7][0-9]{8}$/', $phone);
    }

    public static function isValidDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    public static function isNotEmpty($input)
    {
        return trim($input) !== '';
    }

    public static function sanitize($input)
    {
        return htmlspecialchars(trim($input));
    }
}