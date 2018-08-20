function activateMenu() {
    var url = document.location.pathname.match(/[^\/]+$/)[0];
    $("ul.navbar-nav li[data-href='" + url + "']").addClass("active");

    $("ul.navbar-nav li").click(function () {
        window.location.href = $(this).attr("data-href");
    })
}
function getAge(date) {    
    var today = new Date();
    var birthDate = new Date(date);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }    
    return age;
}

function slctToggler() {
    if ($(this).val() == 'other') {        
        $(this).parent().next(".toggler:first").show().find("input:first").val('').attr("required",true);        
    } else {
        $(this).parent().next(".toggler:first").hide().find("input:first").val($(this).val()).attr("required",false);        
    }
}
function btnLinkClick() {
    window.location.href = $(this).attr("data-href");
}

function formatDate(d,mode) {
    var date = new Date(d);
    var day = date.getDate();
    var month = date.getMonth() + 1;
    var year = date.getFullYear();

    var delimeter = mode[1]; //either - / # or whatever user would like to seperate dates    
    
    var mode = mode.replace(delimeter, '');
    var mode = mode.replace(delimeter, '');

    if (day < 9)
        day = '0' + day;
    if (month < 10)
        month = '0' + month;
    
    var newDate;    
    if (mode == "dmy")
        newDate = day + delimeter + month + delimeter + year;
    else if (mode == 'mdy')
        newDate = month + delimeter + day + delimeter + year;
    else if (mode == 'ymd')
        newDate = year + delimeter + month + delimeter + day;
    else
        newDate = "INVALID";
    
    return newDate;
}
function autoComplete() {
    $(".autoComplete").each(function () {
        var tableName = $(this).attr("data-table");
        $(this).autocomplete({
            source: function (request, response) {
                $.ajax({
                    type: "post",
                    dataType: "json",
                    data: { table: tableName },
                    url: 'jsonsDB/autoComplete.php',
                    success: function (json) {
                        response(json)
                    }
                })
            },
        });       
    });
}

function deleteDynamicRow() {        
    var tableName = $(this).attr("data-table");
    var fieldName = $(this).attr("data-field");    
    var redirect = $(this).attr("data-redirect");    
    var rowID = $("#formRow input[name='hdnRowID']").val();
    if ($(this).attr("data-rowID") != undefined)
        rowID = $(this).attr("data-rowID");

    var j = confirm("האם למחוק רשומה זו?");
    if (j == false) return false;

    $.ajax({
        url: "modifyDB/deleteDynamicRow.php?tableName=" + tableName + "&fieldName=" + fieldName + "&fieldVal=" + rowID,
        success: function (data) {
            if (~data.indexOf("error")) {
                alert("לא ניתן למחוק רשומה זו");
                return false;
            }            
            $tableDB.bootstrapTable('removeByUniqueId', rowID);
            $("#modalRow").modal("hide");

            if (redirect)
                window.location.href = redirect;
        }
    })    
}

function bootstrapTable(callback) {   
    $(".tableDB").each(function () {        
        var $ajaxURL = "jsonsDB/" + $(this).attr("data-json");
        var $table = $(this);
        if ($table.attr("id") == 'tableEngineers' && $page=="project") {
            return true;
        }
        $.ajax({
            type: "post",
            dataType: "json",
            url: $ajaxURL,
            success: function (json) {
                if (callback !== undefined)
                    callback(json);
                $table.bootstrapTable({
                    data: json                    
                });
            }
        })    
    })        
}

