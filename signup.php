<?php
include("config.php");
session_start();

$prevPage = $_SERVER["HTTP_REFERER"];

$i = $_POST['id'];
$p = $_POST['pwd'];

$encrypted_p = password_hash($p, PASSWORD_DEFAULT);

$sql = "SELECT id FROM login WHERE id ='$i'";
$sql2 = "INSERT INTO login (id, pwd, iCent) VALUES ('$i', '$encrypted_p', 5000)";
$result = $conn->query($sql);
$count = mysqli_num_rows($result);

if ($count == 1) {
    $msg = "ID already exists";
    header('Location: signup.html');
} else {
    $conn->query($sql2);
    $msg = "successed!";
    echo 'Register ' . $msg;
    header('Refresh: 1; URL = index.html');
}

mysqli_close($conn);

?>