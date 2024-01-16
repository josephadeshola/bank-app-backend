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
$gender= $input['gender'];

$query = "SELECT * FROM bankinfo WHERE email=?";
$prepare = $dbconnection->prepare($query);
$prepare->bind_param("s", $email);
$prepare->execute();
$existingUser = $prepare->get_result()->fetch_assoc();

if ($existingUser) {
    echo json_encode(array('status' => false, 'message' => 'Email already exists'));
} else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $mysql = "INSERT INTO `bankinfo` (`fullName`, `email`, `phone`, `password`, `userName`, `birth`, `address`, `nin_bvn`, `language`, `marital`,`gender`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $prepare = $dbconnection->prepare($mysql);

    $prepare->bind_param("sssssssssss", $fullName, $email, $phone, $hashedPassword, $userName, $birth, $address, $nin_bvn, $language,$marital,$gender);
    $execute= $prepare->execute();

    if ($execute) {
        $result =['email'=>$email , 'status' => true, 'message' => 'User registered successfully'];
        echo json_encode($result);
    } else {
        $result =['status' => false, 'message' => 'Error registering user'];
        echo json_encode($result);
    }
}
?>
