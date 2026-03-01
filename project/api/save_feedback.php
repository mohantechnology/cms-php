<?php

session_start();
// include "../user_password.php";
include "../password.php";

include "../user_password.php";

// if ((!isset($_SESSION["admin_ps"])) ||   $_SESSION["admin_ps"] != $user_password) {
//     unset($_POST);
//     unset($_FILES);
//     unset($_SESSION["admin_ps"]);
//    return  header("Location: ../login.php");
// }
$response  = array();
$error = "";


 
if (!isset($_POST["name"]) ||  !isset($_POST["email"]) || !isset($_POST["feedback"])) {
    $error .= "Must have 'name' , 'email' , 'feedback'";
} else {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $feedback = trim($_POST["feedback"]);

    if ($name   &&  $email &&  $feedback) {

        $sql = "INSERT INTO  feedback ( name , email ,feedback )   VALUES ('$name' , '$email' , '$feedback') ;
        ";
        $result = $conn->query($sql);
 

        if ($result) {
            $response['success'] = "Saved feedback Successfully";
        } else {
            $error .= "Something went wrong";
        }
    } else {
        $error .= "Must have 'name' , 'email' , 'feedback'";
    }
}

header('Content-Type: application/json; charset=utf-8');

if ($error) {
    $response['error'] = $error;
    http_response_code(400);
    echo json_encode($response);
} else if ($response['success']) {
    http_response_code(200);
    echo json_encode($response);
} else {

    $response['error'] =  "Something went wrong";
    http_response_code(500);
}




?>