function engineersManager() {
    initTables();
    $("#btnAddRow").click(addRowModal);
    $(".btnAddPhone").click(function () { renderPhoneRow() });            
    $("#formRow").on("submit", formSubmit);
    $("#formRankProject").on("submit", formRankProjectSubmit);
    $("#slctSortEngineers").change(slctSortEngineers);

    function initTables() {        
        var $table = $("#tableEngineers");
        var $ajaxURL = "jsonsDB/" + $table.attr("data-json");        
        $.ajax({
            type: "post",
            dataType: "json",
            url: $ajaxURL,
            success: function (json) {                    
                $table.bootstrapTable({
                    data: json,                    
                    onClickRow: function (row, $el, field) {
                        editRowModal(row,field);
                    }
                });                
                console.log(json);
            }
        }); 

        $("#tableRankProjects").bootstrapTable({
            onClickRow: function (row,$el,field) {
                rankProjectTableClickRow(row,$el);
            }
        });
    }
    function formRankProjectSubmit(event) {
        event.preventDefault();
        var projectID = $("#rankProjectID").val();
        if (projectID == '') {
            alert("יש לבחור את הפרוייקט לדירוג");
            return false;
        }
        formData = $("#formRankProject").serialize();
        
        $.ajax({
            type: "post",
            data: formData,
            url: "modifyDB/rankProject.php",
            success: function (data) {
                if (~data.indexOf("Duplicate")) {
                    alert("הפרוייקט הנל כבר מדורג במערכת עבור תאריך זה");
                    return false;
                }
                alert("הדירוג נקלט במערכת");
            }
        })
    }
    function rankProjectTableClickRow(row, $el) {        
        if ($el.hasClass("trChecked")) { //already press on selected project
            $("#formRankProject #rankProjectID").val('');            
            $("#tableRankProjects tbody tr").removeClass("trChecked");
        } else {
            $("#tableRankProjects tbody tr").removeClass("trChecked");
            $("#formRankProject #rankProjectID").val(row.rowID);            
            $el.addClass("trChecked");
        }        
    }
    function showRankModal(row) {
        var ajaxURL = "jsonsDB/projectsJSON.php";
        $.ajax({
            type: "post",
            data: { 'engineer_id': row.rowID },
            dataType: "json",
            url: ajaxURL,
            success: function (json) {                
                $("#tableRankProjects").bootstrapTable("load", json);
                $("#modalRank .modal-header label").text("מהנדס: " + row.rowID + ". " + row.firstname + " " + row.lastname + " - דירוג פרויקט")
                $("#modalRank").modal("show");
            }
        });                 
    }
    

    function slctSortEngineers() {
        var $ajaxURL = "jsonsDB/" + $("#tableEngineers").attr("data-json");
        var mode = $(this).val();
        $.ajax({
            type: "post",
            dataType: "json",
            data: { 'mode': mode },
            url: $ajaxURL,
            success: function (data) {                
                $("#tableEngineers").bootstrapTable("load", data)
            }, error: function () {
                alert("errror");
            }
        });         
    }

    function editRowModal(row,field) { 
        if (field == 'rank') {
            $("#rankEngineerID").val(row.rowID);
            showRankModal(row);
            return false;
        }
        $("input[name='txtEngineerFirstName']").val(row.firstname);
        $("input[name='txtEngineerLastName']").val(row.lastname);
        $("select[name='slctEngineerTopicID']").val(row.topic_id);
        $("textarea[name='txtEngineerAddress']").val(row.address);
        $("input[name='txtEngineerBirthdate']").val(row.birthdate);        
        $("#modalRow .modal-header label").text("עריכת מהנדס " + row.rowID);
        $("#modalRow .btn-submit").text("עריכה");        
        $("#formRow input[name='hdnRowID']").val(row.rowID);        
        $(".phones-container").empty();
        if (row.phones != null) {
            var phones = row.phones.split("<br>");
            phones.map(function (phone) {
                renderPhoneRow(phone);
            });
        }                

        $("#modalRow").modal("show");        
    }
    function addRowModal() {
        $("#modalRow .modal-header label").text("הוספת מהנדס חדש");
        $("#modalRow .btn-submit").text("הוספה");
        $("#formRow input[name='hdnRowID']").val("");
        $(".phones-container").empty();
        $("#formRow").trigger("reset");
        
        $("#modalRow").modal("show");
    }

    function renderPhoneRow(phone) {
        console.log(phone);
        if (phone === undefined)
            phone = '';

        var $newInputPhone = $('<input/>').attr({
            type: 'text',
            name: 'txtEngineerPhone[]',
            class: 'form-control ltr',
            placeholder: 'Phone Number',
            value: phone
        })

        $(".phones-container").append($newInputPhone);
    }

    function formSubmit() {         //engineers Form
        var phones = [];        
        $("input[name = 'txtEngineerPhone[]']").each(function (index) {
            if ($(this).val() != '')
                phones.push($(this).val());
        })                
        var formData = {
            rowID       :   $("input[name='hdnRowID']").val(),
            firstname   :   $("input[name='txtEngineerFirstName']").val(),
            lastname    :   $("input[name='txtEngineerLastName']").val(),
            topic_id    :   $("select[name='slctEngineerTopicID']").val(),
            topicName   :   $("select[name='slctEngineerTopicID'] option:selected").text(),
            address     :   $("textarea[name='txtEngineerAddress']").val(),
            birthdate   :   $("input[name='txtEngineerBirthdate']").val(),
            age         :   getAge($("input[name='txtEngineerBirthdate']").val()),
            phones      :   phones            
        }
       
        $.ajax({
            url: "modifyDB/editEngineer.php",
            type: 'POST',
            data: formData,
            success: function (newID) {           
                if (~newID.indexOf("error")) {
                    alert("bad submit: " + newID);                    
                    return false;
                }
                
                var row = {
                    'rowID':  newID,
                    'firstname' :   formData.firstname,
                    'lastname'  :   formData.lastname,
                    'topic_id'  :   formData.topic_id,
                    'topicName' :   formData.topicName,
                    'address'   :   formData.address,
                    'birthdate' :   formData.birthdate,
                    'age'       :   formData.age,
                    'phones'    :   formData.phones.join("<br>"),
                    'rank'      :   "<span class='glyphicon glyphicon-signal'></span>"
                };
                
                if (formData.rowID == '') { //add new row                    
                    $tableDB.bootstrapTable("prepend",row);
                } else { //edit row
                    delete row.rowID;
                    $tableDB.bootstrapTable('updateByUniqueId', {
                        id: formData.rowID,
                        row: row
                    });
                }
                
                $("#modalRow").modal("hide");
            },
            error: function () {
                alert("Bad submit");
            }
        });
    }   
}

