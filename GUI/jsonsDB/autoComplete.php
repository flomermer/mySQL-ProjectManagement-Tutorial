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

$table = $_POST['table'];
$jsonArr = array();

if($table=='engineers'){
    $sql = "SELECT * FROM engineers";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($rs = $result->fetch_assoc()) {
            $value = $rs['firstname'] . ' ' . $rs['lastname'];
            array_push($jsonArr,$value);
        }
    }
}

echo json_encode($jsonArr);
$conn->close();
?>
