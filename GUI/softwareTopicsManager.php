<?php include('../consts/db.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>mySQL - GUI</title>
        <script src="includes/jquery/jquery-3.2.1.min.js"></script>
        <link rel="stylesheet" href="includes/bootstrap/3.3.5/css/bootstrap.css" />
        <script src="includes/bootstrap/3.3.5/table/src/bootstrap.table.fix.min.js"></script>
        <link rel="stylesheet" href="includes/bootstrap/3.3.5/table/src/bootstrap-table.css" />
        <script src="includes/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="includes/style.css" />
        <script src="includes/script.js"></script>

        <?php
            // Create connection
            $conn = new mysqli($servername, $username, $password,$db);

            $sql = "SELECT * FROM software_topics GROUP BY NAME ORDER BY name ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($rs = $result->fetch_assoc()) {
                    $slctTopicsNames .= "<option value='".$rs["name"] . "'>". $rs["name"] . "</option>";
                }
            }

            $sql = "SELECT * FROM software_topics GROUP BY specialty ORDER BY specialty ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($rs = $result->fetch_assoc()) {
                    $slctTopicsSpecialties .= "<option value='".$rs["specialty"] . "'>". $rs["specialty"] . "</option>";
                }
            }

            $conn->close();
        ?>
    </head>
    <body data-page="softwareTopicsManager">
        <header class="mainMenu">
            <?php include('menu.php');?>
        </header>
        <main>
            <div class="row panel">
                <div class="col-md-2 text-right">
                    <button class="btn btn-default btnAddRow" id="btnAddRow">
                        <span class="glyphicon glyphicon-plus"></span>תחום חדש
                    </button>
                </div>
            </div>
            <table class="table table-bordered table-responsive table-hover tableDB"
                data-unique-id="rowID" data-json="softwareTopicsJSON.php">
                <caption>טבלת תחומים</caption>
                <thead>
                    <tr>
                        <th class="col-md-2 text-center" data-field="rowID" data-sortable="true">קוד</th>
                        <th class="col-md-5 text-center" data-field="name" data-sortable="true">שם תחום</th>
                        <th class="col-md-5 text-center" data-field="specialty" data-sortable="true">מומחיות</th>
                    </tr>
                </thead>
                <tbody>
                    <!--loaded by ajax-->
                </tbody>
            </table>
            <div class="modal fade" id="modalRow" style="direction:rtl;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            &nbsp;&nbsp;
                            <label></label>
                            <button id="btnDeleteRow" data-table="software_topics" data-field="topic_id" class="btn btn-xs btn-default" style="float:left">
                                <span class="glyphicon glyphicon-remove"></span> הסרה
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="content">
                                <form id="formRow" method="post">
                                    <input type="hidden" name="hdnRowID" /><!-- add/edit -->
                                    <div class="form-group">
                                        <select name="slctTopicsNames" class="form-control slctToggle" required>
                                            <option value="">שם התחום</option>
                                            <?php echo $slctTopicsNames;?>
                                            <option value="other">אחר</option>
                                        </select>
                                    </div>
                                    <div class="form-group toggler">
                                        <input type="text" class="form-control" name="txtTopicName" placeholder="שם תחום..."/>
                                    </div>
                                    <div class="form-group">
                                        <select name="slctTopicsSpecialties" class="form-control slctToggle" required>
                                            <option value="">מומחיות</option>
                                            <?php echo $slctTopicsSpecialties;?>
                                            <option value="other">אחר</option>
                                        </select>
                                    </div>
                                    <div class="form-group toggler">
                                        <input type="text" class="form-control" name="txtTopicSpecialty" placeholder="שם מומחיות..."/>
                                    </div>

                                    <div class="form-group text-center">
                                        <button class="btn btn-success btn-submit"></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
</body>
</html>