function softwareTopicsManager() {
    bootstrapTable();

    $("#btnAddRow").click(addRowModal);    
    $("#formRow").on("submit", formSubmit);    

    $tableDB.on('click-row.bs.table', function (event, row, field) { editRowModal(row); });


    function editRowModal(row) {
        $("#modalRow .modal-header label").text("עריכת תחום תוכנה: " + row.rowID);
        $("#modalRow .btn-submit").text("עריכה");

        $("#formRow .toggler").hide();
        $("select[name='slctTopicsNames']").val(row.name);
        $("select[name='slctTopicsSpecialties']").val(row.specialty);
        $("input[name='txtTopicName']").val(row.name);
        $("input[name='txtTopicSpecialty']").val(row.specialty);

        $("#formRow input[name='hdnRowID']").val(row.rowID);
        
        $("#modalRow").modal("show");
    }

    function addRowModal() {
        $("#modalRow .modal-header label").text("הוספת תחום תוכנה חדש");
        $("#modalRow .btn-submit").text("הוספה");
        $("#formRow input[name='hdnRowID']").val("");
        $("#formRow .toggler").hide();
        $("#formRow").trigger("reset");

        $("#modalRow").modal("show");
    }    
    
    function formSubmit() {      //softwareTopicsForm  
        var $slctName       =   $("select[name='slctTopicsNames']");
        var $slctSpecialty  =   $("select[name='slctTopicsSpecialties']");
        var $txtName        =   $("input[name='txtTopicName']");
        var $txtSpecialty = $("input[name='txtTopicSpecialty']");

        var formData = {
            rowID: $("input[name='hdnRowID']").val(),
            name: $txtName.val(),
            specialty: $txtSpecialty.val(),            
        }
        
        $.ajax({
            url: "modifyDB/editSoftwareTopic.php",
            type: 'POST',
            data: formData,
            success: function (newID) {                         
                if (~newID.indexOf("error")) {
                    alert("bad submut: " + newID);
                    return false;
                } else if (newID == 'alreadyExists') {
                    alert("התחום וההתמחות כבר קיימים במערכת. לא ניתן להוסיף פעמיים");
                    return false;
                }                                
                
                if ($slctName.val() == 'other')  //add new value to names if neccassary
                    insertOptionToSelect($slctName, $txtName.val());
                if ($slctSpecialty.val() == 'other')  //add new value to specialties if neccassary
                    insertOptionToSelect($slctSpecialty, $txtSpecialty.val());

                var row = {
                    'rowID': newID,
                    'name': formData.name,
                    'specialty': formData.specialty                    
                };                

                if (formData.rowID == '') { //add new row                    
                    $tableDB.bootstrapTable("prepend", row);                    
                } else { //edit row
                    delete row.rowID;
                    $tableDB.bootstrapTable('updateByUniqueId', {
                        id: formData.rowID,
                        row: row
                    });
                }                

                $("#modalRow").modal("hide");
            },
            error: function () {
                alert("Bad submit");
            }
        });
    }      
    
    function insertOptionToSelect($select, value) {
        $select.find("option:last").before("<option value='" + value + "'>" + value + "</option>");
    }   
}

