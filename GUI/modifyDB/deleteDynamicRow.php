<?php include('../../consts/db.php'); ?>
<?php

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tableName = $_GET["tableName"];
$fieldName = $_GET["fieldName"];
$fieldVal  = $_GET["fieldVal"];

$sql = "DELETE FROM $tableName WHERE $fieldName=$fieldVal";
if($conn->query($sql)==FALSE)
    die("error. cannot delete entity");

$conn->close();
?>
