<?php
$host = "localhost";
$user = "root";
$pass = "";
$database = "note_db";
$connect = new mysqli($host, $user, $pass, $database);
if($connect-> connect_error){
    die("Connection failed: ". $connect-> connect_error);
}
?>