function projectsManager() {
    bootstrapTable();
    $("#btnDeleteProject").click(function () { });
    $("#btnAddRow").click(addRowModal);
    $("#formRow").on("submit", formSubmit);
    
    $tableDB.on('click-row.bs.table', function (event, row, field) { editRowModal(row); });
       
    function editRowModal(row) {        
        window.location.href = "project.php?projectID=" + row.rowID;
    }
    function addRowModal() {
        $("#modalRow .modal-header label").text("הוספת פרוייקט חדש");
        $("#modalRow .btn-submit").text("הוספה");
        $("#formRow input[name='hdnRowID']").val("");
        $("#formRow .toggler").hide();
        $("#formRow").trigger("reset");

        $("#modalRow").modal("show");
    }
    function formSubmit() {      //softwareTopicsForm          
        var formData = {
            rowID: $("input[name='hdnRowID']").val(),
            projectName: $("input[name='txtProjectName']").val(),
            clientName: $("input[name='txtClientName']").val(),
            startDate: $("input[name='txtStartDate']").val(),
        }

        $.ajax({
            url: "modifyDB/editProject.php",
            type: 'POST',
            data: formData,
            success: function (newID) {
                if (~newID.indexOf("error")) {
                    alert("bad submut: " + newID);
                    return false;
                }                 
                var row = {
                    'rowID': newID,
                    'projectName' : formData.projectName,
                    'clientName'  : formData.clientName,
                    'startDate': formData.startDate,
                    'startDateStr': formatDate(formData.startDate,"d/m/y")
                };

                if (formData.rowID == '') { //add new row                    
                    //$tableDB.bootstrapTable("prepend", row);
                    window.location.href = "project.php?projectID=" + row.rowID;
                } else { //edit row
                    delete row.rowID;
                    $tableDB.bootstrapTable('updateByUniqueId', {
                        id: formData.rowID,
                        row: row
                    });
                }

                $("#modalRow").modal("hide");
            },
            error: function () {
                alert("Bad submit");
            }
        });
    }       
}

