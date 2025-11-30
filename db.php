<?php
$con=mysqli_connect(hostname:'localhost',username:'root',password:'',database:'testingdb');
if($con)
{
    echo "connected";
}
else
{
    echo "not connected";
}
?>