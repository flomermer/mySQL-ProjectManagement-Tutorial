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
    </head>
    <body data-page="developmentTopicsManager">
        <header class="mainMenu">
            <?php include('menu.php');?>
        </header>
        <main>
            <div class="row panel">
                <div class="col-md-2 text-right">
                    <button class="btn btn-default btnAddRow" id="btnAddRow">
                        <span class="glyphicon glyphicon-plus"></span>שלב פיתוח חדש
                    </button>
                </div>
            </div>
            <table class="table table-bordered table-responsive table-hover tableDB"
                data-unique-id="rowID" data-json="developmentTopicsJSON.php">
                <caption>טבלת שלבי פיתוח</caption>
                <thead>
                    <tr>
                        <th class="col-md-1 text-center" data-field="rowID" data-sortable="true">קוד</th>
                        <th class="col-md-10 text-center" data-field="name" data-sortable="true">שלב פיתוח</th>
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
                            <button id="btnDeleteRow" data-table="development_topics" data-field="dev_topic_id" class="btn btn-xs btn-default" style="float:left">
                                <span class="glyphicon glyphicon-remove"></span> הסרה
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="content">
                                <form id="formRow" method="post">
                                    <input type="hidden" name="hdnRowID" /><!-- add/edit -->
                                    <div class="form-group">
                                        <input class="form-control ltr" name="txtTopicName"/>
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
