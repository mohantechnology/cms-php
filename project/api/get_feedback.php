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



$sql = "SELECT * FROM feedback;";
$result = $conn->query($sql);




if ($result &&  $result != "" &&  $result->num_rows >=  0) {
    $result_arr = array();

    $response['success'] = "Saved Feeback Successfully";
    $row;
    while ($row = $result->fetch_assoc()) {
        array_push($result_arr, $row);
    }
    $response['data'] =    $result_arr;
} else {
    $error .= "Something went wrong";
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
