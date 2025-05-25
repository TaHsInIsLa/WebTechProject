<?php
session_start();
$fullname = $_POST['fullname'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$password = $_POST['password']; // keep password for now
$dob = $_POST['dob'];
$country = $_POST['country'];
$opinion = $_POST['opinion'];
$terms = isset($_POST['terms']) ? 1 : 0;

$_SESSION['registration_data'] = [
    'fullname' => $fullname,
    'gender' => $gender,
    'email' => $email,
    'password' => $password,
    'dob' => $dob,
    'country' => $country,
    'opinion' => $opinion,
    'terms' => $terms
];
header("Location: details.php");
exit();
