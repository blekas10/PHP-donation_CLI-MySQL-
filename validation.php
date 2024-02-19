<?php
require_once 'dbConnect.php';

/**
 * @param mixed
 * @param string
 * @return bool|string
 */

function validateIdExists($id, $tableName) {
    $conn = dbConnect();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM `$tableName` WHERE id = :id");

    $stmt->execute([':id' => $id]);

    $count = $stmt->fetchColumn();

    if ($count > 0) {
        return true;
    } else {
        return "$tableName ID does not exist.";
    }
}

function validateCharityId($id) {
    if (!is_numeric($id)) {
        return "Charity ID must be a number.";
    }
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT COUNT(*) FROM charities WHERE id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        return "Charity ID already exists.";
    }
    return true;
}

function validateDonationId($donationId) {
    $conn = dbConnect();

    if (!is_numeric($donationId)) {
        return "Donation ID must be a numeric value.";
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM donations WHERE id = ?");
    $stmt->execute([$donationId]);
    if ($stmt->fetchColumn() > 0) {
        return "Donation ID already exists.";
    }


    return true;
}

function validateEmailSyntax($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }
    return true;
}

function validateName($name) {
    if (trim($name) === '') {
        return "Name cannot be empty.";
    }
    if (!preg_match("/^[\p{L} '-]+$/u", $name)) {
        return "Name must contain only letters, spaces, hyphens, and apostrophes.";
    }
    return true;
}

function validateAmount($amount) {
    if (!is_numeric($amount) || $amount <= 0) {
        return "Amount must be a positive number.";
    }
    return true;
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    if ($d && $d->format('Y-m-d') === $date) {
        return true;
    }
    return "Date must be in the format YYYY-MM-DD.";
}

function validateTime($time) {
    $t = DateTime::createFromFormat('H:i', $time);
    if ($t && $t->format('H:i') === $time) {
        return true;
    }
    return "Time must be in the format HH:MM.";
}
?>