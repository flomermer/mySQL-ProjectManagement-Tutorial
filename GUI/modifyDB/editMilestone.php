<?php include('../../consts/db.php'); ?>
<?php

$rowID      =   $_POST["rowID"];     //edit-new flag
    if (is_null($rowID) || $rowID==''){$rowID=0;}

$projectID  =   $_POST["projectID"];
$desc       =   $_POST["desc"];
$amount     =   $_POST["amount"];
$targetDate =   $_POST["targetDate"];

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error)
    die("error: " . $conn->connect_error);

if($rowID==0){ //add new topic
    $sql = "INSERT INTO milestones (project_id, amount, targetDate, description)
        VALUES ($projectID, $amount, '$targetDate', '$desc')";

    if ($conn->query($sql) === FALSE)
        die("error: $conn->error");


    $new_id = $conn->insert_id;

    echo "$new_id";
} else { //edit topic
    $sql = "UPDATE milestones
            SET description = '$desc', targetDate='$targetDate', amount=$amount
            WHERE milestone_id = $rowID
            ";
    if($conn->query($sql)==FALSE)
        die("error: $conn->error");

}
$conn->close;

?>
