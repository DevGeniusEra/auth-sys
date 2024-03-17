<?php
 //host
 $host= "localhost";
 //dbName
 $dbname = "auth-sys";
 //user
 $user = "root";
 //pass
 $pass = "";

 $conn = new PDO("mysql:host=$host;dbname=$dbname;", $user,$pass);

 if($conn == true) {
    echo "Its work fine.";
 } else{
    echo "connection is wrong";
 }


?>