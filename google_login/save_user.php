<?php
session_start();

$output = [
    'success' => false
];

if(empty($_SESSION['user']) && isset($_POST['id'])){


    $user = [
        'f_name' => $_POST['fName'],
        'l_name' => $_POST['lName'],
        'email' => $_POST['email'],
        'id' => $_POST['id']
    ];

    //$mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
    require_once("../db/config/connect.php");
    if ($conn->connect_error) {
        die('Error : (' . $conn->connect_errno . ') ' . $conn->connect_error);
    }

    //check if user exist in database using COUNT
    $result = $conn->query("SELECT COUNT(google_id) as usercount FROM google_users WHERE google_id=$user[id]");
    $user_count = $result->fetch_object()->usercount; //will return 0 if user doesn't exist

    if ($user_count){
        $output['success'] = true;
        $output['msg'] = "Welcome back $user[f_name]";

    } else {

        $sql = "INSERT INTO google_users (google_id, google_fname, google_lname, google_email) VALUES ('$user[id]', '$user[f_name]', '$user[l_name]', '$user[email]')";

        if(!$result = $conn->query($sql)){
            $output['error'][] = $conn->error;
            $output['error'][] = $conn->errno;
            $output['sql'] = $sql;
        } else if($conn->affected_rows > 0){
            $output['success'] = true;
            $output['msg'] = "Welcome $user[f_name], thank you for registering";
        } else {
            $output['error'][] = 'Unable to add to db';
            $output['sql'] = $sql;
        }
    }

    unset($user['id']);

    $_SESSION['user'] = $user;
} else if(isset($_SESSION['user'])){
    $output['success'] = true;
    $output['msg'] = 'User already logged in';
} else {
    $output['error'] = 'No post data received';
}

print(json_encode($output));
