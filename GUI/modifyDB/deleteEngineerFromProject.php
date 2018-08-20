<?php include('../../consts/db.php'); ?>
<?php

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$projectID = $_POST["projectID"];
$engineerID = $_POST["engineerID"];

$sql = "DELETE FROM project_participations WHERE engineer_id='$engineerID' AND project_id='$projectID'";
if($conn->query($sql)==FALSE)
    die("error. cannot delete entity");

$conn->close();
?>
