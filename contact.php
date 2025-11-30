<?php
include ('../config/db.php');
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$sql="insert into contact (name,email,subject,message) values('$name', '$email','$subject','$message')";
mysqli_query(mysql: $con, query: $sql);
echo "<script> alert('Register sucessful'); </script>";
header("Location: ../index.php");
?>
