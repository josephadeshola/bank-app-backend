<?php
require 'connection.php';
$input = json_decode(file_get_contents("php://input"), true);
$email = $input["email"];
$password = $input["password"];
$query = "SELECT * FROM bankinfo WHERE email=?";
$prepare = $dbconnection->prepare($query);
$prepare->bind_param("s", $email);
$prepare->execute();
$existingUserResult = $prepare->get_result();

if ($existingUserResult->num_rows > 0) {
    $existingUser = $existingUserResult->fetch_assoc();

    $hashedPassword = $existingUser['password'];

    if (password_verify($password, $hashedPassword)) {
        echo json_encode(array("status" => true, "message" => "login successful"));
    } else {
        echo json_encode(array("status" => false, "message" => "login unsuccessful"));

    }
} else {
    $result = json_encode(array('status' => false, 'message' => 'Email does not exist'));
    echo json_encode($result);
}
?>