function project() { //pageProject    
    bootstrapTable();   //except #tableEngineers
    loadEngineersTable();
    $("#formDetails").on("submit", detailsSubmit);

    $("#btnAddMilestone").click(addMilestoneModal);
    $("#tableMilestones").on('click-row.bs.table', function (event, row, field) { editMilestoneModal(row); });
    $("#formMilestone").on("submit", formMilestoneSubmit);

    $("#tableEngineers").on('click-row.bs.table', function (event, row, field) { removeEngineerFromProject(row); });
    $("#tableAddEngineer").on('click-row.bs.table', function (event, row, field) { addEngineerToProject(row); });

    $("#tableDevTools").on('click-row.bs.table', function (event, row, field) { editDevTool(row); });
    $("#btnAddDevTool").click(addDevTool);
    $("#btnDeleteTool").click(deleteTool);
    $("#formTool").on("submit", formToolSubmit);

    function deleteTool() {
        var rowID = $(this).attr("data-toolID");
        $.ajax({
            url: "modifyDB/deleteDynamicRow.php?tableName=development_tools&fieldName=auto_id&fieldVal=" + rowID,
            success: function (data) {
                $("#tableDevTools").bootstrapTable('removeByUniqueId', rowID);                
                $("#modalTool").modal("hide");                
            }
        })    
    }    
    function addDevTool() {
        $("#modalTool .modal-header label").text("הוספת כלי פיתוח:");
        $("#modalTool .btn-submit").text("הוספה");
        $("#modalTool #btnDeleteTool").hide();
        $("#formTool").trigger("reset");

        $("#formTool input[name='hdnRowID']").val("");
        $("#modalTool").modal("show");
    }
    function editDevTool(row) {
        $("#modalTool .modal-header label").text("עריכת כלי פיתוח: " + row.rowID);
        $("#modalTool .btn-submit").text("עריכה");
        $("#formTool input[name='hdnRowID']").val(row.rowID);

        $("#modalTool #btnDeleteTool").show();

        $("#formTool input[id='txtTool']").val(row.tool);
        $("#formTool select[id='slctToolDevTopic']").val(row.topic_id);

        $("#btnDeleteTool").attr("data-toolID", row.rowID);

        $("#modalTool").modal("show");
    }
    function formToolSubmit() {        
        var formData = {
            rowID: $("#formTool input[name='hdnRowID']").val(),
            topic: $("#formTool select[id='slctToolDevTopic'] option:selected").text(),
            tool: $("#formTool input[id='txtTool']").val(),
            topic_id: $("#formTool select[id='slctToolDevTopic']").val(),
            project_id: $("#hdnProjectID").val()
        }
        
        $.ajax({
            url: "modifyDB/editTool.php",
            type: 'POST',
            data: formData,
            success: function (newID) {
                if (~newID.indexOf("error")) {
                    alert("bad submut: " + newID);
                    return false;
                }                
                if (formData.rowID == '') { //add new row                    
                    formData.rowID = newID;
                    $("#tableDevTools").bootstrapTable("prepend", formData);
                    $("#modalTool").modal("hide");
                } else { //edit row                    
                    $tableDB.bootstrapTable('updateByUniqueId', {
                        id: formData.rowID,
                        row: formData
                    });
                }

                $("#modalTool").modal("hide");
            },
            error: function () {
                alert("Bad submit");
            }
        });
    }


    function addEngineerToProject(row) {
        var j = confirm("האם להוסיף את המהנדס: " + row.firstname + ' ' + row.lastname + ' לפרוייקט הנוכחי?');
        if (j == false) return false;
        var formData = {
            engineerID: row.rowID,
            projectID: $("#hdnProjectID").val()
        }  
        $.ajax({
            url: "modifyDB/addEngineerToProject.php",
            type: 'POST',
            data: formData,
            success: function (data) {
                if (~data.indexOf("error")) {
                    alert("המהנדס הנל כבר קיים בפרוייקט");
                    return false;
                }
                /*
                var newRow = {
                    'rowID': row.rowID,
                    'name': row.firstname + ' ' + row.lastname,
                    'topicName': row.topicName,
                    'topicID': row.topicID
                };
                $("#tableEngineers").bootstrapTable("append", newRow);
                */
                loadEngineersTable();
                $("#modalEngineers").modal("hide");
            }
        })    
    }

    function removeEngineerFromProject(row) {
        var j = confirm("האם להסיר מהנדס זה מהפרוייקט הנוכחי?");
        if (j == false) return false;
        var formData = {
            engineerID : row.rowID,
            projectID  : $("#hdnProjectID").val()
        }        
        $.ajax({
            url: "modifyDB/deleteEngineerFromProject.php",
            type: 'POST',
            data: formData,
            success: function (data) {                
                if (~data.indexOf("error")) {
                    alert("תקלה בעת מחיקה. הרשומה לא נמחקה");
                    return false;
                }
                //$("#tableEngineers").bootstrapTable('removeByUniqueId', row.rowID);         
                loadEngineersTable();
            }
        })    
    }
    function loadEngineersTable() {
        var $ajaxURL = "jsonsDB/" + $("#tableEngineers").attr("data-json");                
        $.ajax({
            type: "post",
            dataType: "json",            
            url: $ajaxURL,
            success: function (data) {
                console.log(data);
                $("#tableEngineers").bootstrapTable({                    
                    pagination: true,
                    pageSize: 10,
                    onPostBody: callback
                });
                $("#tableEngineers").bootstrapTable("load", data);                    
            }, error: function () {
                alert("errror");
            }
        });            

        function callback() {
            var rows = $("#tableEngineers").bootstrapTable("getData");

            var counter = 1;
            for (i = 0; i < rows.length; i++) { //make rowspan by topicName
                while (i + counter < rows.length && rows[i].topicName == rows[i + counter].topicName) {
                    counter++;
                }

                $("#tableEngineers").bootstrapTable('mergeCells', {
                    index: i,
                    field: 'topicName',
                    rowspan: counter
                });

                i = i + counter - 1;
                counter = 1;
            }
        }
    }

    function addMilestoneModal() {
        $("#formMilestone .btn-submit").text("הוספה")
        $("#modalMilestone .modal-header label").text("הוספת אבן דרך");
        $("#formMilestone").trigger("reset");        
        $("#btnDeleteMilestone").hide();
        $("#formMilestone input[name='hdnRowID']").val("");          

        $("#modalMilestone").modal("show");             
    }
    function editMilestoneModal(row) {
        $("#formMilestone .btn-submit").text("עריכה")
        $("#modalMilestone .modal-header label").text("עריכת אבן דרך: " + row.desc);        
        $("#btnDeleteMilestone").show();
        $("#formMilestone input[name='hdnRowID']").val(row.rowID);

        $("#formMilestone input[id='txtDesc']").val(row.desc),
        $("#formMilestone input[id='txtAmount']").val(row.amount),
        $("#formMilestone input[id='txtTargetDate']").val(row.targetDate)

        $("#modalMilestone").modal("show");
    }

    function formMilestoneSubmit() {        
        var formData = {
            rowID       :   $("#formMilestone input[name='hdnRowID']").val(),
            projectID   :   $("#hdnProjectID").val(),
            desc        :   $("#formMilestone input[id='txtDesc']").val(),
            amount      :   $("#formMilestone input[id='txtAmount']").val(),
            targetDate  :   $("#formMilestone input[id='txtTargetDate']").val()
        }
        
        $.ajax({
            url: "modifyDB/editMilestone.php",
            type: 'POST',
            data: formData,
            success: function (newID) { 
                if (~newID.indexOf("error")) {
                    alert("bad submit: " + newID);
                    return false;
                }
                var row = {
                    'rowID': newID,
                    'desc': formData.desc,
                    'amount': formData.amount,
                    'targetDate': formData.targetDate,
                    'targetDateStr': formatDate(formData.targetDate, "d/m/y")
                };

                if (formData.rowID == '') { //add new row                    
                    $("#tableMilestones").bootstrapTable("append", row);
                } else { //edit row
                    delete row.rowID;
                    $("#tableMilestones").bootstrapTable('updateByUniqueId', {
                        id: formData.rowID,
                        row: row
                    });
                }

                $("#modalMilestone").modal("hide");             
            },
            error: function () {
                alert("Bad submit");
            }
        });
    }

    function detailsSubmit() {      //softwareTopicsForm          
        var formData = {
            rowID:  $("#hdnProjectID").val(),
            projectName: $("#formDetails input[name='txtProjectName']").val(),
            clientName: $("#formDetails input[name='txtClientName']").val(),
            startDate: $("#formDetails input[name='txtStartDate']").val(),
        }

        $.ajax({
            url: "modifyDB/editProject.php",
            type: 'POST',
            data: formData,
            success: function (newID) {                
                $("#spanProjectName").text(formData.projectName);                
                $("#modalEditRow").modal("hide");                                
            },
            error: function () {
                alert("Bad submit");
            }
        });
    }       

} //end pageProject

