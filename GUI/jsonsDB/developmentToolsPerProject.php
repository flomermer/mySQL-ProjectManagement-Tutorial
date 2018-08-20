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

$projectID = $_GET["projectID"];

$sql =  "SELECT a.*, b.name AS topicName, b.dev_topic_id AS topic_id
        from development_tools a
        LEFT JOIN development_topics b ON a.dev_topic_id=b.dev_topic_id
        WHERE project_id=$projectID
        ORDER BY b.name
        ";
$result = $conn->query($sql);

$jsonArr = array();
if ($result->num_rows > 0) {
    while($rs = $result->fetch_assoc()) {
        $jsonArr[] = array(
                            'rowID'     =>  $rs['auto_id'], //tool_id
                            'topic'     =>  $rs['topicName'],
                            'tool'      =>  $rs['tool'],
                            'topic_id'  =>  $rs['topic_id']
                            );
    }
}

echo json_encode($jsonArr);

$conn->close();
?>
