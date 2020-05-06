<?php
include("config.php");
session_start();

$i = $_POST['id'];
$p = $_POST['pwd'];

$prevPage = $_SERVER["HTTP_REFERER"];

// Attempt insert query execution
$sql = "SELECT id, pwd FROM login WHERE id ='$i'";
$result2 = $conn->query($sql);
$row = mysqli_fetch_array($result2, MYSQLI_ASSOC);
$hash_p = $row["pwd"];
$sql2 = "DELETE FROM login WHERE id = '$i'";
$result = $conn->query($sql);
$count = mysqli_num_rows($result);


if ($count == 1) {
    if (password_verify($p, $hash_p)) {
        $conn->query($sql2);
        echo '<script language="javascript">';
        echo 'alert("Account deleted!")';
        echo '</script>';
        header('Refresh: 2; URL = index.html');
    } else {
        $msg = "Hash Fail";
    }
} else {
    header('Location: drop.html');
}
// Close connection
mysqli_close($conn);
