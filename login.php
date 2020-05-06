<?php
include("config.php");
session_start();

$i = $_POST['id'];
$p = $_POST['pwd'];

$sql = "SELECT id, pwd FROM login WHERE id ='$i'";
$result = $conn->query($sql);
$count = mysqli_num_rows($result);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$hash_p = $row["pwd"];

if ($count == 1) {
   if (password_verify($p, $hash_p)) {
      $_SESSION['id'] = $i;
      if (isset($_SESSION['id'])) {
         header('Location: news.php');
      } else {
         $msg = "Session Fail";
      }
   } else {
      echo '<script language="javascript">';
      echo 'alert("Username and password do not match.")';
      echo '</script>';
   }
} else {
   echo '<script language="javascript">';
   echo 'alert("Username and password do not match.")';
   echo '</script>';
}
echo '<script language="javascript">';
echo 'location.replace("index.html")';
echo '</script>';

mysqli_close($conn);
echo $msg;
