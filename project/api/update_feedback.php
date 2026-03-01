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



if (!isset($_POST["id"]) ||  !isset($_POST["status"])) {
    $error .= "Must have 'id', 'status' ";
} else {
    $id = trim($_POST["id"]);
    $status = trim($_POST["status"]);

    if ($id && $status) {
        $row_result ; 

        $sql = "SELECT * FROM    feedback  WHERE id = $id  ";
        $row_result = $conn->query($sql);
        $row_result = $row_result->fetch_assoc(); 
        $sql;
        if ($status == 'approved') {
            $sql = "UPDATE   feedback SET status = 'approved'  WHERE id = $id  ";
            $result = $conn->query($sql);
            $response['success'] = "Approved Successfully";
        } else {
            $status = 'rejected';
            $sql = "DELETE FROM feedback   WHERE id = $id ";
            $result = $conn->query($sql);
            $response['success'] = "Rejected Successfully";
        }

    //    print_r($row_result) ; 
    //   echo "result is --- :" ; 
       

        if (!$result) {
            $error .= "Something went wrong";
        } else {
            require_once __DIR__ . '/../send_email.php';
            $mailResult = send_feedback_email($row_result['email'], $row_result['feedback'], $status);
            $response['mail'] = $mailResult['sent'];
            $response['mail_log'] = $mailResult['log'];
        }
    } else {
        $error .= "Must have 'id', 'status' ";
    }
}


if ($error) {
    $response['error'] = $error;
    http_response_code(400);
} elseif (!empty($response['success'])) {
    http_response_code(200);
} else {
    $response['error'] = "Something went wrong";
    http_response_code(500);
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);

