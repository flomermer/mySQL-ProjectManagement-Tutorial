<?php include('../../consts/db.php'); ?>
<?php
$engineer_id = $_POST["rankEngineerID"];
$project_id = $_POST['rankProjectID'];
$month = $_POST['txtRankMonth'];
$year = $_POST['txtRankYear'];
$grade = $_POST['txtRankGrade'];

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error)
    die("error: " . $conn->connect_error);


$sql = "INSERT INTO grades (engineer_id, project_id, grade, month, year)
    VALUES ($engineer_id,$project_id, $grade, $month, $year)";

if ($conn->query($sql) === FALSE)
    die("error: $conn->error");


//$new_id = $conn->insert_id;
//echo "$new_id";

$conn->close;

?>
