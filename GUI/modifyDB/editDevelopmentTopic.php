<?php include('../../consts/db.php'); ?>
<?php

$rowID      =   $_POST["rowID"];     //edit-new flag
    if (is_null($rowID) || $rowID==''){$rowID=0;}
$name       =   $_POST["name"];

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error)
    die("error: " . $conn->connect_error);


if($rowID==0){ //add new topic
    $sql = "INSERT INTO development_topics (name)
        VALUES ('$name')";

    if ($conn->query($sql) === FALSE)
        die("error: $conn->error");


    $new_id = $conn->insert_id;

    echo "$new_id";
} else { //edit topic
    $sql = "UPDATE development_topics
            SET name = '$name'
            WHERE dev_topic_id = $rowID
            ";
    if($conn->query($sql)==FALSE)
        die("error: $conn->error");

}
$conn->close;

?>