function milestones() {
    bootstrapTable(onLoadSuccess);

    function onLoadSuccess(data) {
        var totalAmount = 0;        
        console.log(data);
        $.each(data, function () {
            totalAmount += Number(this.amount);
        });
        $("#spanTotalAmount").text('₪' + totalAmount);             
    }
} //end milestones

function developmentTopicsManager() {
    bootstrapTable();

    $("#btnAddRow").click(addRowModal);
    $("#formRow").on("submit", formSubmit);
    $tableDB.on('click-row.bs.table', function (event, row, field) { editRowModal(row); });


    function editRowModal(row) {
        window.location.href = "developmentTopic.php?devID=" + row.rowID;      
    }

    function addRowModal() {
        $("#modalRow .modal-header label").text("הוספת שלב פיתוח");
        $("#modalRow .btn-submit").text("הוספה");
        $("#formRow input[name='hdnRowID']").val("");
        $("#formRow").trigger("reset");

        $("#modalRow").modal("show");
    }
    function formSubmit() {      //softwareTopicsForm  
        var $txtName = $("input[name='txtTopicName']");
        
        var formData = {
            rowID: $("input[name='hdnRowID']").val(),
            name: $txtName.val()            
        }

        $.ajax({
            url: "modifyDB/editDevelopmentTopic.php",
            type: 'POST',
            data: formData,
            success: function (newID) {
                if (~newID.indexOf("error")) {
                    alert("bad submut: " + newID);
                    return false;
                }                
                var row = {
                    'rowID': newID,
                    'name': formData.name                    
                };

                if (formData.rowID == '') { //add new row                    
                    $tableDB.bootstrapTable("prepend", row);
                } else { //edit row
                    delete row.rowID;
                    $tableDB.bootstrapTable('updateByUniqueId', {
                        id: formData.rowID,
                        row: row
                    });
                }

                $("#modalRow").modal("hide");
            },
            error: function () {
                alert("Bad submit");
            }
        });
    }
}//end developmentTopicsManager

