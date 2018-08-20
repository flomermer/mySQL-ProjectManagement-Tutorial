<?php include('../../consts/db.php'); ?>
<?php

$rowID      =   $_POST["rowID"];     //edit-new flag
    if (is_null($rowID) || $rowID==''){$rowID=0;}

$name       =   $_POST["name"];
$specialty  =   $_POST["specialty"];

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error)
    die("error: " . $conn->connect_error);


/*check if topic is already exists*/
$sql = "SELECT * FROM software_topics WHERE name='$name' AND specialty='$specialty' AND topic_id<>$rowID";
$result = $conn->query($sql);

if($result->num_rows!=0){
    echo "alreadyExists";
    exit();
}

if($rowID==0){ //add new topic
    $sql = "INSERT INTO software_topics (name, specialty)
        VALUES ('$name','$specialty')";

    if ($conn->query($sql) === FALSE)
        die("error: $conn->error");


    $new_id = $conn->insert_id;

    echo "$new_id";
} else { //edit topic
    $sql = "UPDATE software_topics
            SET name = '$name', specialty = '$specialty'
            WHERE topic_id = $rowID
            ";
    if($conn->query($sql)==FALSE)
        die("error: $conn->error");

}
$conn->close;

?>
