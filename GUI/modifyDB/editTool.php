<?php include('../../consts/db.php'); ?>
<?php

$rowID      =   $_POST["rowID"];     //edit-new flag
    if (is_null($rowID) || $rowID==''){$rowID=0;}

$project_id =   $_POST["project_id"];
$topic_id   =   $_POST["topic_id"];
$tool       =   $_POST["tool"];
$topic      =   $_POST["topic"];

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error)
    die("error: " . $conn->connect_error);

if($rowID==0){ //add new topic
    $sql = "INSERT INTO development_tools (project_id, dev_topic_id, tool)
        VALUES ($project_id, $topic_id, '$tool')";

    if ($conn->query($sql) === FALSE)
        die("error: $conn->error");


    $new_id = $conn->insert_id;

    echo "$new_id";
} else { //edit topic
    $sql = "UPDATE development_tools
            SET dev_topic_id=$topic_id, tool='$tool'
            WHERE auto_id = $rowID
            ";
    if($conn->query($sql)==FALSE)
        die("error: $conn->error");

}
$conn->close;

?>
