<?php include('../../consts/db.php'); ?>
<?php

$rowID      =   $_POST["rowID"];     //edit-new flag
    if (is_null($rowID) || $rowID==''){$rowID=0;}

$projectName    =   $_POST["projectName"];
$clientName     =   $_POST["clientName"];
$startDate      =   $_POST["startDate"];

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error)
    die("error: " . $conn->connect_error);

if($rowID==0){ //add new topic
    $sql = "INSERT INTO projects (projectName, clientName, startDate)
        VALUES ('$projectName','$clientName', '$startDate')";

    if ($conn->query($sql) === FALSE)
        die("error: $conn->error");


    $new_id = $conn->insert_id;

    echo "$new_id";
} else { //edit topic
    $sql = "UPDATE projects
            SET projectName = '$projectName', clientName = '$clientName', startDate='$startDate'
            WHERE project_id = $rowID
            ";
    if($conn->query($sql)==FALSE)
        die("error: $conn->error");

}
$conn->close;

?>
