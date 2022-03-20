//Change Daily Treatment Schedule table based on shifts and type of treatment(Treatment or Medication).
function adjustTable(row) {
    var type = document.getElementById("type").value;
    //On start
    if (type === '') {
        type = "Medication";
    }
    var shiftType = "";
    shiftType = document.getElementById("shift").value + "_" + type;
    if (shiftType.length == 0) {
        document.getElementById("basic-datatables").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from DailyTreatment.php
                document.getElementById("basic-datatables").innerHTML = this.responseText;
            }
        };
        //Send value of shift and type of treatment to DailyTreatment.php
        xmlhttp.open("GET", "PHP/DailyTreatment.php?shift=" + shiftType, true);
        xmlhttp.send();
    }
}
//Add timestamp when any treatment is adminstered.
function addTimeStamp(timestamp) {
    if (timestamp.length == 0) {
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from DailyTreatment.php and display message
                document.getElementById("txtHint").innerHTML = this.responseText;
                var msg = document.getElementById("txtHint").innerHTML;
                alert(msg);
            }
        };
        //Send value of each treatment's timestamp to DailyTreatment.php
        xmlhttp.open("GET", "PHP/DailyTreatment.php?timestamp=" + timestamp.value, true);
        xmlhttp.send();
    }
    timestamp.disabled = true;
}
//Show only treatments in the selected time frame
function showTreatment() {
    var type = document.getElementById("type");
    type.value = "Physical Therapy";
    var shift = document.getElementById("shift");
    //Concatenate physical therapy with shift number
    if (shift.value.includes("Shift")) {
        shiftType = shift.value + "_" + type.value;
    } else {
        shiftType = "Shift 1" + "_" + type.value;
    }
    if (shiftType.length == 0) {
        document.getElementById("basic-datatables").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from DailyTreatment.php and display on table
                document.getElementById("basic-datatables").innerHTML = this.responseText;
            }
        };
        //Send value of shift and type of treatment to DailyTreatment.php
        xmlhttp.open("GET", "PHP/DailyTreatment.php?shift=" + shiftType, true);
        xmlhttp.send();
    }
}
//Show only medication in the selected time frame
function showMedication() {
    var type = document.getElementById("type");
    type.value = "Medication";
    var shift = document.getElementById("shift");
    //Concatenate medication with shift number
    if (shift.value.includes("Shift")) {
        select = shift.value + "_" + type.value;
    } else {
        select = "Shift 1" + "_" + type.value;
    }
    if (select.length == 0) {
        document.getElementById("txtHint2").innerHTML = "";
        document.getElementById("basic-datatables").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //Receive result from DailyTreatment.php and display on table
                document.getElementById("basic-datatables").innerHTML = this.responseText;

            }
        };
        //Send value of shift and type of treatment to DailyTreatment.php
        xmlhttp.open("GET", "PHP/DailyTreatment.php?shift=" + select, true);
        xmlhttp.send();
    }
}
//If the data in the database table expires the "Export" button is visible to clear the database table
function onExpire() {
    var textHint = document.getElementById("txtHint");
    textHint.style.display = "block";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //If the data expired then the span will display the button hidden
            textHint.innerHTML = this.responseText;
        }
    };
    //Send value of 1 to check if the data in the database table has expired or not
    xmlhttp.open("GET", "PHP/DailyTreatment.php?check=" + 1, true);
    xmlhttp.send();
}
//To display member's information on the dialog box
function display() {
    var modal = document.getElementById("modal2");
    modal.style.display = "block";
}

function hide() {
    var modal = document.getElementById("modal2");
    modal.style.display = "none";
}