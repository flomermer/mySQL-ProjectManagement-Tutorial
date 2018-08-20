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

$devID = $_GET["devID"];

$sql =  "SELECT a.*, b.projectName
        from development_tools a
        LEFT JOIN projects b ON a.project_id=b.project_id
        WHERE dev_topic_id=$devID
        ORDER BY b.projectName
        ";
$result = $conn->query($sql);

$jsonArr = array();
if ($result->num_rows > 0) {
    while($rs = $result->fetch_assoc()) {
        $jsonArr[] = array(
                            'tool'          =>  $rs['tool'],
                            'projectName'   =>  $rs['projectName']
                            );
    }
}

echo json_encode($jsonArr);

$conn->close();
?>
