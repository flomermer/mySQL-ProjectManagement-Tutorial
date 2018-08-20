<?php include('../consts/db.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>mySQL - GUI</title>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <script src="includes/jquery/jquery-3.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="includes/bootstrap/3.3.5/css/bootstrap.css" />
    <script src="includes/bootstrap/3.3.5/table/src/bootstrap.table.fix.min.js"></script>
    <link rel="stylesheet" href="includes/bootstrap/3.3.5/table/src/bootstrap-table.css" />
    <script src="includes/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="includes/style.css" />
    <script src="includes/script.js"></script>

    <?php
    $devID = $_GET["devID"];

    // Create connection
    $conn = new mysqli($servername, $username, $password,$db);

    $sql = "SELECT * FROM development_topics WHERE dev_topic_id=$devID";
    $result = $conn->query($sql);
    $rs = $result->fetch_assoc();

    $devName = $rs['name'];
    $conn->close();
    ?>
</head>
<body data-page="developmentTopic">
    <header class="mainMenu"><?php include('menu.php');?></header>
    <main>
        <div class="row col-xs-12 text-center form-group">
            <div class="col-xs-2 text-right">
                <button class="btn btn-default btnLink" data-href="developmentTopicsManager.php">
                    <span class="glyphicon glyphicon-arrow-right"></span> חזרה
                </button>
            </div>
            <div class="col-xs-8 text-center">
                <h2>
                    שלב פיתוח:
                    <span id="spanDevName">
                        <?php echo $devName;?>
                    </span>
                </h2>
            </div>
            <div class="col-xs-2 text-left">
                <button id="btnEditRow" class="btn btn-default" data-toggle="modal" data-target="#modalEditRow">
                    <span class="glyphicon glyphicon-edit"></span>עריכה
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 text-right"> <!--column details-->

            </div>
            <div class="col-xs-4 text-center"> <!--column milestones-->
                <header class="form-group text-center">

                </header>
                <table class="table table-bordered table-responsive table-hover tableDB" id="tableDevelopmentTools"
                    data-unique-id="rowID" data-json="developmentToolsPerTopic.php?devID=<?php echo $devID;?>">
                    <caption>כלי פיתוח</caption>
                    <thead>
                        <tr>
                            <th data-field="rowID" data-visible="false"></th>
                            <th data-field="projectID" data-visible="false"></th>
                            <th class="col-md-6 text-center" data-field="tool" data-sortable="true">כלי פיתוח</th>
                            <th class="col-md-6 text-center" data-field="projectName" data-sortable="true">פרוייקט</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--loaded by ajax-->
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4 text-center">

            </div>
        </div>
    </main>
    <div class="modal fade" id="modalEditRow" style="direction:rtl;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    &nbsp;&nbsp;
                    <label>פרטי שלב פיתוח:</label>
                    <button id="btnDeleteRow" data-table="development_topics" data-field="dev_topic_id" data-rowid="<?php echo $devID;?>" data-redirect="developmentTopicsManager.php" class="btn btn-default btn-danger" style="float:left;">
                        <span class="glyphicon glyphicon-remove"></span>הסרה
                    </button>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <form id="formDetails" method="post">
                            <div class="text-center form-group">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="txtDevName" value="<?php echo $devName;?>" required />
                                </div>
                                <div class="form-group text-center">
                                    <button class="btn btn-submit">עריכת שם</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" id="hdnDevID" value="<?php echo $devID;?>" />
</body>
</html>
