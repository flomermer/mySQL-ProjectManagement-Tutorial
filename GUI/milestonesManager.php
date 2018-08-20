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

</head>
<body data-page="milestones">
    <header class="mainMenu"><?php include('menu.php');?>
    </header>
    <main>
        <div class="row panel">
            <div class="col-md-2 text-right">
                
            </div>
            <div class="col-xs-8 text-center">
                <h2>אבני דרך - צפי ל30 יום הקרובים</h2>
            </div>
        </div>
        
        <table class="table table-bordered table-responsive table-hover tableDB" id="tableMilestones"
               data-unique-id="rowID" data-json="milestonesJSON.php">
            <caption>אבני דרך</caption>
            <thead>
                <tr>
                    <th class="col-md-1 text-center" data-field="rowID" data-sortable="true">קוד</th>
                    <th class="col-md-4 text-center" data-field="desc" data-sortable="true">אבן דרך</th>                    
                    <th class="col-md-2 text-center" data-field="amount" data-sortable="true">סכום</th>
                    <th class="col-md-2 text-center" data-field="targetDateStr" data-sortable="true">תאריך יעד</th>
                    <th class="col-md-3 text-center" data-field="projectName" data-sortable="true">פרוייקט</th>
                    <th data-field="targetDate" data-visible="false"></th>
                </tr>
            </thead>
            <tbody>
                <!--loaded by ajax-->
            </tbody>
        </table>
        <div class="col-xs-12 text-center">
            <h3>סכום הכנסות כולל: <span id="spanTotalAmount"></span></h3>
        </div>
        <div class="modal fade" id="modalRow" style="direction:rtl;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        &nbsp;&nbsp;
                        <label></label>
                        <button id="btnDeleteRow" data-table="projects" data-field="project_id" class="btn btn-xs btn-default" style="float:left">
                            <span class="glyphicon glyphicon-remove"></span> הסרה
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="content">
                            <form id="formRow" method="post">
                                <input type="hidden" name="hdnRowID" /><!-- add/edit -->
                                                               
                                <div class="form-group">
                                    <input type="text" class="form-control" name="txtProjectName" placeholder="שם פרויקט..." required />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="txtClientName" placeholder="שם לקוח..." required />
                                </div>                                
                                <div class="form-group">
                                    <input type="date" class="form-control" name="txtStartDate" required>
                                </div>

                                <div class="form-group text-center">
                                    <button class="btn btn-success btn-submit"></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>  <!--modalNewRow-->     
</body>
</html>
