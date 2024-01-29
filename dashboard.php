<?php
require 'connection.php';
$id=json_decode(file_get_contents("php://input"), true);
$email = $input["email"];
echo json_encode($email);
$query = "SELECT * FROM bankinfo WHERE email=?";
$prepare = $dbconnection->prepare($query);
$prepare->bind_param("s", $email);
$prepare->execute();
$user = $prepare->get_result()->fetch_assoc();

if ($user) {
    $amount = $user['amount'];
    echo json_encode(array('status' => true, 'amount' => $amount));
} else {
    echo json_encode(array('status' => false, 'message' => 'User not found'));
}
?>
