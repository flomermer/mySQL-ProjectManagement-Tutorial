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

$sql = "INSERT INTO project_participations (project_id, engineer_id)
        VALUES ($projectID, $engineerID)";
if($conn->query($sql)==FALSE)
    die("error. cannot insert entity");

$conn->close();
?>
