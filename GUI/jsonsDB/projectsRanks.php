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

$sql = "SELECT *, ROUND(AVG(g.grade),1) as avgGrade, p.projectName
        FROM grades g
        LEFT JOIN projects p ON p.project_id=g.project_id
        GROUP BY g.project_id
        ORDER BY avgGrade DESC
        ";

$result = $conn->query($sql);

$jsonArr = array();
if ($result->num_rows > 0) {
    while($rs = $result->fetch_assoc()) {
        $jsonArr[] = array(
                            'rowID'         =>  $rs['project_id'],
                            'projectName'   =>  $rs['projectName'],
                            'grade'         =>  $rs['avgGrade']
                            );
    }
}

echo json_encode($jsonArr);

$conn->close();
?>