function developmentTopic() {
    bootstrapTable();
    $("#formDetails").on("submit", detailsSubmit);
    
    function detailsSubmit() {      //softwareTopicsForm          
        var formData = {
            rowID: $("#hdnDevID").val(),
            name: $("#formDetails input[name='txtDevName']").val()            
        }

        $.ajax({
            url: "modifyDB/editDevelopmentTopic.php",
            type: 'POST',
            data: formData,
            success: function (newID) {                                
                $("#spanDevName").text(formData.name);
                $("#modalEditRow").modal("hide");
            },
            error: function () {
                alert("Bad submit");
            }
        });
    }       
} //end developmentTopic

function projectsRanks() {
    initTable();
    
    function sparklineChart(cats, values) {        
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'דירוג פרוייקטים'
            },
            xAxis: {
                categories: cats
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'דירוג ממוצע'
                },
                stackLabels: {
                    enabled: false                    
                }
            },            
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: ''
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }
                }
            },
            series: [{
                name: 'דירוג משחר ההיסטוריה',
                data: values
            }]
        });
    }

    function initTable() {
        var $table = $("#tableProjectsRanks");        
        var ajaxURL = "jsonsDB/" + $table.attr("data-json");
        $.ajax({
            type: "post",
            dataType: "json",
            url: ajaxURL,
            success: function (json) {
                
                var cats = json.map(function (row) {
                    return row.projectName;
                });               
                var values = json.map(function (row) {
                    return Number(row.grade);
                });               
                
                sparklineChart(cats, values);

                $table.bootstrapTable({
                    data: json,
                    onPostBody: function () {
                        $table.find("tbody tr:lt(3):last").css("border-bottom", "3px solid green");
                        $table.find("tbody tr:last").prev().prev().css("border-top", "3px solid red");
                    }
                });                
            }
        });
    }
}

$("document").ready(function () {    
    $page = $("body").attr("data-page");    
    $tableDB = $(".tableDB");    
    activateMenu();

    $("form").submit(function (e) { e.preventDefault(); })
    $(".slctToggle").change(slctToggler);
    $("#btnDeleteRow").click(deleteDynamicRow);
    $(".btnLink").click(btnLinkClick);
    autoComplete();    

    if ($page == "engineersManager") {        
        engineersManager();        
    } else if ($page == 'softwareTopicsManager') {
        softwareTopicsManager();
    } else if ($page == 'projectsManager') {
        projectsManager();
    } else if ($page == 'project') {
        project();
    } else if ($page == 'milestones') {
        milestones();
    } else if ($page == 'developmentTopicsManager'){
        developmentTopicsManager();
    } else if ($page == 'developmentTopic') {
        developmentTopic();
    } else if ($page == 'projectsRanks') {
        projectsRanks();
    }
})