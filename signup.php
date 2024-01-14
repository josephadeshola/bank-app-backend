<?php
require 'connection.php';
require 'header.php';

$input = json_decode(file_get_contents("php://input"), true);
$fullName = $input['fullName'] ?? '';
$email = $input['email'] ?? '';
$phone = $input['phone'] ?? '';
$password = $input['password'] ?? '';
$userName = $input['userName'] ?? '';
$birth = $input['birth'] ?? '';
$address = $input['address'] ?? '';
$nin_bvn = $input['nin_bvn'] ?? '';
$language = $input['language'] ?? '';
$marital = $input['marital'] ?? '';
echo json_encode($marital) ?? '';

$query = "SELECT * FROM bankinfo WHERE email=?";
$prepare = $dbconnection->prepare($query);
$prepare->bind_param("s", $email);
$prepare->execute();
$existingUser = $prepare->get_result()->fetch_assoc();

if ($existingUser) {
    echo json_encode(array('success' => false, 'message' => 'Email already exists'));
} else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $mysql = "INSERT INTO `bankinfo`(`fullName`, `email`, `phone`, `password`, `userName`, `birth`, `address`, `nin_bvn`, `language`, `marital`) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $prepare = $dbconnection->prepare($mysql);

    $prepare->bind_param("ssssssssss", $fullName, $email, $phone, $hashedPassword, $userName, $birth, $address, $nin_bvn, $language, $marital);
    $execute = $prepare->execute();

    if ($execute) {
        echo json_encode(array('success' => true, 'message' => 'User registered successfully'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error registering user', 'error' => $prepare->error));
    }
}


?> 
