<?php

require 'connection.php';
$input = json_decode(file_get_contents("php://input"), true);
$fullName = $input['fullName'];
$email = $input['email'];
$phone = $input['phone'];
$password = $input['password'];
$userName = $input['userName'];
$birth = $input['birth'];
$address = $input['address'];
$nin_bvn = $input['nin_bvn'];
$language = $input['language'];
$marital = $input['marital'];
$gender = $input['gender'];

// Validate phone number using the Abstract API
$apiKey = '2242b30a40ad454d8acfe642e0b3487d';
$phoneValidationUrl = "https://phonevalidation.abstractapi.com/v1/?api_key={$apiKey}&phone={$phone}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $phoneValidationUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$data = curl_exec($ch);
curl_close($ch);

$phoneValidationResult = json_decode($data, true);

if ($phoneValidationResult === null) {
    $result = ["message" => "Unable to decode the phone validation API response.", "status" => false];
    echo json_encode($result);
    exit();
}

if (isset($phoneValidationResult['valid'])) {
    if ($phoneValidationResult['valid']) {
        // Check if the email already exists in the database
        $query = "SELECT * FROM bankinfo WHERE email=?";
        $prepare = $dbconnection->prepare($query);
        $prepare->bind_param("s", $email);
        $prepare->execute();
        $existingUser = $prepare->get_result()->fetch_assoc();

        if ($existingUser) {
            echo json_encode(['status' => false, 'message' => 'Email already exists']);
        } else {
            // Insert the new user into the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $mysql = "INSERT INTO `bankinfo` (`fullName`, `email`, `phone`, `password`, `userName`, `birth`, `address`, `nin_bvn`, `language`, `marital`,`gender`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
            $prepare = $dbconnection->prepare($mysql);

            $prepare->bind_param("sssssssssss", $fullName, $email, $phone, $hashedPassword, $userName, $birth, $address, $nin_bvn, $language, $marital, $gender);
            $execute = $prepare->execute();

            if ($execute) {
                $result = ['email' => $email, 'status' => true, 'message' => 'User registered successfully'];
                echo json_encode($result);
            } else {
                $result = ['status' => false, 'message' => 'Error registering user'];
                echo json_encode($result);
            }
        }
    } else {
        // Phone number is not valid
        $result = ['phone' => $phone, "status" => false, "message" => "Phone number is not valid"];
        echo json_encode($result);
        exit();
    }
} else {
    $result = ["message" => "Invalid phone validation API response format", "status" => false];
    echo json_encode($result);
    exit();
}
?>