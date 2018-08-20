<?php include('../../consts/db.php'); ?>
<?php
header('Content-Type: application/json');

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("SET NAMES 'utf8'");

$sql =  "SELECT * FROM development_topics order by name ASC";
$result = $conn->query($sql);

$jsonArr = array();
if ($result->num_rows > 0) {
    while($rs = $result->fetch_assoc()) {
        $jsonArr[] = array(
                            'rowID'         =>  $rs['dev_topic_id'],
                            'name'          =>  $rs['name']
                            );
    }
}

echo json_encode($jsonArr);

$conn->close();
?>
