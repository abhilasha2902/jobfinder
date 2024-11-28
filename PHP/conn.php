<?php 
$conn = mysqli_connect("localhost","root","","jobden");
if (!$conn) {
    die("Conntion fail". mysqli_connect_error());
}

?>